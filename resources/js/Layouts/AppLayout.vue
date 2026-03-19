<template>
  <div>
    <header class="bg-blue-600 text-white p-4">
      <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">REVO RADIUS</h1>
        <nav class="space-x-4">
          <a href="/dashboard" class="hover:underline font-semibold">Dashboard</a>
          <a href="/routers" class="hover:underline font-semibold">Routers</a>
          <a href="/vouchers" class="hover:underline font-semibold">Vouchers</a>
          <a href="/customers" class="hover:underline font-semibold">Members</a>
          <a href="/billing" class="hover:underline font-semibold">Billing</a>
          <span class="text-blue-300">|</span>
          <form @submit.prevent="logout" class="inline">
            <button type="submit" class="hover:underline text-red-200">Logout</button>
          </form>
        </nav>
      </div>
    </header>
    <main class="container mx-auto p-4 mt-6">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const logout = () => {
  router.post('/logout')
}
</script>
