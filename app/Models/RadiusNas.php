<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RadiusNas extends Model
{
    use HasFactory;

    protected $table = 'radius_nas';

    protected $fillable = [
        'nasname', 'shortname', 'type', 'ports', 'secret',
        'server', 'community', 'description', 'router_id'
    ];

    /**
     * Relationship: NAS belongs to an internal Mikrotik Router
     */
    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class, 'router_id');
    }
}
