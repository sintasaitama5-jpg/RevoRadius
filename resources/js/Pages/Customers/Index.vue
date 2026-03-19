<template>
  <AppLayout>
    <div class="mb-6 flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-800">Manajemen Pelanggan PPPoE/Bulanan</h2>
      <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
        + Tambah Pelanggan
      </button>
    </div>

    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
      <p>{{ $page.props.flash.success }}</p>
    </div>

    <!-- Tabel Pelanggan -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kredensial</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket (Rp)</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="customer in customers" :key="customer.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">{{ customer.name }}</div>
              <div class="text-xs text-gray-500">{{ customer.router ? customer.router.name : 'Radius Server' }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900 font-mono">{{ customer.username }}</div>
              <div class="text-xs text-gray-400">Secret / Password</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ customer.profile_name }}</div>
              <div class="text-sm font-bold text-green-600">Rp {{ customer.monthly_price }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Tgl {{ customer.billing_cycle_date }} per bulan</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span v-if="customer.status === 'ACTIVE'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                AKTIF
              </span>
              <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                TERISOLIR (SUSPEND)
              </span>
            </td>
          </tr>
          <tr v-if="customers.length === 0">
            <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">Belum ada pelanggan bulanan.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Form Tambah Pelanggan -->
    <div v-if="showModal" class="fixed inset-0 overflow-y-auto z-10" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pendaftaran Berlangganan (PPPoE)</h3>

          <form @submit.prevent="submitForm">
            <div class="grid grid-cols-2 gap-4">
              <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Nama Pelanggan / Institusi</label>
                <input v-model="form.name" type="text" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">PPPoE Username</label>
                <input v-model="form.username" type="text" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">PPPoE Password</label>
                <input v-model="form.password" type="text" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Nama Profil (Contoh: 10M)</label>
                <input v-model="form.profile_name" type="text" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Harga Per Bulan (Rp)</label>
                <input v-model="form.monthly_price" type="number" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Tgl Penagihan / Jatuh Tempo</label>
                <input v-model="form.billing_cycle_date" type="date" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Router Tujuan</label>
                <select v-model="form.router_id" class="mt-1 border-gray-300 w-full rounded-md shadow-sm sm:text-sm">
                  <option :value="null">-- FreeRADIUS Sentral --</option>
                  <option v-for="rt in routers" :key="rt.id" :value="rt.id">{{ rt.name }}</option>
                </select>
              </div>
            </div>
            <div class="mt-5 sm:flex sm:flex-row-reverse">
              <button type="submit" :disabled="form.processing" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                Simpan & Buat Tagihan
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
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({
  customers: Array,
  routers: Array,
})

const showModal = ref(false)

const form = useForm({
  name: '',
  username: '',
  password: '',
  profile_name: '',
  monthly_price: 150000,
  billing_cycle_date: '',
  router_id: null,
})

const submitForm = () => {
  form.post('/customers', {
    onSuccess: () => {
      showModal.value = false
      form.reset()
    }
  })
}
</script>
