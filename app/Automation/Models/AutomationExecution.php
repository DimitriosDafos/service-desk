<?php

namespace App\Automation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomationExecution extends Model
{
    use HasUuids;

    protected $table = 'automation_executions';

    protected $fillable = [
        'rule_id',
        'ticket_id',
        'trigger_event',
        'actions_executed',
        'status',
        'executed_at',
        'completed_at',
        'error_message',
    ];

    protected $casts = [
        'actions_executed' => 'array',
        'executed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(AutomationRule::class);
    }
}
