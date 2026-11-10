<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
   public function run(): void
    {
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@nameera.com'
            ],
            [
                'name' => 'Admin Nameera',
                'password' => 'password123'
            ]
        );

        $owner = User::firstOrCreate(
            [
                'email' => 'owner@nameera.com'
            ],
            [
                'name' => 'Owner Nameera',
                'password' => 'password123'
            ]
        );

        $admin->assignRole('admin');

        $owner->assignRole('owner');
    }
}