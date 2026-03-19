<?php

namespace App\Domains\Hotspot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Router\Models\Router;

class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'voucher_batch_id',
        'code',
        'password',
        'profile_name',
        'price',
        'status',
        'router_id'
    ];

    public function batch()
    {
        return $this->belongsTo(VoucherBatch::class, 'voucher_batch_id');
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }
}
