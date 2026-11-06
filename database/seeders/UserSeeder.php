<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'pid' => Str::uuid()->toString(),
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        User::factory(9)->create();
    }
}
