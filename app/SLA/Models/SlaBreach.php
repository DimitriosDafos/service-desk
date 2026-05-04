<?php

namespace App\SLA\Models;

use App\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Ticketing\Models\Ticket;

class SlaBreach extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'ticket_id',
        'sla_policy_id',
        'breach_type',
        'breached_at',
        'acknowledged_at',
    ];

    protected $casts = [
        'breached_at' => 'datetime',
        'acknowledged_at' => 'datetime',
    ];

    public const BREACH_RESPONSE = 'response';
    public const BREACH_RESOLUTION = 'resolution';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function slaPolicy(): BelongsTo
    {
        return $this->belongsTo(SlaPolicy::class);
    }
}
