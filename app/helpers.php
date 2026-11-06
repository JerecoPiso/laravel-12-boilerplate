<?php

use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

if (!function_exists('api_response')) {
    function api_response($data = [], $success = true, $message = 'Success', $code = 200)
    {
        // Exception codes can be non-HTTP (e.g. PDO's "HY000"); fall back to 500.
        $code = (is_numeric($code) && $code >= 100 && $code <= 599) ? (int) $code : 500;

        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}

if (!function_exists('api_error')) {
    /**
     * Build an error response from any exception. Database errors are
     * turned into field errors with the same shape as validation errors.
     */
    function api_error(\Throwable $e)
    {
        for ($ex = $e; $ex; $ex = $ex->getPrevious()) {
            if ($ex instanceof QueryException) {
                return db_error_response($ex);
            }
        }

        return api_response([], false, $e->getMessage(), $e->getCode() ?: 500);
    }
}

if (!function_exists('db_error_response')) {
    /**
     * Convert a QueryException into a validation-style JSON response:
     * { success: false, message: "Invalid data.", errors: { field: [msg] } }
     */
    function db_error_response(QueryException $e)
    {
        $driverCode = (int) ($e->errorInfo[1] ?? 0);
        $driverMessage = (string) ($e->errorInfo[2] ?? $e->getMessage());
        $label = fn (string $column) => str_replace('_', ' ', $column);

        $column = null;
        $error = null;
        $status = 422;

        switch ($driverCode) {
            case 1048: // Column 'x' cannot be null
            case 1364: // Field 'x' doesn't have a default value
                if (preg_match("/(?:Column|Field) '([^']+)'/", $driverMessage, $m)) {
                    $column = $m[1];
                    $error = "The {$label($column)} field is required.";
                }
                break;

            case 1062: // Duplicate entry 'v' for key 'users.users_email_unique'
                if (preg_match("/for key '(?:[^.']+\.)?([^']+)'/", $driverMessage, $m)) {
                    $column = db_column_from_index($m[1], db_table_from_sql($e->getSql()));
                    $error = "The {$label($column)} has already been taken.";
                }
                break;

            case 1406: // Data too long for column 'x'
                if (preg_match("/column '([^']+)'/", $driverMessage, $m)) {
                    $column = $m[1];
                    $error = "The {$label($column)} field is too long.";
                }
                break;

            case 1264: // Out of range value for column 'x'
                if (preg_match("/column '([^']+)'/", $driverMessage, $m)) {
                    $column = $m[1];
                    $error = "The {$label($column)} field is out of range.";
                }
                break;

            case 1265: // Data truncated for column 'x'
            case 1292: // Incorrect date/datetime value: 'v' for column 'x'
            case 1366: // Incorrect integer/string value: 'v' for column 'x'
                if (preg_match("/column '([^']+)'/", $driverMessage, $m)) {
                    $column = $m[1];
                    $error = "The {$label($column)} field has an invalid value.";
                }
                break;

            case 1452: // Cannot add or update a child row: FOREIGN KEY (`x`) REFERENCES ...
                if (preg_match('/FOREIGN KEY \(`([^`]+)`\)/', $driverMessage, $m)) {
                    $column = $m[1];
                    $error = "The selected {$label($column)} is invalid.";
                }
                break;

            case 1451: // Cannot delete or update a parent row
                return response()->json([
                    'success' => false,
                    'message' => 'This record is still in use by other records and cannot be changed or deleted.',
                    'errors' => [],
                ], 409);

            case 1054: // Unknown column 'x' in 'field list'
                if (preg_match("/Unknown column '(?:[^.']+\.)?([^']+)'/", $driverMessage, $m)) {
                    $column = $m[1];
                    $error = "The {$label($column)} field does not exist.";
                    $status = 500;
                }
                break;
        }

        if ($column && $error) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data.',
                'errors' => [$column => [$error]],
            ], $status);
        }

        // Unrecognised database error: only expose driver details in debug mode.
        return response()->json([
            'success' => false,
            'message' => config('app.debug') ? $driverMessage : 'A database error occurred.',
            'errors' => [],
        ], 500);
    }
}

if (!function_exists('db_table_from_sql')) {
    function db_table_from_sql(?string $sql): ?string
    {
        if ($sql && preg_match('/\b(?:into|update|from)\s+[`"]?(\w+)[`"]?/i', $sql, $m)) {
            return $m[1];
        }

        return null;
    }
}

if (!function_exists('db_column_from_index')) {
    /**
     * Turn Laravel's index name (e.g. "users_email_unique") into its column ("email").
     */
    function db_column_from_index(string $index, ?string $table): string
    {
        if (strtoupper($index) === 'PRIMARY') {
            return 'id';
        }

        $column = Str::beforeLast($index, '_unique');
        if ($table && Str::startsWith($column, "{$table}_")) {
            $column = Str::after($column, "{$table}_");
        }

        return $column;
    }
}
