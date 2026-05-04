<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Tenancy\Models\Tenant;
use App\Tenancy\Models\Group;
use App\Tenancy\Models\Queue;
use App\SLA\Models\SlaPolicy;
use App\SLA\Models\BusinessHours;

class ServiceDeskSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'name' => 'Demo Company',
            'slug' => 'demo-company',
            'email' => 'admin@democompany.com',
            'is_active' => true,
        ]);

        $businessHours = BusinessHours::create([
            'tenant_id' => $tenant->id,
            'name' => 'Standard Business Hours',
            'timezone' => 'Europe/Berlin',
            'monday_start' => '09:00',
            'monday_end' => '17:00',
            'tuesday_start' => '09:00',
            'tuesday_end' => '17:00',
            'wednesday_start' => '09:00',
            'wednesday_end' => '17:00',
            'thursday_start' => '09:00',
            'thursday_end' => '17:00',
            'friday_start' => '09:00',
            'friday_end' => '17:00',
        ]);

        $slaPolicies = [
            [
                'name' => 'Critical Priority SLA',
                'priority' => 'critical',
                'response_time_minutes' => 15,
                'resolution_time_minutes' => 240,
                'business_hours_id' => $businessHours->id,
            ],
            [
                'name' => 'High Priority SLA',
                'priority' => 'high',
                'response_time_minutes' => 60,
                'resolution_time_minutes' => 480,
                'business_hours_id' => $businessHours->id,
            ],
            [
                'name' => 'Medium Priority SLA',
                'priority' => 'medium',
                'response_time_minutes' => 240,
                'resolution_time_minutes' => 1440,
                'business_hours_id' => $businessHours->id,
            ],
            [
                'name' => 'Low Priority SLA',
                'priority' => 'low',
                'response_time_minutes' => 1440,
                'resolution_time_minutes' => 4320,
                'business_hours_id' => $businessHours->id,
            ],
        ];

        foreach ($slaPolicies as $policy) {
            $policy['tenant_id'] = $tenant->id;
            $policy['is_active'] = true;
            SlaPolicy::create($policy);
        }

        $adminUser = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin User',
            'email' => 'admin@democompany.com',
            'password' => Hash::make('password'),
            'role' => 'tenant_admin',
            'is_active' => true,
        ]);

        $agentUser = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Support Agent',
            'email' => 'agent@democompany.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'is_active' => true,
        ]);

        $requesterUser = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'John Doe',
            'email' => 'john@democompany.com',
            'password' => Hash::make('password'),
            'role' => 'requester',
            'is_active' => true,
        ]);

        $itSupportGroup = Group::create([
            'tenant_id' => $tenant->id,
            'name' => 'IT Support',
            'description' => 'Technical support and IT issues',
            'is_active' => true,
        ]);

        $hrGroup = Group::create([
            'tenant_id' => $tenant->id,
            'name' => 'HR',
            'description' => 'Human Resources inquiries',
            'is_active' => true,
        ]);

        $itSupportGroup->users()->attach([$adminUser->id, $agentUser->id]);

        $technicalQueue = Queue::create([
            'tenant_id' => $tenant->id,
            'group_id' => $itSupportGroup->id,
            'name' => 'Technical Support',
            'description' => 'Queue for technical issues',
            'email' => 'support@democompany.com',
            'is_active' => true,
            'auto_assign' => true,
        ]);

        $generalQueue = Queue::create([
            'tenant_id' => $tenant->id,
            'group_id' => $hrGroup->id,
            'name' => 'General Inquiries',
            'description' => 'Queue for general questions',
            'is_active' => true,
        ]);

        echo "Seeded demo data successfully!\n";
        echo "Admin user: admin@democompany.com / password\n";
        echo "Agent user: agent@democompany.com / password\n";
        echo "Requester: john@democompany.com / password\n";
    }
}
