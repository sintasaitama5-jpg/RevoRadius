<template>
    <AppLayout title="Template Hotspot">
        <div class="px-6 py-6 max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Template Hotspot</h1>
                    <p class="text-slate-400 text-sm mt-1">Rancang template voucher dan lihat hasil preview.</p>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Template
                    </button>
                </div>
            </div>

            <!-- Editor -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

                <!-- Left: Form -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl shadow-sm overflow-hidden backdrop-blur-sm">
                    <div class="p-6 border-b border-slate-700 bg-slate-800/80 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-white">Template Editor</h3>
                        <span class="bg-emerald-900/50 text-emerald-400 text-xs font-medium px-2.5 py-1 rounded border border-emerald-700/50">Default</span>
                    </div>
                    <div class="p-6 space-y-4">

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Pilih Template</label>
                            <select v-model="form.template" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                <option value="simple">simple</option>
                                <option value="modern">modern</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nama Template</label>
                            <input type="text" v-model="form.name" class="bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2 flex justify-between items-center">
                                Header HTML
                                <button class="text-xs text-indigo-400 hover:text-indigo-300">Salin Header</button>
                            </label>
                            <textarea v-model="form.header" rows="4" class="bg-slate-900 border border-slate-700 text-slate-300 text-sm font-mono rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 resize-y"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2 flex justify-between items-center">
                                Row HTML (Voucher Item)
                                <button class="text-xs text-indigo-400 hover:text-indigo-300">Salin Row</button>
                            </label>
                            <textarea v-model="form.row" rows="6" class="bg-slate-900 border border-slate-700 text-slate-300 text-sm font-mono rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 resize-y"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2 flex justify-between items-center">
                                Footer HTML
                                <button class="text-xs text-indigo-400 hover:text-indigo-300">Salin Footer</button>
                            </label>
                            <textarea v-model="form.footer" rows="2" class="bg-slate-900 border border-slate-700 text-slate-300 text-sm font-mono rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 resize-y"></textarea>
                        </div>

                    </div>
                </div>

                <!-- Right: Preview & Guide -->
                <div class="space-y-6">
                    <!-- Preview -->
                    <div class="bg-slate-800/50 border border-slate-700 rounded-xl shadow-sm overflow-hidden backdrop-blur-sm">
                        <div class="p-6 border-b border-slate-700 bg-slate-800/80 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-medium text-white">Preview</h3>
                                <p class="text-xs text-slate-400">Preview voucher dengan placeholder.</p>
                            </div>
                            <div class="flex items-center space-x-2 text-sm">
                                <label class="text-slate-400">Jumlah preview</label>
                                <input type="number" v-model="previewCount" class="bg-slate-900 border border-slate-700 text-white rounded w-16 p-1 text-center" min="1" max="10">
                            </div>
                        </div>
                        <div class="p-6 bg-slate-900 min-h-[300px] flex items-center justify-center">
                            <!-- Visual Preview (Simulated based on form values) -->
                            <div class="bg-white rounded p-4 text-black shadow-inner overflow-auto max-h-[400px] w-full">
                                <div class="flex flex-wrap gap-4 justify-center">
                                    <div v-for="n in previewCount" :key="n" class="border border-black p-2 bg-white w-48 text-center text-sm font-sans shadow-sm">
                                        <div class="font-bold border-b border-black pb-1 mb-1 text-xs">Kode Voucher [00{{n}}]</div>
                                        <div class="font-bold text-lg border border-black p-1 my-2 bg-gray-50">user00{{n}}</div>
                                        <div class="text-[10px] text-gray-700">3 jam, 1 jam, 10.000</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Placeholders Guide -->
                    <div class="bg-slate-800/50 border border-slate-700 rounded-xl shadow-sm overflow-hidden backdrop-blur-sm">
                        <div class="p-4 border-b border-slate-700 bg-slate-800/80">
                            <h3 class="text-sm font-medium text-white">Placeholder Tersedia</h3>
                        </div>
                        <div class="p-4 text-sm text-slate-300 font-mono space-y-2">
                            <p><span class="text-indigo-400">%no_urut%</span> — nomor urut (001, 002, ...)</p>
                            <p><span class="text-indigo-400">%username%</span> — username member</p>
                            <p><span class="text-indigo-400">%password%</span> — password</p>
                            <p><span class="text-indigo-400">%profile%</span> — nama profil</p>
                            <p><span class="text-indigo-400">%price%</span> — harga profil</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const previewCount = ref(4);

const form = ref({
    template: 'simple',
    name: 'simple',
    header: `<!-- HEADER HTML -->\n<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">\n<html>\n<head>\n  <title>Voucher Hotspot</title>\n</head>\n<body>`,
    row: `<!-- ROW HTML -->\n<div class="voucher">\n  <div class="v-header">Kode Voucher <span class="v-no">[%no_urut%]</span></div>\n  <div class="v-body">\n    <div class="v-user">%username%</div>\n  </div>\n  <div class="v-info">%profile%</div>\n</div>`,
    footer: `<!-- FOOTER HTML -->\n</body>\n</html>`
});
</script>
