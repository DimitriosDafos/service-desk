<?php

namespace App\Ticketing\Models;

use App\Tenancy\Models\Tenant;
use App\Tenancy\Models\Queue as TenancyQueue;
use App\Tenancy\Models\Group;
use App\Models\User;
use App\SLA\Models\SlaPolicy;
use App\Ticketing\Enums\TicketStatus;
use App\Ticketing\Enums\TicketPriority;
use App\Ticketing\Enums\TicketImpact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'ticket_number',
        'title',
        'description',
        'status',
        'priority',
        'impact',
        'requester_id',
        'assigned_agent_id',
        'assigned_group_id',
        'queue_id',
        'sla_policy_id',
        'parent_ticket_id',
        'resolved_at',
        'closed_at',
        'first_response_at',
        'custom_fields',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'impact' => TicketImpact::class,
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'first_response_at' => 'datetime',
        'custom_fields' => 'array',
    ];

    protected $with = [
        'requester',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function assignedGroup(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'assigned_group_id');
    }

    public function queue(): BelongsTo
    {
        return $this->belongsTo(TenancyQueue::class);
    }

    public function slaPolicy(): BelongsTo
    {
        return $this->belongsTo(SlaPolicy::class);
    }

    public function parentTicket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'parent_ticket_id');
    }

    public function childTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'parent_ticket_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function publicComments(): HasMany
    {
        return $this->hasMany(TicketComment::class)->where('is_internal', false);
    }

    public function internalNotes(): HasMany
    {
        return $this->hasMany(TicketComment::class)->where('is_internal', true);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TicketTag::class, 'ticket_ticket_tag', 'ticket_id', 'ticket_tag_id');
    }

    public function slaBreaches(): HasMany
    {
        return $this->hasMany(SlaBreach::class);
    }

    public function linkedAssets(): BelongsToMany
    {
        return $this->belongsToMany(\App\CMDB\Models\Asset::class, 'ticket_assets');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [
            TicketStatus::NEW,
            TicketStatus::TRIAGED,
            TicketStatus::IN_PROGRESS,
            TicketStatus::PENDING,
        ]);
    }

    public function scopeClosed($query)
    {
        return $query->whereIn('status', [
            TicketStatus::RESOLVED,
            TicketStatus::CLOSED,
            TicketStatus::CANCELLED,
        ]);
    }

    public function scopeAssignedToMe($query, int $userId)
    {
        return $query->where('assigned_agent_id', $userId);
    }

    public function scopeMyQueues($query, array $queueIds)
    {
        return $query->whereIn('queue_id', $queueIds);
    }

    public static function generateTicketNumber(string $tenantId): string
    {
        $tenant = \App\Tenancy\Models\Tenant::find($tenantId);
        $prefix = 'TKT-' . ($tenant ? strtoupper(substr($tenant->name, 0, 3)) : 'TEN') . '-';
        $lastTicket = self::where('tenant_id', $tenantId)
            ->orderByDesc('created_at')
            ->first();
        
        $nextNumber = $lastTicket 
            ? (intval(substr($lastTicket->ticket_number, -8)) + 1) 
            : 1;
        
        return $prefix . str_pad($nextNumber, 8, '0', STR_PAD_LEFT);
    }
}
