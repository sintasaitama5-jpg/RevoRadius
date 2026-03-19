<template>
  <div class="flex h-screen bg-[#0f172a] text-gray-300 font-sans">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#1e293b] border-r border-gray-700 flex flex-col hidden md:flex">
      <div class="p-6 flex items-center justify-center border-b border-gray-700">
        <span class="text-white text-lg font-bold tracking-wider">REVO RADIUS</span>
      </div>

      <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <Link href="/dashboard" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/dashboard') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
          Dashboard
        </Link>
        <Link href="/routers" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/routers') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
          Routers
        </Link>
        <Link href="/vouchers" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/vouchers') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
          Vouchers
        </Link>
        <Link href="/customers" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/customers') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          Members (PPPoE)
        </Link>
        <Link href="/billing" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/billing') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          Billing & Invoices
        </Link>
      </nav>

      <div class="p-4 border-t border-gray-700">
        <form @submit.prevent="logout">
          <button type="submit" class="flex w-full items-center px-4 py-2 text-sm font-medium text-red-400 hover:bg-gray-700 hover:text-red-300 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Logout
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#0f172a] overflow-hidden">
      <!-- Top header for mobile -->
      <header class="md:hidden bg-[#1e293b] border-b border-gray-700 p-4 flex justify-between items-center">
        <span class="text-white font-bold">REVO RADIUS</span>
        <form @submit.prevent="logout">
          <button type="submit" class="text-sm text-red-400 hover:text-red-300">Logout</button>
        </form>
      </header>

      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const logout = () => {
  router.post('/logout')
}

const page = usePage()
const isUrl = (path) => {
  return page.url.startsWith(path)
}
</script>
