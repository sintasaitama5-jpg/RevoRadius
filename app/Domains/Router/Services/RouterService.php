<?php

namespace App\Domains\Router\Services;

use App\Domains\Router\Models\Router;
use Illuminate\Support\Facades\Crypt;
use Exception;

class RouterService
{
    /**
     * Create a new router profile
     */
    public function createRouter(array $data): Router
    {
        // Encrypt the password before saving to the database
        $data['password'] = Crypt::encryptString($data['password']);
        $data['status'] = 'offline';

        return Router::create($data);
    }

    /**
     * Update existing router
     */
    public function updateRouter(Router $router, array $data): Router
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Crypt::encryptString($data['password']);
        } else {
            unset($data['password']);
        }

        $router->update($data);
        return $router;
    }

    /**
     * Delete router securely
     */
    public function deleteRouter(Router $router): bool
    {
        return $router->delete();
    }

    /**
     * Test connection to Mikrotik (Simulation for Phase 2)
     */
    public function testConnection(Router $router): array
    {
        try {
            // Get decrypted password
            $password = Crypt::decryptString($router->password);

            // In a real application, we would use a Mikrotik API Client here
            // Example:
            // $client = new \RouterOS\Client([
            //    'host' => $router->ip_address,
            //    'user' => $router->username,
            //    'pass' => $password,
            //    'port' => $router->api_port,
            // ]);

            // For now, simulate network ping test
            $timeout = 2;
            $fp = @fsockopen($router->ip_address, $router->api_port, $errno, $errstr, $timeout);

            if (!$fp) {
                $router->update(['status' => 'offline']);
                return [
                    'success' => false,
                    'message' => "Gagal terhubung ke {$router->ip_address}:{$router->api_port} - {$errstr}"
                ];
            }

            fclose($fp);

            // Simulating successful API login
            $router->update(['status' => 'online']);

            return [
                'success' => true,
                'message' => 'Koneksi ke Router Berhasil (Port Terbuka)!'
            ];

        } catch (Exception $e) {
            $router->update(['status' => 'error']);
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ];
        }
    }
}
