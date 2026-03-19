<template>
  <AppLayout>
    <div class="mb-6 flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-800">Manajemen Tagihan (Billing)</h2>
    </div>

    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
      <p>{{ $page.props.flash.success }}</p>
    </div>

    <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
      <p>{{ $page.props.flash.error }}</p>
    </div>

    <!-- Tabel Daftar Tagihan (Invoice) -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Tagihan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Tagihan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="invoice in invoices" :key="invoice.id">
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ invoice.invoice_number }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ invoice.customer.name }} <br><span class="text-xs">@{{ invoice.customer.username }}</span></td>
            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">Rp {{ invoice.total_amount }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ invoice.due_date }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span v-if="invoice.status === 'PAID'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                LUNAS ({{ invoice.paid_date }})
              </span>
              <span v-else-if="invoice.status === 'OVERDUE'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                MENUNGGAK
              </span>
              <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                BELUM BAYAR
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
              <button
                v-if="invoice.status !== 'PAID'"
                @click="payInvoice(invoice.id)"
                class="text-green-600 hover:text-green-900 font-bold">
                Tandai Bayar (Kasir)
              </button>
            </td>
          </tr>
          <tr v-if="invoices.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada tagihan terbit.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'

defineProps({
  invoices: Array,
})

const payInvoice = (id) => {
  if (confirm('Anda yakin menerima uang pembayaran untuk Tagihan ini secara tunai/transfer manual? Layanan akan diaktifkan kembali jika sempat terisolir.')) {
    router.post(`/billing/invoices/${id}/pay`)
  }
}
</script>
