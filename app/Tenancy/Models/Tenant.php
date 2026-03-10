<?php

namespace App\Tenancy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tenant extends Model
{
    use HasUuids;
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(\App\Ticketing\Models\Ticket::class);
    }

    public function slaPolicies(): HasMany
    {
        return $this->hasMany(\App\SLA\Models\SlaPolicy::class);
    }

    public function knowledgeBaseArticles(): HasMany
    {
        return $this->hasMany(\App\KnowledgeBase\Models\Article::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(\App\CMDB\Models\Asset::class);
    }

    public function automationRules(): HasMany
    {
        return $this->hasMany(\App\Automation\Models\AutomationRule::class);
    }

    public function holidayCalendars(): HasMany
    {
        return $this->hasMany(\App\SLA\Models\HolidayCalendar::class);
    }
}
