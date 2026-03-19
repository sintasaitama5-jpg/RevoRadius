<?php

namespace Tests\Feature;

use App\Models\User;
use App\Domains\Billing\Models\Customer;
use App\Domains\Billing\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_customers_and_invoices()
    {
        $user = User::factory()->create();

        $response1 = $this->actingAs($user)->get('/customers');
        $response1->assertStatus(200);

        $response2 = $this->actingAs($user)->get('/billing');
        $response2->assertStatus(200);
    }

    public function test_user_can_create_customer_and_auto_generate_invoice()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/customers', [
            'name' => 'John Doe',
            'username' => 'johndoe_pppoe',
            'password' => 'secret',
            'profile_name' => '10Mbps',
            'monthly_price' => 150000,
            'billing_cycle_date' => '2023-01-05',
            'router_id' => null,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('customers', [
            'username' => 'johndoe_pppoe',
        ]);

        // Auto-generate invoice requirement
        $this->assertDatabaseCount('invoices', 1);

        $invoice = Invoice::first();
        $this->assertEquals(150000, $invoice->total_amount);
        $this->assertEquals('UNPAID', $invoice->status);
    }

    public function test_user_can_pay_invoice_and_unsuspend_customer()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/customers', [
            'name' => 'Jane Smith',
            'username' => 'jane',
            'password' => '123',
            'profile_name' => '10M',
            'monthly_price' => 200000,
            'billing_cycle_date' => '2024-01-01',
        ]);

        $customer = Customer::first();
        $invoice = Invoice::first();

        // Simulate customer was suspended for overdue invoice
        $customer->update(['status' => 'SUSPENDED']);

        // User (Cashier) Pays the invoice
        $response = $this->actingAs($user)->post("/billing/invoices/{$invoice->id}/pay");

        $response->assertRedirect();

        // Ensure invoice is PAID
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'PAID',
        ]);

        // Ensure customer is unsuspended (ACTIVE)
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'status' => 'ACTIVE'
        ]);
    }
}
