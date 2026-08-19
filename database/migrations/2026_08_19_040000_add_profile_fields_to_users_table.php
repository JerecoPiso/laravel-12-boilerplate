<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pid')->unique()->after('id');
            $table->string('firstname')->after('email');
            $table->string('lastname')->after('firstname');
            $table->string('middlename')->nullable()->after('lastname');
            $table->string('suffix', 10)->nullable()->after('middlename');
            $table->enum('gender', ['Male', 'Female'])->default('Male')->after('suffix');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pid',
                'firstname',
                'lastname',
                'middlename',
                'suffix',
                'gender',
                'date_of_birth',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
