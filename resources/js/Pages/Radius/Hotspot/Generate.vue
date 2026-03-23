<template>
    <AppLayout title="Generate Voucher Hotspot">
        <div class="px-6 py-6 max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Generate Voucher</h1>
                    <p class="text-slate-400 text-sm mt-1">Buat pengguna/voucher hotspot secara massal.</p>
                </div>
            </div>

            <!-- Form Generate -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-xl shadow-sm overflow-hidden backdrop-blur-sm">
                <div class="p-6 border-b border-slate-700 bg-slate-800/80">
                    <h3 class="text-lg font-medium text-white">Parameter Voucher</h3>
                </div>
                <div class="p-6">
                    <form @submit.prevent="generate" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Jumlah (Qty)</label>
                                    <input type="number" v-model="form.qty" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="10">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Server Hotspot</label>
                                    <select v-model="form.server" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                        <option value="all">Semua Server</option>
                                        <option value="hotspot1">hotspot1</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Mode Username & Password</label>
                                    <select v-model="form.userMode" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                        <option value="up">User & Password Berbeda (UP)</option>
                                        <option value="vc">User & Password Sama (VC)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Panjang Karakter</label>
                                    <input type="number" v-model="form.length" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="8">
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Prefix (Opsional)</label>
                                    <input type="text" v-model="form.prefix" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="vc-">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Karakter Random</label>
                                    <select v-model="form.charType" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                        <option value="num">Hanya Angka (12345)</option>
                                        <option value="lower">Hanya Huruf Kecil (abcd)</option>
                                        <option value="upper">Hanya Huruf Besar (ABCD)</option>
                                        <option value="mix">Campuran (aBcD12)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Profil (Paket Harga)</label>
                                    <select v-model="form.profile" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                        <option value="3h">3 jam, 1 jam, 10.000</option>
                                        <option value="1d">1 Hari, 1 Jam, 15.000</option>
                                        <option value="7d">1 Minggu, 2 Jam, 30.000</option>
                                        <option value="30d">1 Bulan, Tanpa Batas, 100.000</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Batas Waktu (Time Limit) - Opsional</label>
                                    <input type="text" v-model="form.timeLimit" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="e.g. 1h, 1d">
                                </div>

                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-slate-700 flex justify-end space-x-3">
                            <button type="button" class="px-5 py-2.5 text-sm font-medium text-slate-300 bg-transparent border border-slate-600 rounded-lg hover:bg-slate-800 focus:ring-4 focus:ring-slate-800 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-800 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Generate Voucher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

const form = ref({
    qty: 10,
    server: 'all',
    userMode: 'vc',
    length: 8,
    prefix: '',
    charType: 'mix',
    profile: '3h',
    timeLimit: ''
});

const generate = () => {
    // Navigate to the print view or show success modal
    console.log('Generating vouchers...', form.value);
    router.visit('/hotspot/print'); // Dummy navigation
};
</script>
