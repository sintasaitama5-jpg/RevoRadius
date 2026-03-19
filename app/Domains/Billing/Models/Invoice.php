<?php

namespace App\Domains\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'customer_id', 'total_amount',
        'status', 'due_date', 'paid_date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
