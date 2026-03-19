<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto p-4 md:p-6 text-slate-300">

      <!-- TOP ROW: 4 Stat Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Card 1: Pendapatan Hari Ini -->
        <div class="bg-[#e8fdf5] rounded-xl p-5 flex items-center justify-between shadow-sm cursor-pointer hover:opacity-90 transition-opacity">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#10b981] flex items-center justify-center shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-[#10b981]">Pendapatan Hari Ini</p>
              <h3 class="text-2xl font-bold text-[#10b981] my-0.5">Rp 0,00</h3>
              <p class="text-xs text-[#10b981] opacity-80">0 transaksi</p>
            </div>
          </div>
          <svg class="w-5 h-5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </div>

        <!-- Card 2: Billing Hari Ini -->
        <div class="bg-[#eff6ff] rounded-xl p-5 flex items-center justify-between shadow-sm cursor-pointer hover:opacity-90 transition-opacity">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#3b82f6] flex items-center justify-center shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-[#3b82f6]">Billing Hari Ini</p>
              <h3 class="text-2xl font-bold text-[#3b82f6] my-0.5">Rp 0,00</h3>
              <p class="text-xs text-[#3b82f6] opacity-80">0 transaksi</p>
            </div>
          </div>
        </div>

        <!-- Card 3: Hotspot Hari Ini -->
        <div class="bg-[#f5f3ff] rounded-xl p-5 flex items-center justify-between shadow-sm cursor-pointer hover:opacity-90 transition-opacity">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#8b5cf6] flex items-center justify-center shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-[#8b5cf6]">Hotspot Hari Ini</p>
              <h3 class="text-2xl font-bold text-[#8b5cf6] my-0.5">Rp 0,00</h3>
              <p class="text-xs text-[#8b5cf6] opacity-80">0 transaksi</p>
            </div>
          </div>
        </div>

        <!-- Card 4: Invoice Bulan Ini -->
        <div class="bg-[#fffbeb] rounded-xl p-5 flex items-center justify-between shadow-sm cursor-pointer hover:opacity-90 transition-opacity">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#f59e0b] flex items-center justify-center shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-[#f59e0b]">Invoice Bulan Ini</p>
              <h3 class="text-2xl font-bold text-[#f59e0b] my-0.5">0</h3>
              <p class="text-xs text-[#f59e0b] opacity-80">0 belum dibayar</p>
            </div>
          </div>
          <svg class="w-5 h-5 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </div>

      </div>

      <!-- MIDDLE ROW: 4 Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Billing Bulanan -->
        <div class="bg-[#1e2434] border border-slate-700/60 rounded-xl p-5 shadow-sm">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h2 class="font-semibold text-white">Billing Bulanan</h2>
          </div>
          <div class="grid grid-cols-3 gap-2 text-center mb-5">
            <div>
              <p class="text-xs text-slate-400 mb-1">Total</p>
              <p class="text-xl font-bold text-white">0</p>
            </div>
            <div>
              <p class="text-xs text-yellow-500 mb-1">belum dibayar</p>
              <p class="text-xl font-bold text-yellow-500">0</p>
            </div>
            <div>
              <p class="text-xs text-red-500 mb-1">Overdue</p>
              <p class="text-xl font-bold text-red-500">0</p>
            </div>
          </div>
          <div class="text-center pt-3 border-t border-slate-700/50">
            <p class="text-xs text-emerald-500 mb-1">Invoice Terbayar</p>
            <p class="text-sm font-semibold text-emerald-500">0 • Rp 0,00</p>
          </div>
        </div>

        <!-- PPPoE -->
        <div class="bg-[#1e2434] border border-slate-700/60 rounded-xl p-5 shadow-sm flex flex-col justify-between cursor-pointer hover:bg-[#232a3d] transition-colors relative group">
          <div>
            <div class="flex items-center gap-3 mb-4">
              <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
              </div>
              <h2 class="font-semibold text-white">PPPoE</h2>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center mb-5">
              <div>
                <p class="text-xs text-emerald-500 mb-1">Online</p>
                <p class="text-xl font-bold text-emerald-500">0</p>
              </div>
              <div>
                <p class="text-xs text-yellow-500 mb-1">Offline</p>
                <p class="text-xl font-bold text-yellow-500">0</p>
              </div>
              <div>
                <p class="text-xs text-red-500 mb-1">Suspend</p>
                <p class="text-xl font-bold text-red-500">0</p>
              </div>
            </div>
          </div>
          <div class="text-center pt-3 border-t border-slate-700/50">
            <p class="text-xs text-slate-400 mb-1">Total Pengguna PPPoE</p>
            <p class="text-2xl font-bold text-white">0</p>
          </div>
          <svg class="w-5 h-5 text-slate-500 absolute bottom-5 right-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </div>

        <!-- Hotspot -->
        <div class="bg-[#1e2434] border border-slate-700/60 rounded-xl p-5 shadow-sm flex flex-col justify-between cursor-pointer hover:bg-[#232a3d] transition-colors relative group">
          <div>
            <div class="flex items-center gap-3 mb-4">
              <div class="w-8 h-8 rounded-lg bg-fuchsia-500/10 flex items-center justify-center text-fuchsia-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
              </div>
              <h2 class="font-semibold text-white">Hotspot</h2>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center mb-5">
              <div>
                <p class="text-xs text-emerald-500 mb-1">Online</p>
                <p class="text-xl font-bold text-emerald-500">116</p>
              </div>
              <div>
                <p class="text-xs text-yellow-500 mb-1">Expired</p>
                <p class="text-xl font-bold text-yellow-500">522</p>
              </div>
              <div>
                <p class="text-xs text-red-500 mb-1">Disabled</p>
                <p class="text-xl font-bold text-red-500">0</p>
              </div>
            </div>
          </div>
          <div class="text-center pt-3 border-t border-slate-700/50">
            <p class="text-xs text-slate-400 mb-1">Total Pengguna Hotspot</p>
            <p class="text-2xl font-bold text-white">1698</p>
          </div>
          <svg class="w-5 h-5 text-slate-500 absolute bottom-5 right-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </div>

        <!-- WhatsApp -->
        <div class="bg-[#1e2434] border border-slate-700/60 rounded-xl p-5 shadow-sm relative">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
              </div>
              <h2 class="font-semibold text-white">WhatsApp</h2>
            </div>
            <span class="px-3 py-1 bg-red-500/10 text-red-400 text-xs font-medium rounded-full border border-red-500/20">Mati</span>
          </div>

          <div class="flex flex-col items-center justify-center h-24 mb-3">
            <p class="text-red-500 font-medium flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
              Gateway Terputus
            </p>
            <p class="text-xs text-slate-400 mt-1">Perlu koneksi ulang</p>
          </div>

          <div class="pt-3 border-t border-slate-700/50 flex justify-between items-center text-xs">
            <span class="text-slate-400">WhatsApp ID</span>
            <span class="text-slate-300">Belum terdaftar</span>
          </div>
        </div>

      </div>

      <!-- BOTTOM ROW: 3 Columns -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1: NAS & Trafik -->
        <div class="space-y-6">

          <!-- Kesehatan NAS -->
          <div class="bg-[#1e2434] border border-slate-700/60 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-400">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                </div>
                <h2 class="font-semibold text-white">Kesehatan NAS</h2>
              </div>
              <select class="bg-[#171c28] border border-slate-700 text-xs text-slate-300 rounded-lg px-3 py-1.5 focus:ring-0 focus:border-blue-500 outline-none">
                <option>ibuanitadupi</option>
              </select>
            </div>

            <div class="space-y-4">
              <div>
                <p class="text-xs text-slate-400 mb-1">Informasi Perangkat</p>
                <p class="text-sm text-slate-200">CPU 0% · ce17 · RouterOS RB750Gr3</p>
              </div>
              <div>
                <p class="text-xs text-slate-400 mb-1">Uptime</p>
                <p class="text-xl font-bold text-white">2h 6m</p>
              </div>
            </div>
          </div>

          <!-- Monitor Trafik -->
          <div class="bg-[#1e2434] border border-slate-700/60 rounded-xl p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-8 h-8 rounded-lg bg-teal-500/10 flex items-center justify-center text-teal-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
              </div>
              <h2 class="font-semibold text-white">Monitor Trafik</h2>
            </div>

            <div class="flex justify-between items-start mb-6">
              <div>
                <p class="text-xs text-emerald-500 flex items-center gap-1 mb-1">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                  Unduh
                </p>
                <p class="text-2xl font-bold text-emerald-500">1.72</p>
                <p class="text-[10px] text-emerald-500 opacity-80">Mbps</p>
              </div>
              <div class="text-right">
                <p class="text-xs text-red-500 flex items-center justify-end gap-1 mb-1">
                  Unggah
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                </p>
                <p class="text-2xl font-bold text-red-500">0.24</p>
                <p class="text-[10px] text-red-500 opacity-80">Mbps</p>
              </div>
            </div>

            <!-- Mock Chart Area -->
            <div class="relative h-32 w-full mt-4">
              <div class="absolute inset-0 border-b border-slate-700/50 flex items-end">
                <!-- Grid Lines -->
                <div class="w-full h-1/2 border-t border-slate-700/30"></div>
              </div>

              <div class="absolute top-0 left-0 right-0 flex justify-between text-[10px] text-slate-500">
                <span>Grafik Real-time</span>
                <span>7.38 Mbps</span>
              </div>

              <!-- SVG Curves for dummy chart -->
              <svg class="absolute inset-0 h-full w-full" preserveAspectRatio="none" viewBox="0 0 100 40">
                <!-- Red Line (Upload) -->
                <path d="M0 38 Q 20 38, 40 38 T 80 35 T 100 32" fill="none" stroke="#ef4444" stroke-width="1" />
                <!-- Green Line (Download) -->
                <path d="M0 35 Q 15 10, 30 35 T 60 38 T 80 38 T 100 32" fill="none" stroke="#10b981" stroke-width="1" />
              </svg>
            </div>

            <div class="flex items-center gap-4 mt-3 text-[10px] text-slate-400">
              <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div> Unduh
              </div>
              <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-red-500"></div> Unggah
              </div>
            </div>
          </div>
        </div>

        <!-- Column 2: Log Audit -->
        <div class="bg-[#1e2434] border border-slate-700/60 rounded-xl p-0 shadow-sm overflow-hidden flex flex-col h-full">
          <div class="p-5 flex items-center justify-between border-b border-slate-700/50">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
              </div>
              <h2 class="font-semibold text-white">Log Audit</h2>
            </div>
            <span class="text-xs text-slate-400">7 entri</span>
          </div>

          <div class="flex-1 overflow-y-auto max-h-[500px] p-2">
            <!-- Log Item 1 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-xs font-bold text-white">LOGIN</span>
                <span class="text-[10px] text-slate-400">19 Mar 2026, 15.21</span>
              </div>
              <p class="text-xs text-slate-300">teaardian &rarr; teaardian</p>
            </div>
            <!-- Log Item 2 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-xs font-bold text-white">LOGIN</span>
                <span class="text-[10px] text-slate-400">19 Mar 2026, 07.05</span>
              </div>
              <p class="text-xs text-slate-300">teaardian &rarr; teaardian</p>
            </div>
            <!-- Log Item 3 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-xs font-bold text-white">HOTSPOT_USER_BATCH_DELETED</span>
                <span class="text-[10px] text-slate-400">15 Mar 2026, 20.51</span>
              </div>
              <p class="text-xs text-slate-300">teaardian &rarr; Batch Delete</p>
            </div>
            <!-- Log Item 4 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-xs font-bold text-white">HOTSPOT_USER_GENERATED</span>
                <span class="text-[10px] text-slate-400">15 Mar 2026, 20.42</span>
              </div>
              <p class="text-xs text-slate-300">teaardian &rarr; Generated 1000 users</p>
            </div>
            <!-- Log Item 5 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-xs font-bold text-white">HOTSPOT_USER_GENERATED</span>
                <span class="text-[10px] text-slate-400">15 Mar 2026, 20.39</span>
              </div>
              <p class="text-xs text-slate-300">teaardian &rarr; Generated 100 users</p>
            </div>
            <!-- Log Item 6 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-xs font-bold text-white">HOTSPOT_USER_GENERATED</span>
                <span class="text-[10px] text-slate-400">15 Mar 2026, 20.38</span>
              </div>
              <p class="text-xs text-slate-300">teaardian &rarr; Generated 100 users</p>
            </div>
            <!-- Log Item 7 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-xs font-bold text-white">HOTSPOT_USER_GENERATED</span>
                <span class="text-[10px] text-slate-400">15 Mar 2026, 20.37</span>
              </div>
              <p class="text-xs text-slate-300">teaardian &rarr; Generated 100 users</p>
            </div>
          </div>
        </div>

        <!-- Column 3: Transaksi Terbaru -->
        <div class="bg-[#1e2434] border border-slate-700/60 rounded-xl p-0 shadow-sm overflow-hidden flex flex-col h-full">
          <div class="p-5 flex items-center justify-between border-b border-slate-700/50">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
              </div>
              <h2 class="font-semibold text-white">Transaksi Terbaru</h2>
            </div>
            <span class="text-xs text-slate-400">7 item</span>
          </div>

          <div class="flex-1 overflow-y-auto max-h-[500px] p-2">
            <!-- Transaksi Item 1 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-bold text-white">Beli 1000 voucher (6jam-5rb)</span>
                <span class="text-sm font-bold text-emerald-500">Rp 4.000.000,00</span>
              </div>
              <div class="flex justify-between items-center mt-1">
                <span class="text-[11px] text-slate-400">teaardian • voucher_sale</span>
                <span class="text-[10px] text-slate-500">15 Mar 2026, 20.42</span>
              </div>
            </div>
            <!-- Transaksi Item 2 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-bold text-white">Beli 100 voucher (6jam-5rb)</span>
                <span class="text-sm font-bold text-emerald-500">Rp 400.000,00</span>
              </div>
              <div class="flex justify-between items-center mt-1">
                <span class="text-[11px] text-slate-400">teaardian • voucher_sale</span>
                <span class="text-[10px] text-slate-500">15 Mar 2026, 20.39</span>
              </div>
            </div>
            <!-- Transaksi Item 3 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-bold text-white">Beli 100 voucher (6jam-5rb)</span>
                <span class="text-sm font-bold text-emerald-500">Rp 400.000,00</span>
              </div>
              <div class="flex justify-between items-center mt-1">
                <span class="text-[11px] text-slate-400">teaardian • voucher_sale</span>
                <span class="text-[10px] text-slate-500">15 Mar 2026, 20.38</span>
              </div>
            </div>
            <!-- Transaksi Item 4 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-bold text-white">Beli 100 voucher (6jam-5rb)</span>
                <span class="text-sm font-bold text-emerald-500">Rp 400.000,00</span>
              </div>
              <div class="flex justify-between items-center mt-1">
                <span class="text-[11px] text-slate-400">teaardian • voucher_sale</span>
                <span class="text-[10px] text-slate-500">15 Mar 2026, 20.37</span>
              </div>
            </div>
            <!-- Transaksi Item 5 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-bold text-white">Beli 100 voucher (6jam-5rb)</span>
                <span class="text-sm font-bold text-emerald-500">Rp 400.000,00</span>
              </div>
              <div class="flex justify-between items-center mt-1">
                <span class="text-[11px] text-slate-400">teaardian • voucher_sale</span>
                <span class="text-[10px] text-slate-500">15 Mar 2026, 20.37</span>
              </div>
            </div>
            <!-- Transaksi Item 6 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-bold text-white">Beli 100 voucher (6jam-5rb)</span>
                <span class="text-sm font-bold text-emerald-500">Rp 400.000,00</span>
              </div>
              <div class="flex justify-between items-center mt-1">
                <span class="text-[11px] text-slate-400">teaardian • voucher_sale</span>
                <span class="text-[10px] text-slate-500">15 Mar 2026, 20.35</span>
              </div>
            </div>
            <!-- Transaksi Item 7 -->
            <div class="p-3 hover:bg-[#232a3d] rounded-lg transition-colors border-b border-slate-700/30 last:border-0">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-bold text-white">Beli 100 voucher (6jam-5rb)</span>
                <span class="text-sm font-bold text-emerald-500">Rp 400.000,00</span>
              </div>
              <div class="flex justify-between items-center mt-1">
                <span class="text-[11px] text-slate-400">teaardian • voucher_sale</span>
                <span class="text-[10px] text-slate-500">15 Mar 2026, 20.30</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

// Data ditiadakan karena fokus HANYA pada frontend layout & dummy data sesuai gambar.
</script>
