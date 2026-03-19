<?php

namespace App\Http\Controllers;

use App\Domains\Billing\Models\Customer;
use App\Domains\Billing\Services\BillingService;
use App\Domains\Router\Models\Router;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    private $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function index()
    {
        $customers = Customer::with('router')->orderBy('id', 'desc')->get();
        $routers = Router::where('status', 'online')->get();

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'routers' => $routers,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'username' => ['required', 'string', 'unique:customers,username'],
            'password' => ['nullable', 'string'],
            'profile_name' => ['required', 'string'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'billing_cycle_date' => ['required', 'date'],
            'router_id' => ['nullable', 'exists:routers,id']
        ]);

        $customer = $this->billingService->createCustomer($data);

        // Auto generate first invoice
        $this->billingService->generateInvoice($customer);

        return redirect()->back()->with('success', 'Pelanggan berhasil ditambahkan & Tagihan Pertama diterbitkan.');
    }
}
