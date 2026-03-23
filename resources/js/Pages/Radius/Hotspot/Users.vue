<template>
    <AppLayout title="Pengguna Hotspot">
        <div class="px-6 py-6 max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Pengguna Hotspot</h1>
                    <p class="text-slate-400 text-sm mt-1">Manajemen user hotspot dan voucher.</p>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Generate
                    </button>
                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah
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
                        <select class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2">
                            <option>Semua Profil</option>
                            <option>1 Jam</option>
                            <option>1 Hari</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" placeholder="Cari pengguna..." class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 w-64" />
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-300">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-800/80 border-b border-slate-700">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500 focus:ring-2">
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Nama</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Profil</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Limit Waktu</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Limit Kuota</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Komentar</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id" class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-colors">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 rounded focus:ring-indigo-500 focus:ring-2">
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-white">{{ user.username }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-700 text-slate-300 text-xs font-medium px-2.5 py-1 rounded border border-slate-600">{{ user.profile }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-400">{{ user.limit_uptime || '-' }}</td>
                                <td class="px-6 py-4 text-slate-400">{{ user.limit_bytes || '-' }}</td>
                                <td class="px-6 py-4 text-slate-400">{{ user.comment || '-' }}</td>
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
                <!-- Pagination -->
                <div class="p-4 border-t border-slate-700 bg-slate-800/80 flex items-center justify-between">
                    <span class="text-sm text-slate-400">
                        Menampilkan <span class="font-medium text-white">1</span> sampai <span class="font-medium text-white">4</span> dari <span class="font-medium text-white">4</span> entri
                    </span>
                    <ul class="inline-flex -space-x-px text-sm">
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-slate-400 bg-slate-900 border border-slate-700 rounded-l-lg hover:bg-slate-800 hover:text-white">Sebelumnya</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-white bg-indigo-600 border border-indigo-600 hover:bg-indigo-700 hover:text-white">1</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-slate-400 bg-slate-900 border border-slate-700 rounded-r-lg hover:bg-slate-800 hover:text-white">Selanjutnya</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Dummy data
const users = ref([
    { id: 1, username: 'user001', profile: '3 jam, 1 jam, 10.000', limit_uptime: '1h', limit_bytes: '', comment: 'Voucher - vc-1111' },
    { id: 2, username: 'user002', profile: '3 jam, 1 jam, 10.000', limit_uptime: '1h', limit_bytes: '', comment: 'Voucher - vc-1111' },
    { id: 3, username: 'user003', profile: '3 jam, 1 jam, 10.000', limit_uptime: '1h', limit_bytes: '', comment: 'Voucher - vc-1111' },
    { id: 4, username: 'user004', profile: '3 jam, 1 jam, 10.000', limit_uptime: '1h', limit_bytes: '', comment: 'Voucher - vc-1111' },
]);
</script>
