<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RadiusGroupsMap extends Model
{
    use HasFactory;

    protected $table = 'radius_groups_maps';

    protected $fillable = [
        'groupname', 'attribute', 'op', 'value', 'type',
        'profile_type', 'profile_id'
    ];

    /**
     * Get the parent profile model (HotspotProfile / PppoeProfile).
     */
    public function profile(): MorphTo
    {
        return $this->morphTo();
    }
}