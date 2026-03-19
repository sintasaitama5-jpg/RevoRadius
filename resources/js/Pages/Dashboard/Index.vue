<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-white tracking-tight">System Overview</h1>
        <p class="mt-2 text-sm text-gray-400">Pantau status jaringan, pengguna, dan performa billing real-time.</p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Routers -->
        <div class="bg-[#1e293b] rounded-xl p-6 border border-gray-700 shadow-lg relative overflow-hidden group hover:border-blue-500 transition-colors">
          <div class="absolute right-0 top-0 mt-6 mr-6 p-3 bg-blue-500/10 rounded-lg group-hover:bg-blue-500/20 transition-colors">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
          </div>
          <h2 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Total Routers</h2>
          <div class="flex items-baseline">
            <p class="text-4xl font-extrabold text-white">{{ stats.routers }}</p>
            <span class="ml-2 text-sm text-gray-500 font-medium">Nodes</span>
          </div>
        </div>

        <!-- Card 2: Vouchers -->
        <div class="bg-[#1e293b] rounded-xl p-6 border border-gray-700 shadow-lg relative overflow-hidden group hover:border-emerald-500 transition-colors">
          <div class="absolute right-0 top-0 mt-6 mr-6 p-3 bg-emerald-500/10 rounded-lg group-hover:bg-emerald-500/20 transition-colors">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
          </div>
          <h2 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Active Vouchers</h2>
          <div class="flex items-baseline">
            <p class="text-4xl font-extrabold text-emerald-400">{{ stats.active_vouchers }}</p>
            <span class="ml-2 text-sm text-gray-500 font-medium">Users</span>
          </div>
        </div>

        <!-- Card 3: PPPoE -->
        <div class="bg-[#1e293b] rounded-xl p-6 border border-gray-700 shadow-lg relative overflow-hidden group hover:border-purple-500 transition-colors">
          <div class="absolute right-0 top-0 mt-6 mr-6 p-3 bg-purple-500/10 rounded-lg group-hover:bg-purple-500/20 transition-colors">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          </div>
          <h2 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">PPPoE Members</h2>
          <div class="flex items-baseline">
            <p class="text-4xl font-extrabold text-purple-400">{{ stats.pppoe_members }}</p>
            <span class="ml-2 text-sm text-gray-500 font-medium">Subs</span>
          </div>
        </div>

        <!-- Card 4: Invoices -->
        <div class="bg-[#1e293b] rounded-xl p-6 border border-gray-700 shadow-lg relative overflow-hidden group hover:border-red-500 transition-colors">
          <div class="absolute right-0 top-0 mt-6 mr-6 p-3 bg-red-500/10 rounded-lg group-hover:bg-red-500/20 transition-colors">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <h2 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Unpaid Invoices</h2>
          <div class="flex items-baseline">
            <p class="text-4xl font-extrabold text-red-400">{{ stats.unpaid_invoices }}</p>
            <span class="ml-2 text-sm text-gray-500 font-medium">Pending</span>
          </div>
        </div>
      </div>

      <!-- Quick Actions (Mock for visual completeness matching command center feel) -->
      <div class="bg-[#1e293b] rounded-xl border border-gray-700 shadow-lg p-6">
        <h3 class="text-lg font-bold text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <button class="flex items-center justify-center p-3 text-sm font-medium text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 rounded-lg border border-blue-500/20 transition-colors">
            + Tambah Router
          </button>
          <button class="flex items-center justify-center p-3 text-sm font-medium text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 rounded-lg border border-emerald-500/20 transition-colors">
            + Generate Voucher
          </button>
          <button class="flex items-center justify-center p-3 text-sm font-medium text-purple-400 bg-purple-500/10 hover:bg-purple-500/20 rounded-lg border border-purple-500/20 transition-colors">
            + Register PPPoE
          </button>
          <button class="flex items-center justify-center p-3 text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 rounded-lg border border-gray-600 transition-colors">
            System Log
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  stats: Object,
})
</script>
