<template>
    <AppLayout title="Profil Hotspot">
        <div class="px-6 py-6 max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Profil Hotspot</h1>
                    <p class="text-slate-400 text-sm mt-1">Kelola profil user, masa aktif, dan harga.</p>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Profil
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-xl shadow-sm overflow-hidden backdrop-blur-sm">
                <div class="p-4 border-b border-slate-700 flex justify-between items-center bg-slate-800/80">
                    <div class="flex space-x-2">
                        <select class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <select class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2">
                            <option>Semua Server</option>
                            <option>Hotspot1</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" placeholder="Cari profil..." class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 w-64" />
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-300">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-800/80 border-b border-slate-700">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Nama Profil</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Masa Aktif</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Kuota Bandwidth</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Harga</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Pengguna Bersamaan</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="profile in profiles" :key="profile.id" class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 font-medium text-white">{{ profile.name }}</td>
                                <td class="px-6 py-4 text-slate-400">{{ profile.validity }}</td>
                                <td class="px-6 py-4 text-slate-400">
                                    <span class="bg-indigo-900/50 text-indigo-300 text-xs font-medium px-2.5 py-1 rounded border border-indigo-700/50">{{ profile.speed }}</span>
                                </td>
                                <td class="px-6 py-4 font-medium text-emerald-400">{{ profile.price }}</td>
                                <td class="px-6 py-4 text-slate-400">{{ profile.shared_users }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-slate-400 hover:text-white mx-1 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button class="text-rose-400 hover:text-rose-300 mx-1 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Dummy data
const profiles = ref([
    { id: 1, name: '3 jam, 1 jam, 10.000', validity: '3h', speed: '3M/3M', price: 'Rp 10.000', shared_users: '1' },
    { id: 2, name: '1 Hari, 1 Jam, 15.000', validity: '1d', speed: '3M/3M', price: 'Rp 15.000', shared_users: '1' },
    { id: 3, name: '1 Minggu, 2 Jam, 30.000', validity: '7d', speed: '5M/5M', price: 'Rp 30.000', shared_users: '1' },
    { id: 4, name: '1 Bulan, Tanpa Batas, 100.000', validity: '30d', speed: '10M/10M', price: 'Rp 100.000', shared_users: '1' },
]);
</script>
