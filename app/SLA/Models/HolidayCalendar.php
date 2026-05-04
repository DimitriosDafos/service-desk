<?php

namespace App\SLA\Models;

use App\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HolidayCalendar extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'name',
        'year',
        'holidays',
    ];

    protected $casts = [
        'holidays' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
