<?php

namespace App\Domains\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Router\Models\Router;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'username', 'password', 'profile_name',
        'monthly_price', 'status', 'billing_cycle_date', 'router_id'
    ];

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
