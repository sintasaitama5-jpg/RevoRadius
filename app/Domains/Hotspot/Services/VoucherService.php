<?php

namespace App\Domains\Hotspot\Services;

use App\Domains\Hotspot\Models\Voucher;
use App\Domains\Hotspot\Models\VoucherBatch;
use Illuminate\Support\Str;

class VoucherService
{
    /**
     * Generate Voucher secara Massal
     */
    public function generateBatch(array $data): VoucherBatch
    {
        $batch = VoucherBatch::create([
            'name' => 'BATCH-' . strtoupper(Str::random(6)),
            'profile_name' => $data['profile_name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'status' => 'UNPAID', // Secara default belum dibayar (belum diakui sbg pendapatan)
            'router_id' => $data['router_id'] ?? null,
        ]);

        $vouchers = [];
        for ($i = 0; $i < $data['quantity']; $i++) {
            $vouchers[] = [
                'voucher_batch_id' => $batch->id,
                'code' => $this->generateUniqueCode(),
                'password' => Str::random(4),
                'profile_name' => $data['profile_name'],
                'price' => $data['price'],
                'status' => 'UNPAID',
                'router_id' => $data['router_id'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert menggunakan Chunk agar cepat untuk mass insert ribuan data
        foreach (array_chunk($vouchers, 1000) as $chunk) {
            Voucher::insert($chunk);
        }

        return $batch;
    }

    /**
     * Aktivasi / Tandai voucher sebagai sudah terjual
     */
    public function sellBatch(VoucherBatch $batch): bool
    {
        $batch->update(['status' => 'PAID']);

        // Tandai semua voucher di batch ini sebagai PAID
        Voucher::where('voucher_batch_id', $batch->id)
            ->where('status', 'UNPAID')
            ->update(['status' => 'PAID']);

        // TODO: Memicu Queue Job untuk menambahkan User Kredensial ke Router/FreeRADIUS asli
        // dispatch(new \App\Jobs\SyncVoucherToRouterJob($batch));

        return true;
    }

    private function generateUniqueCode(int $length = 6): string
    {
        $code = strtoupper(Str::random($length));

        // Pastikan unik
        while (Voucher::where('code', $code)->exists()) {
            $code = strtoupper(Str::random($length));
        }

        return $code;
    }
}
