<?php

namespace App\Tenancy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Queue extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'group_id',
        'name',
        'description',
        'email',
        'is_active',
        'auto_assign',
        'assignment_strategy',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_assign' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function slaPolicy(): BelongsTo
    {
        return $this->belongsTo(\App\SLA\Models\SlaPolicy::class, 'sla_policy_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(\App\Ticketing\Models\Ticket::class);
    }
}
