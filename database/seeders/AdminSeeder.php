<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => env('ADMIN_USERNAME', 'Admin'),
            'email' => env('ADMIN_MAIL', 'admin@example.com'),
            'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);
    }
}
