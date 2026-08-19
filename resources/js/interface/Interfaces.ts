export interface User {
    pid?: string;
    email: string;
    firstname: string;
    middlename?: string;
    lastname: string;
    suffix?: string;
    gender: string;
    date_of_birth: Date;
    password: string;
}
