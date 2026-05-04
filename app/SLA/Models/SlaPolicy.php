<?php

namespace App\SLA\Models;

use App\Tenancy\Models\Tenant;
use App\Tenancy\Models\Queue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SlaPolicy extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'priority',
        'response_time_minutes',
        'resolution_time_minutes',
        'business_hours_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'response_time_minutes' => 'integer',
        'resolution_time_minutes' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class, 'sla_policy_id');
    }

    public function businessHours(): BelongsTo
    {
        return $this->belongsTo(BusinessHours::class);
    }
}
