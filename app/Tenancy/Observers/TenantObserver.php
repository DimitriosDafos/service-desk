<?php

namespace App\Tenancy\Observers;

use App\Tenancy\Models\Tenant;

class TenantObserver
{
    public function created(Tenant $tenant): void
    {
        $tenant->makeCurrent();
        
        $tenant->domains()->create([
            'domain' => $tenant->slug . '.localhost',
            'is_primary' => true,
        ]);
    }
}
