<template>
  <MainLayout>
    <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
      <div class="text-center max-w-4xl px-6">
        <!-- Icon decorativo -->
        <div class="flex justify-center mb-8">
          <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-6 rounded-2xl shadow-2xl">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
        </div>

        <!-- Título principal -->
        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 tracking-tight">
          Bienvenido al sistema
        </h1>

        <!-- Descripción -->
        <p class="text-xl md:text-2xl text-gray-600 leading-relaxed">
          Un sistema optimizado y mejorado para la integración de monitoreo de máquinas industriales
        </p>

        <!-- Línea decorativa -->
        <div class="mt-12 flex justify-center">
          <div class="h-1 w-32 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full"></div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '../Layouts/MainLayout.vue';
</script>
