<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RadiusSession extends Model
{
    use HasFactory;

    protected $table = 'radius_sessions';

    protected $fillable = [
        'username', 'acctsessionid', 'nasipaddress', 'framedipaddress',
        'callingstationid', 'acctstarttime', 'last_update_time',
        'acctinputoctets', 'acctoutputoctets', 'radius_users_map_id'
    ];

    protected $casts = [
        'acctstarttime' => 'datetime',
        'last_update_time' => 'datetime',
    ];

    public function userMap(): BelongsTo
    {
        return $this->belongsTo(RadiusUsersMap::class, 'radius_users_map_id');
    }
}