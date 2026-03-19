<?php

namespace App\Domains\Billing\Services;

use App\Domains\Billing\Models\Customer;
use App\Domains\Billing\Models\Invoice;
use Illuminate\Support\Str;

class BillingService
{
    /**
     * Buat pelanggan baru (PPPoE/Hotspot Bulanan)
     */
    public function createCustomer(array $data): Customer
    {
        // TODO: Memicu Queue Job untuk create user di Router API / Radius DB
        return Customer::create($data);
    }

    /**
     * Generate Invoice untuk seorang Pelanggan
     */
    public function generateInvoice(Customer $customer): Invoice
    {
        $invNumber = 'INV-' . date('Ym') . '-' . strtoupper(Str::random(4));

        return Invoice::create([
            'invoice_number' => $invNumber,
            'customer_id' => $customer->id,
            'total_amount' => $customer->monthly_price,
            'status' => 'UNPAID',
            'due_date' => now()->addDays(7)->toDateString(),
        ]);
    }

    /**
     * Bayar / Lunasi Tagihan
     */
    public function payInvoice(Invoice $invoice): Invoice
    {
        $invoice->update([
            'status' => 'PAID',
            'paid_date' => now(),
        ]);

        // Jika user disuspend, aktifkan kembali
        if ($invoice->customer->status === 'SUSPENDED') {
            $invoice->customer->update(['status' => 'ACTIVE']);
            // TODO: Memicu Queue Job untuk unsuspend di Router / Radius
        }

        return $invoice;
    }
}
