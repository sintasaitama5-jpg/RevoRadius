<?php

namespace App\Domains\Hotspot\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Router\Models\Router;

class VoucherBatch extends Model
{
    protected $fillable = ['name', 'profile_name', 'price', 'quantity', 'status', 'router_id'];

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }
}
