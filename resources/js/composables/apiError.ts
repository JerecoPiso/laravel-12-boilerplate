/**
 * Build a readable message from an API error response. Handles both
 * validation and database errors, which share this shape:
 * { success: false, message: string, errors?: { [field]: string[] } }
 */
export function apiErrorMessage(err: any, fallback: string = 'Something went wrong'): string {
    const data = err?.response?.data;
    const errors = data?.errors;

    if (errors && typeof errors === 'object') {
        const messages = Object.values(errors)
            .flat()
            .filter((msg): msg is string => typeof msg === 'string' && msg.length > 0);

        if (messages.length) {
            return messages.join(' ');
        }
    }

    return data?.message || fallback;
}
