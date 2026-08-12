<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Test Super',
            'email' => 'super@email.com',
            'role' => 'super',
            'password' => Hash::make('password')
        ]);

        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@email.com',
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);
    }
}
