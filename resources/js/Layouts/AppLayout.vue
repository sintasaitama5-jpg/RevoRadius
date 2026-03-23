<template>
  <div class="flex h-screen bg-[#0f172a] text-gray-300 font-sans">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#111827] border-r border-gray-800 flex flex-col hidden md:flex">
      <div class="p-6 flex items-center justify-center border-b border-gray-800">
        <span class="text-white text-lg font-bold tracking-wider">REVO RADIUS</span>
      </div>

      <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <Link href="/dashboard" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/dashboard') ? 'bg-[#1e40af] text-[#60a5fa]' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
          Dashboard
        </Link>
        <Link href="/routers" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/routers') ? 'bg-[#1e40af] text-[#60a5fa]' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
          Routers
        </Link>

        <!-- Menu Section: RADIUS -->
        <div class="pt-6 pb-2">
          <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
            RADIUS
          </p>
        </div>

        <!-- PPPoE Dropdown -->
        <div>
          <button @click="toggleMenu('pppoe')" :class="['w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/customers') || isUrl('/pppoe') || isUrl('/billing') ? 'text-white' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
              </svg>
              PPPoE
            </div>
            <svg :class="['w-4 h-4 transition-transform duration-200', openMenus.pppoe ? 'rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
          </button>
          <div v-show="openMenus.pppoe" class="pl-11 pr-4 py-2 space-y-1">
            <div class="relative before:absolute before:inset-y-0 before:left-[-1.25rem] before:w-px before:bg-gray-700">
              <Link href="/customers" :class="['relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors', isUrl('/customers') ? 'text-white bg-[#1e293b]' : 'text-gray-400 hover:text-white hover:bg-[#1e293b]']">
                <span class="absolute left-[-1.5rem] w-2 h-2 rounded-full bg-gray-500 border-2 border-[#111827]"></span>
                Pengguna
              </Link>
              <Link href="/billing" :class="['relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors', isUrl('/billing') ? 'text-white bg-[#1e293b]' : 'text-gray-400 hover:text-white hover:bg-[#1e293b]']">
                <span class="absolute left-[-1.5rem] w-2 h-2 rounded-full bg-gray-500 border-2 border-[#111827]"></span>
                Billing & Invoices
              </Link>
              <Link href="#" class="relative flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white hover:bg-[#1e293b] rounded-lg transition-colors">
                <span class="absolute left-[-1.5rem] w-2 h-2 rounded-full bg-gray-500 border-2 border-[#111827]"></span>
                Profil
              </Link>
            </div>
          </div>
        </div>

        <!-- Hotspot Dropdown -->
        <div>
          <button @click="toggleMenu('hotspot')" :class="['w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/vouchers') || isUrl('/hotspot') ? 'text-white' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
              </svg>
              Hotspot
            </div>
            <svg :class="['w-4 h-4 transition-transform duration-200', openMenus.hotspot ? 'rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
          </button>
          <div v-show="openMenus.hotspot" class="pl-11 pr-4 py-2 space-y-1">
            <div class="relative before:absolute before:inset-y-0 before:left-[-1.25rem] before:w-px before:bg-gray-700">
              <Link :href="route('hotspot.users')" :class="['relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors', isUrl('/hotspot/users') ? 'text-white bg-[#1e293b]' : 'text-gray-400 hover:text-white hover:bg-[#1e293b]']">
                <span class="absolute left-[-1.5rem] w-2 h-2 rounded-full bg-gray-500 border-2 border-[#111827]"></span>
                Pengguna
              </Link>
              <Link :href="route('hotspot.profiles')" :class="['relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors', isUrl('/hotspot/profiles') ? 'text-white bg-[#1e293b]' : 'text-gray-400 hover:text-white hover:bg-[#1e293b]']">
                <span class="absolute left-[-1.5rem] w-2 h-2 rounded-full bg-gray-500 border-2 border-[#111827]"></span>
                Profil
              </Link>
              <Link :href="route('hotspot.generate')" :class="['relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors', isUrl('/hotspot/generate') ? 'text-white bg-[#1e293b]' : 'text-gray-400 hover:text-white hover:bg-[#1e293b]']">
                <span class="absolute left-[-1.5rem] w-2 h-2 rounded-full bg-gray-500 border-2 border-[#111827]"></span>
                Generate
              </Link>
              <Link :href="route('hotspot.templates')" :class="['relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors', isUrl('/hotspot/templates') ? 'text-white bg-[#1e293b]' : 'text-gray-400 hover:text-white hover:bg-[#1e293b]']">
                <span class="absolute left-[-1.5rem] w-2 h-2 rounded-full bg-gray-500 border-2 border-[#111827]"></span>
                Template
              </Link>
            </div>
          </div>
        </div>

        <!-- NAS Single Item -->
        <Link href="#" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/nas') ? 'bg-[#1e40af] text-[#60a5fa]' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          NAS
        </Link>

        <!-- Sesi Single Item -->
        <Link href="#" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/sessions') ? 'bg-[#1e40af] text-[#60a5fa]' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          Sesi
        </Link>

        <!-- Menu Section: ADMIN -->
        <div class="pt-6 pb-2">
          <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
            ADMIN
          </p>
        </div>

        <Link href="#" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/admin/users') ? 'bg-[#1e40af] text-[#60a5fa]' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
          </svg>
          Pengguna
        </Link>
        <Link href="#" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/admin/logs') ? 'bg-[#1e293b] text-white' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Log
        </Link>
        <Link href="#" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/admin/settings') ? 'bg-[#1e293b] text-white' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
          </svg>
          Pengaturan
        </Link>
        <Link href="#" :class="['flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/admin/data') ? 'bg-[#1e293b] text-white' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
          </svg>
          Manajemen Data
        </Link>
        <div>
          <button @click="toggleMenu('partner')" :class="['w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors', isUrl('/partner') ? 'text-white' : 'text-gray-400 hover:bg-[#1e293b] hover:text-white']">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
              Partner
            </div>
            <svg :class="['w-4 h-4 transition-transform duration-200', openMenus.partner ? 'rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
          </button>
          <div v-show="openMenus.partner" class="pl-11 pr-4 py-2 space-y-1">
             <Link href="#" class="block px-3 py-2 text-sm text-gray-500 hover:text-gray-300 hover:bg-[#1e293b] rounded-lg transition-colors">
              List Partner
            </Link>
          </div>
        </div>

      </nav>

      <div class="p-4 border-t border-gray-800">
        <form @submit.prevent="logout">
          <button type="submit" class="flex w-full items-center px-4 py-2 text-sm font-medium text-red-400 hover:bg-gray-800 hover:text-red-300 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Logout
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#0f172a] overflow-hidden">
      <!-- Top header for mobile -->
      <header class="md:hidden bg-[#111827] border-b border-gray-800 p-4 flex justify-between items-center">
        <span class="text-white font-bold tracking-wider">REVO RADIUS</span>
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
import { reactive, onMounted } from 'vue'

const logout = () => {
  router.post('/logout')
}

const page = usePage()
const isUrl = (path) => {
  return page.url.startsWith(path)
}

const openMenus = reactive({
  pppoe: false,
  hotspot: false
})

const toggleMenu = (menu) => {
  openMenus[menu] = !openMenus[menu]
}

// Automatically open menus if current URL is under them
onMounted(() => {
  if (isUrl('/customers') || isUrl('/billing')) {
    openMenus.pppoe = true
  }
  if (isUrl('/vouchers')) {
    openMenus.hotspot = true
  }
})
</script>
