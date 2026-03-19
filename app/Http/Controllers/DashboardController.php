<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'routers' => 0,
                'active_vouchers' => 0,
                'pppoe_members' => 0,
                'unpaid_invoices' => 0
            ]
        ]);
    }
}
