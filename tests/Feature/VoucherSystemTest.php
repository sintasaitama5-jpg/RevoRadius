<?php

namespace Tests\Feature;

use App\Domains\Hotspot\Models\Voucher;
use App\Domains\Hotspot\Models\VoucherBatch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoucherSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_vouchers()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/vouchers');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Vouchers/Index'));
    }

    public function test_user_can_generate_batch_of_vouchers()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/vouchers/generate', [
            'profile_name' => '1 Jam / 5000',
            'price' => 5000,
            'quantity' => 10,
            'router_id' => null, // Global
        ]);

        $response->assertRedirect();

        $this->assertDatabaseCount('voucher_batches', 1);
        // Memastikan ada 10 anak voucher yang tergenerate
        $this->assertDatabaseCount('vouchers', 10);

        $batch = VoucherBatch::first();
        $this->assertEquals('UNPAID', $batch->status);
    }

    public function test_user_can_activate_voucher_batch()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/vouchers/generate', [
            'profile_name' => 'Premium',
            'price' => 10000,
            'quantity' => 5,
            'router_id' => null,
        ]);

        $batch = VoucherBatch::first();

        $response = $this->actingAs($user)->post("/vouchers/batches/{$batch->id}/activate");

        $response->assertRedirect();

        // Memastikan batch berubah status jadi PAID
        $this->assertDatabaseHas('voucher_batches', [
            'id' => $batch->id,
            'status' => 'PAID'
        ]);

        // Memastikan semua anak voucher ikut PAID
        $this->assertEquals(5, Voucher::where('status', 'PAID')->count());
    }
}
