<template>
  <div class="min-h-screen bg-[#0f172a] flex font-sans text-gray-100">
    <div class="flex-1 flex flex-col justify-center px-8 sm:px-16 lg:px-24 xl:px-32">
      <!-- Left side text content -->
      <div class="max-w-2xl">
        <div class="inline-block border border-gray-600 rounded-full px-4 py-1 text-sm font-medium text-gray-300 mb-6">
          Revo Radius Network Console
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-tight mb-6">
          Operasi, billing, dan kontrol jaringan dalam satu command center.
        </h1>
        <p class="text-lg text-gray-400 mb-8 max-w-xl leading-relaxed">
          Pantau subscriber, kelola profil PPPoE, dan jaga billing tetap terkontrol. Tampilan modern ini dibangun untuk kecepatan dan kejelasan.
        </p>

        <div class="flex space-x-6 text-sm text-gray-300">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-emerald-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Sesi aman
          </div>
          <div class="flex items-center">
            <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Insight real-time
          </div>
        </div>
      </div>
    </div>

    <!-- Right side login form -->
    <div class="flex-1 flex items-center justify-center p-8">
      <div class="w-full max-w-md bg-[#1e293b] border border-gray-700 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-2xl font-bold text-white mb-2">Masuk</h2>
        <p class="text-gray-400 text-sm mb-8">Gunakan kredensial operator untuk melanjutkan.</p>

        <form class="space-y-6" @submit.prevent="submit">
          <!-- Error message if any -->
          <div v-if="form.errors.email" class="bg-red-900/50 border border-red-500 text-red-200 px-4 py-2 rounded">
            {{ form.errors.email }}
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="block w-full px-4 py-3 bg-[#0f172a] border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
              placeholder="admin@revoradius.com"
            >
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              class="block w-full px-4 py-3 bg-[#0f172a] border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
              placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
            >
          </div>

          <div class="pt-2">
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#1e293b] focus:ring-blue-500 disabled:opacity-50 transition-colors"
            >
              Masuk
            </button>
          </div>

          <div class="text-center mt-6">
            <p class="text-xs text-gray-500">
              Butuh akses? Hubungi administrator jaringan Anda.
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
})

const submit = () => {
  form.post('/login')
}
</script>
