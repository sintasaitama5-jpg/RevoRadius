<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RadiusUsersMap extends Model
{
    use HasFactory;

    protected $table = 'radius_users_maps';

    protected $fillable = [
        'username', 'attribute', 'op', 'value',
        'member_type', 'member_id', 'groupname', 'is_active'
    ];

    /**
     * Get the parent member model (Voucher or CustomerService).
     */
    public function member(): MorphTo
    {
        return $this->morphTo();
    }
}