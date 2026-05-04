<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@system.com'],
            [
                'tenant_id' => null,
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin123'),
                'role' => 'system_owner',
                'is_active' => true,
            ]
        );

        $this->command->info('Super Admin created!');
        $this->command->info('Email: superadmin@system.com');
        $this->command->info('Password: superadmin123');
    }
}
