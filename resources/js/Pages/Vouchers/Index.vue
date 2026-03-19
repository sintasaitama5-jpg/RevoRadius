<template>
  <AppLayout>
    <div class="mb-6 flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-800">Manajemen Voucher Hotspot</h2>
      <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
        + Generate Batch Baru
      </button>
    </div>

    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
      <p>{{ $page.props.flash.success }}</p>
    </div>

    <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
      <p>{{ $page.props.flash.error }}</p>
    </div>

    <!-- Tabel Daftar Batch Voucher -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batch ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket & Harga</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Router</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Pembayaran</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="batch in batches" :key="batch.id">
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ batch.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ batch.profile_name }} <br> <span class="text-green-600 font-bold">Rp {{ batch.price }}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ batch.quantity }} lembar</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ batch.router ? batch.router.name : 'Global (Radius)' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span v-if="batch.status === 'PAID'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Terbayar / Lunas
              </span>
              <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                Belum Dibayar (UNPAID)
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
              <button class="text-blue-600 hover:text-blue-900">Lihat/Cetak PDF</button>
              <button
                v-if="batch.status === 'UNPAID'"
                @click="activateBatch(batch.id)"
                class="text-green-600 hover:text-green-900 font-bold">
                Jual (Tandai Lunas)
              </button>
            </td>
          </tr>
          <tr v-if="batches.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada voucher yang digenerate.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Generate Voucher -->
    <div v-if="showModal" class="fixed inset-0 overflow-y-auto z-10" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Generate Voucher Massal</h3>

          <form @submit.prevent="generateVoucher">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Nama Profil (Contoh: "2 Jam / 5MB")</label>
                <input v-model="form.profile_name" type="text" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm" required>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Harga per Lembar</label>
                  <input v-model="form.price" type="number" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm" required min="0">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Jumlah Cetak</label>
                  <input v-model="form.quantity" type="number" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm" required min="1" max="1000">
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Terapkan Pada Router Spesifik (Opsional)</label>
                <select v-model="form.router_id" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm">
                  <option :value="null">-- Global FreeRADIUS --</option>
                  <option v-for="rt in routers" :key="rt.id" :value="rt.id">{{ rt.name }} - {{ rt.ip_address }}</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Status voucher baru selalu "UNPAID" sampai dicetak dan disetor uangnya ke kasir/aplikasi.</p>
              </div>
            </div>

            <div class="mt-5 sm:flex sm:flex-row-reverse">
              <button type="submit" :disabled="form.processing" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                Generate Sekarang
              </button>
              <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Batal
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router as inertiaRouter } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  batches: Array,
  routers: Array,
})

const showModal = ref(false)

const form = useForm({
  profile_name: '',
  price: 5000,
  quantity: 50,
  router_id: null,
})

const generateVoucher = () => {
  form.post('/vouchers/generate', {
    onSuccess: () => {
      showModal.value = false
      form.reset()
    }
  })
}

const activateBatch = (id) => {
  if (confirm('Voucher akan ditandai LUNAS dan Nominal Penjualan diakui. Voucher otomatis sinkron ke Mikrotik/Radius. Lanjutkan?')) {
    inertiaRouter.post(`/vouchers/batches/${id}/activate`)
  }
}
</script>
