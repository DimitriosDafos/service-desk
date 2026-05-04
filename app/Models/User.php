<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Tenancy\Models\Tenant;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'department',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    const ROLE_SYSTEM_OWNER = 'system_owner';
    const ROLE_SYSTEM_ADMIN = 'system_admin';
    const ROLE_TENANT_ADMIN = 'tenant_admin';
    const ROLE_AGENT = 'agent';
    const ROLE_REQUESTER = 'requester';
    const ROLE_AUDITOR = 'auditor';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(\App\Tenancy\Models\Group::class, 'group_user');
    }

    public function assignedTickets()
    {
        return $this->hasMany(\App\Ticketing\Models\Ticket::class, 'assigned_agent_id');
    }

    public function requestedTickets()
    {
        return $this->hasMany(\App\Ticketing\Models\Ticket::class, 'requester_id');
    }

    public function isSystemLevel(): bool
    {
        return in_array($this->role, [self::ROLE_SYSTEM_OWNER, self::ROLE_SYSTEM_ADMIN]);
    }

    public function isTenantAdmin(): bool
    {
        return $this->role === self::ROLE_TENANT_ADMIN;
    }

    public function isAgent(): bool
    {
        return in_array($this->role, [self::ROLE_TENANT_ADMIN, self::ROLE_AGENT]);
    }

    public function isRequester(): bool
    {
        return $this->role === self::ROLE_REQUESTER;
    }

    public function canManageTenant(): bool
    {
        return in_array($this->role, [self::ROLE_SYSTEM_OWNER, self::ROLE_SYSTEM_ADMIN, self::ROLE_TENANT_ADMIN]);
    }

    public function canManageTickets(): bool
    {
        return in_array($this->role, [self::ROLE_TENANT_ADMIN, self::ROLE_AGENT]);
    }

    public function getQueueIds(): array
    {
        return $this->groups()
            ->with('queues')
            ->get()
            ->pluck('queues.*.id')
            ->flatten()
            ->unique()
            ->toArray();
    }
}
