<template>
  <AppLayout>
    <div class="mb-6 flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-800">Manajemen Router</h2>
      <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
        + Tambah Router
      </button>
    </div>

    <!-- Alert Success -->
    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
      <p>{{ $page.props.flash.success }}</p>
    </div>

    <!-- Alert Error (Connection Test Failed) -->
    <div v-if="$page.props.errors && $page.props.errors.connection" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
      <p>{{ $page.props.errors.connection }}</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Router</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP / Host</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Versi OS</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="router in routers" :key="router.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">{{ router.name }}</div>
              <div class="text-sm text-gray-500">{{ router.username }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ router.ip_address }}:{{ router.api_port }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                {{ router.os_version }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span v-if="router.status === 'online'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Online</span>
              <span v-else-if="router.status === 'error'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Error</span>
              <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Offline</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
              <button @click="testConnection(router.id)" class="text-blue-600 hover:text-blue-900" :disabled="testing === router.id">
                <span v-if="testing === router.id">Menguji...</span>
                <span v-else>Test Ping</span>
              </button>
              <button @click="deleteRouter(router.id)" class="text-red-600 hover:text-red-900 ml-3">Hapus</button>
            </td>
          </tr>
          <tr v-if="routers.length === 0">
            <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">Belum ada router Mikrotik.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Form Tambah Router -->
    <div v-if="showModal" class="fixed inset-0 overflow-y-auto z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <form @submit.prevent="submitForm">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                Tambahkan Mikrotik Baru
              </h3>

              <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700">Nama Router (Site)</label>
                  <input v-model="form.name" type="text" class="mt-1 border-gray-300 w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">IP Public / Domain</label>
                  <input v-model="form.ip_address" type="text" class="mt-1 border-gray-300 w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required placeholder="103.11.22.33">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">API Port</label>
                  <input v-model="form.api_port" type="number" class="mt-1 border-gray-300 w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                </div>
                <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700">Versi RouterOS</label>
                  <select v-model="form.os_version" class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="v7">RouterOS v7 (Lebih Aman, Support Native WG)</option>
                    <option value="v6">RouterOS v6 (Legacy)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">API Username</label>
                  <input v-model="form.username" type="text" class="mt-1 border-gray-300 w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">API Password</label>
                  <input v-model="form.password" type="password" class="mt-1 border-gray-300 w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button type="submit" :disabled="form.processing" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                Simpan Router
              </button>
              <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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
  routers: Array,
  flash: Object,
  errors: Object
})

const showModal = ref(false)
const testing = ref(null)

const form = useForm({
  name: '',
  ip_address: '',
  api_port: 8728,
  os_version: 'v7',
  username: '',
  password: '',
})

const submitForm = () => {
  form.post('/routers', {
    onSuccess: () => {
      showModal.value = false
      form.reset()
    }
  })
}

const deleteRouter = (id) => {
  if (confirm('Yakin ingin menghapus router ini? Seluruh data yang terhubung mungkin terdampak.')) {
    inertiaRouter.delete(`/routers/${id}`)
  }
}

const testConnection = (id) => {
  testing.value = id
  inertiaRouter.post(`/routers/${id}/test`, {}, {
    onFinish: () => {
      testing.value = null
    }
  })
}
</script>
