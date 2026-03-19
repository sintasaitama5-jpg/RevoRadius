<?php

namespace App\Http\Controllers;

use App\Domains\Billing\Models\Invoice;
use App\Domains\Billing\Services\BillingService;
use Inertia\Inertia;

class BillingController extends Controller
{
    private $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function index()
    {
        $invoices = Invoice::with('customer')->orderBy('id', 'desc')->get();

        return Inertia::render('Billing/Invoices', [
            'invoices' => $invoices
        ]);
    }

    public function pay(Invoice $invoice)
    {
        if ($invoice->status === 'PAID') {
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas.');
        }

        $this->billingService->payInvoice($invoice);

        return redirect()->back()->with('success', 'Tagihan ' . $invoice->invoice_number . ' Lunas! Layanan diaktifkan kembali.');
    }
}
