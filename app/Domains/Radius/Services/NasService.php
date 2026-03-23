<?php

namespace App\Domains\Radius\Services;

use App\Models\Router;
use App\Models\RadiusNas;
use Illuminate\Support\Str;

class NasService
{
    /**
     * Daftarkan Mikrotik Router sebagai NAS di database FreeRADIUS
     */
    public function registerRouterAsNas(Router $router, string $secret = null): RadiusNas
    {
        // Secret bisa digenerate otomatis atau diambil dari param
        $nasSecret = $secret ?? Str::random(16);

        $nas = RadiusNas::updateOrCreate(
            ['router_id' => $router->id],
            [
                'nasname' => $router->ip_address,
                'shortname' => Str::slug($router->name),
                'type' => 'mikrotik',
                'ports' => null,
                'secret' => $nasSecret,
                'description' => "Auto-registered from Router: {$router->name}"
            ]
        );

        return $nas;
    }

    /**
     * Hapus Router dari list NAS FreeRADIUS
     */
    public function unregisterRouterAsNas(Router $router): bool
    {
        return RadiusNas::where('router_id', $router->id)->delete() > 0;
    }
}
