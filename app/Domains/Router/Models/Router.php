<?php

namespace App\Domains\Router\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Router extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'ip_address',
        'api_port',
        'os_version',
        'username',
        'password',
        'status',
    ];

    protected $hidden = [
        'password', // Jangan pernah return password plaintext ke Vue frontend
    ];

    protected $casts = [
        'api_port' => 'integer',
    ];
}
