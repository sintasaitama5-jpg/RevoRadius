<?php

namespace App\Http\Controllers;

use App\Domains\Router\Models\Router;
use App\Domains\Hotspot\Models\Voucher;
use App\Domains\Billing\Models\Customer;
use App\Domains\Billing\Models\Invoice;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'routers' => Router::count(),
                'active_vouchers' => Voucher::where('status', 'ACTIVE')->count(),
                'pppoe_members' => Customer::where('status', 'ACTIVE')->count(),
                'unpaid_invoices' => Invoice::whereIn('status', ['UNPAID', 'OVERDUE'])->count()
            ]
        ]);
    }
}
