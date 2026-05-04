<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Tenancy\Models\Tenant;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'demo-company'],
            [
                'name' => 'Demo Company',
                'email' => 'admin@democompany.com',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@democompany.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'tenant_admin',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'agent@democompany.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Support Agent',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'john@democompany.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'requester',
                'is_active' => true,
            ]
        );

        $this->command->info('Demo users seeded successfully!');
        $this->command->info('Admin: admin@democompany.com / password');
        $this->command->info('Agent: agent@democompany.com / password');
        $this->command->info('Requester: john@democompany.com / password');
    }
}
