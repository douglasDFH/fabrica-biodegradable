<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside
      :class="[
        'fixed top-0 left-0 z-40 h-screen transition-transform',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
        'w-64 bg-gradient-to-b from-indigo-900 to-indigo-800 text-white shadow-2xl'
      ]"
    >
      <!-- Logo/Brand -->
      <div class="flex items-center gap-3 px-6 py-6 border-b border-indigo-700">
        <div class="bg-white/10 p-2 rounded-lg">
          <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        </div>
        <div>
          <h1 class="text-lg font-bold">Fábrica</h1>
          <p class="text-xs text-indigo-300">Biodegradable</p>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="px-4 py-6 space-y-2 overflow-y-auto h-[calc(100vh-120px)]">
        <!-- Inicio (Welcome) -->
        <a
          href="/welcome"
          :class="getNavClass('/welcome')"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span class="font-medium">Inicio</span>
        </a>

        <!-- Dashboard -->
        <a
          href="/dashboard"
          :class="getNavClass('/dashboard')"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
          <span class="font-medium">Dashboard</span>
        </a>

        <!-- Planta Section -->
        <div class="pt-4">
          <div class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">
            Planta
          </div>

          <!-- Monitor de Máquinas -->
          <a
            href="/planta/monitor-maquina"
            :class="getNavClass('/planta/monitor-maquina')"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="font-medium">Monitor de Máquinas</span>
          </a>
        </div>

        <!-- Gestión Section -->
        <div class="pt-4">
          <div class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">
            Gestión
          </div>

          <!-- Máquinas -->
          <a
            href="/maquinas"
            :class="getNavClass('/maquinas')"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-medium">Máquinas</span>
          </a>
        </div>

        <!-- Producción Section (Placeholder futuro) -->
        <div class="pt-4">
          <div class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">
            Producción
          </div>

          <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-400 cursor-not-allowed opacity-60"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="font-medium">Órdenes</span>
            <span class="ml-auto text-xs bg-indigo-700 px-2 py-0.5 rounded">Prox.</span>
          </a>
        </div>

        <!-- Reportes Section (Placeholder futuro) -->
        <div class="pt-4">
          <div class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">
            Reportes
          </div>

          <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-400 cursor-not-allowed opacity-60"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="font-medium">Analíticas</span>
            <span class="ml-auto text-xs bg-indigo-700 px-2 py-0.5 rounded">Prox.</span>
          </a>
        </div>
      </nav>
    </aside>

    <!-- Backdrop para mobile -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-30 bg-black/50 md:hidden"
    ></div>

    <!-- Main Content -->
    <div class="md:ml-64">
      <!-- Top Navbar -->
      <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
        <div class="flex items-center justify-between px-6 py-4">
          <!-- Mobile menu button -->
          <button
            @click="sidebarOpen = !sidebarOpen"
            class="md:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-600"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>

          <!-- Page Title (dinámico) -->
          <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-800">Sistema de Producción</h2>
          </div>

          <!-- User menu -->
          <div class="flex items-center gap-4">
            <!-- Notifications -->
            <button class="relative p-2 rounded-lg hover:bg-gray-100 text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
              </svg>
              <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- User profile -->
            <div class="flex items-center gap-3">
              <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-gray-700">Usuario Admin</p>
                <p class="text-xs text-gray-500">Administrador</p>
              </div>
              <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                UA
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const sidebarOpen = ref(false);

const getNavClass = (path) => {
  const currentPath = window.location.pathname;

  // Exact match para welcome y dashboard
  if (path === '/welcome' || path === '/dashboard') {
    const isActive = currentPath === path;
    return [
      'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200',
      isActive
        ? 'bg-white/20 text-white shadow-lg'
        : 'text-indigo-200 hover:bg-white/10 hover:text-white'
    ].join(' ');
  }

  // Partial match para otros paths
  const isActive = currentPath.startsWith(path);
  return [
    'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200',
    isActive
      ? 'bg-white/20 text-white shadow-lg'
      : 'text-indigo-200 hover:bg-white/10 hover:text-white'
  ].join(' ');
};
</script>
