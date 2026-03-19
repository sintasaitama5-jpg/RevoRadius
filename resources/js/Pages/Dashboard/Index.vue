<template>
  <AppLayout>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="p-6 bg-white rounded-lg shadow border border-gray-200">
        <h2 class="text-gray-500 font-medium">Total Routers</h2>
        <p class="text-3xl font-bold mt-2">{{ stats.routers }}</p>
      </div>
      <div class="p-6 bg-white rounded-lg shadow border border-gray-200">
        <h2 class="text-gray-500 font-medium">Active Vouchers</h2>
        <p class="text-3xl font-bold mt-2 text-green-600">{{ stats.active_vouchers }}</p>
      </div>
      <div class="p-6 bg-white rounded-lg shadow border border-gray-200">
        <h2 class="text-gray-500 font-medium">PPPoE Members</h2>
        <p class="text-3xl font-bold mt-2 text-blue-600">{{ stats.pppoe_members }}</p>
      </div>
      <div class="p-6 bg-white rounded-lg shadow border border-gray-200">
        <h2 class="text-gray-500 font-medium">Unpaid Invoices</h2>
        <p class="text-3xl font-bold mt-2 text-red-600">{{ stats.unpaid_invoices }}</p>
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
