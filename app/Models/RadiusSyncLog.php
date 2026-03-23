<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RadiusSyncLog extends Model
{
    use HasFactory;

    protected $table = 'radius_sync_logs';

    protected $fillable = [
        'model_type', 'model_id', 'action', 'payload', 'status', 'message', 'processed_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];

    public function loggable(): MorphTo
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }
}