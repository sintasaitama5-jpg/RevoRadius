<template>
    <AppLayout title="Print Voucher Hotspot">
        <div class="px-6 py-6 max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Cetak Voucher</h1>
                    <p class="text-slate-400 text-sm mt-1">Cetak voucher yang baru saja digenerate dalam format A4.</p>
                </div>
                <div class="flex space-x-3 no-print">
                    <button @click="print" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Sekarang
                    </button>
                </div>
            </div>

            <!-- Print Preview Area (Simulating A4 Page) -->
            <div class="print-container bg-white text-black p-8 mx-auto shadow-xl rounded-sm max-w-4xl" id="print-area">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Dummy Voucher Item -->
                    <div v-for="n in 12" :key="n" class="border-2 border-dashed border-gray-400 p-3 rounded-lg flex flex-col items-center justify-center text-center relative overflow-hidden bg-gray-50">
                        <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
                        <p class="text-xs font-bold text-gray-500 mb-1 uppercase tracking-widest">Revo Radius</p>
                        <p class="text-lg font-mono font-bold text-gray-800 bg-white border border-gray-300 px-3 py-1 rounded w-full mb-2 shadow-sm">
                            u{{ n }}aB9{{ n }}x
                        </p>
                        <div class="text-xs text-gray-600 space-y-1 w-full bg-white p-2 rounded border border-gray-200">
                            <div class="flex justify-between border-b border-gray-100 pb-1"><span>Paket</span><span class="font-semibold">3 Jam</span></div>
                            <div class="flex justify-between border-b border-gray-100 pb-1"><span>Harga</span><span class="font-semibold text-emerald-600">Rp 10.000</span></div>
                            <div class="flex justify-between"><span>Login</span><span class="text-blue-600">revo.id</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

<style scoped>
@media print {
    body * {
        visibility: hidden;
    }
    #print-area, #print-area * {
        visibility: visible;
    }
    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 20px;
        box-shadow: none;
        border-radius: 0;
    }
    .no-print {
        display: none;
    }
}
</style>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const print = () => {
    window.print();
};
</script>
