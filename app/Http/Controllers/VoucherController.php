<?php

namespace App\Http\Controllers;

use App\Domains\Hotspot\Models\VoucherBatch;
use App\Domains\Hotspot\Services\VoucherService;
use App\Domains\Router\Models\Router;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VoucherController extends Controller
{
    private $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    public function index()
    {
        $batches = VoucherBatch::orderBy('id', 'desc')->with('router')->get();
        $routers = Router::where('status', 'online')->get();

        return Inertia::render('Vouchers/Index', [
            'batches' => $batches,
            'routers' => $routers,
        ]);
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'profile_name' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1', 'max:5000'],
            'router_id' => ['nullable', 'exists:routers,id']
        ]);

        $this->voucherService->generateBatch($data);

        return redirect()->back()->with('success', "Berhasil men-generate {$data['quantity']} voucher dengan status UNPAID.");
    }

    public function activate(VoucherBatch $batch)
    {
        if ($batch->status === 'PAID') {
            return redirect()->back()->with('error', 'Batch ini sudah dibayar/aktif.');
        }

        $this->voucherService->sellBatch($batch);

        return redirect()->back()->with('success', 'Berhasil mengaktifkan Batch Voucher! Nilai penjualan diakui.');
    }
}
