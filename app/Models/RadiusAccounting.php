<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RadiusAccounting extends Model
{
    use HasFactory;

    protected $table = 'radius_accounting';
    protected $primaryKey = 'radacctid';

    protected $fillable = [
        'acctsessionid', 'acctuniqueid', 'username', 'groupname',
        'nasipaddress', 'nasportid', 'nasporttype',
        'acctstarttime', 'acctupdatetime', 'acctstoptime',
        'acctinterval', 'acctsessiontime', 'acctauthentic',
        'connectinfo_start', 'connectinfo_stop',
        'acctinputoctets', 'acctoutputoctets',
        'calledstationid', 'callingstationid',
        'acctterminatecause', 'servicetype',
        'framedprotocol', 'framedipaddress',
        'user_map_id'
    ];

    protected $casts = [
        'acctstarttime' => 'datetime',
        'acctupdatetime' => 'datetime',
        'acctstoptime' => 'datetime',
    ];

    public function userMap(): BelongsTo
    {
        return $this->belongsTo(RadiusUsersMap::class, 'user_map_id');
    }
}