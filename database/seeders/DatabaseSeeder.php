<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@materfasum.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'phone'    => '081234567890',
        ]);

        // User demo
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'user@materfasum.id',
            'password' => Hash::make('user123'),
            'role'     => 'user',
            'phone'    => '089876543210',
        ]);
    }
}
