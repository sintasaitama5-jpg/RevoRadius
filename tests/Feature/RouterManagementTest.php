<?php

namespace Tests\Feature;

use App\Models\User;
use App\Domains\Router\Models\Router;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Crypt;

class RouterManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_routers()
    {
        $user = User::factory()->create();
        Router::create([
            'name' => 'Site A',
            'ip_address' => '10.0.0.1',
            'api_port' => 8728,
            'os_version' => 'v7',
            'username' => 'admin',
            'password' => Crypt::encryptString('secret'),
        ]);

        $response = $this->actingAs($user)->get('/routers');

        $response->assertStatus(200);
        // Inertia response
        $response->assertInertia(fn ($page) => $page
            ->component('Routers/Index')
            ->has('routers', 1)
        );
    }

    public function test_user_can_create_router()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/routers', [
            'name' => 'Site B',
            'ip_address' => '10.0.0.2',
            'api_port' => 8728,
            'os_version' => 'v7',
            'username' => 'admin',
            'password' => 'secret_mikrotik',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('routers', [
            'name' => 'Site B',
            'ip_address' => '10.0.0.2',
        ]);

        $router = Router::first();
        // Password harus terenkripsi
        $this->assertNotEquals('secret_mikrotik', $router->password);
        $this->assertEquals('secret_mikrotik', Crypt::decryptString($router->password));
    }

    public function test_user_can_delete_router()
    {
        $user = User::factory()->create();
        $router = Router::create([
            'name' => 'Site C',
            'ip_address' => '10.0.0.3',
            'api_port' => 8728,
            'os_version' => 'v7',
            'username' => 'admin',
            'password' => Crypt::encryptString('secret'),
        ]);

        $response = $this->actingAs($user)->delete("/routers/{$router->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('routers', [
            'id' => $router->id,
        ]);
    }
}
