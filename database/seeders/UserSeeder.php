
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = env('NAMEERA_ADMIN_PASSWORD');
        $ownerPassword = env('NAMEERA_OWNER_PASSWORD');

        if (!$adminPassword || !$ownerPassword) {
            throw new \RuntimeException(
                'Password Admin dan Owner belum dikonfigurasi.'
            );
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@nameera.com'],
            [
                'name' => 'Admin Nameera',
                'password' => $adminPassword,
            ]
        );

        $owner = User::firstOrCreate(
            ['email' => 'owner@nameera.com'],
            [
                'name' => 'Owner Nameera',
                'password' => $ownerPassword,
            ]
        );

        $admin->assignRole('admin');
        $owner->assignRole('owner');
    }
}
