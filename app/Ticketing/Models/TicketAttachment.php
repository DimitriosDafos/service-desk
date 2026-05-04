<?php

namespace App\Ticketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class TicketAttachment extends Model
{
    use HasUuids;

    protected $table = 'ticket_attachments';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'filename',
        'original_filename',
        'mime_type',
        'size',
        'path',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
