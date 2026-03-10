<?php

namespace App\Automation\Models;

use App\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutomationRule extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'trigger_event',
        'trigger_conditions',
        'actions',
        'is_active',
        'execution_order',
    ];

    protected $casts = [
        'trigger_conditions' => 'array',
        'actions' => 'array',
        'is_active' => 'boolean',
    ];

    const TRIGGER_TICKET_CREATED = 'ticket.created';
    const TRIGGER_TICKET_UPDATED = 'ticket.updated';
    const TRIGGER_TICKET_STATUS_CHANGED = 'ticket.status_changed';
    const TRIGGER_TICKET_ASSIGNED = 'ticket.assigned';
    const TRIGGER_SLA_BREACH_WARNING = 'sla.breach_warning';
    const TRIGGER_SLA_BREACHED = 'sla.breached';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function executions(): HasMany
    {
        return $this->hasMany(AutomationExecution::class);
    }
}
