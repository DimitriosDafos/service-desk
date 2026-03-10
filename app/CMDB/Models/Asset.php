<?php

namespace App\CMDB\Models;

use App\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Asset extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'asset_type_id',
        'name',
        'serial_number',
        'asset_tag',
        'description',
        'status',
        'purchase_date',
        'warranty_expiry',
        'location',
        'custom_fields',
        'assigned_user_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'custom_fields' => 'array',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_RETIRED = 'retired';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function parentAssets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'asset_relationships', 'child_asset_id', 'parent_asset_id')
            ->withPivot('relationship_type');
    }

    public function childAssets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'asset_relationships', 'parent_asset_id', 'child_asset_id')
            ->withPivot('relationship_type');
    }

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(\App\Ticketing\Models\Ticket::class, 'ticket_assets');
    }
}
