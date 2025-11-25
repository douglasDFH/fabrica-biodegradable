<template>
  <MainLayout>
    <Head title="Monitor de Máquinas" />
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-8">Monitor de Máquinas - Lista</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="maquina in maquinas" :key="maquina.id" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow duration-300">
          <h2 class="text-xl font-semibold mb-2">{{ maquina.nombre }}</h2>
          <p class="text-gray-600 mb-1">Código: {{ maquina.codigo }}</p>
          <p class="text-gray-600 mb-1">Tipo: {{ maquina.tipo?.nombre }}</p>
          <p class="text-gray-600 mb-1">Estado: {{ maquina.estado }}</p>
          <div v-if="maquina.estado_vivo" class="mt-4">
            <p class="text-sm text-gray-500">Estado Vivo: {{ maquina.estado_vivo.estado }}</p>
            <p class="text-sm text-gray-500">KG Producidos: {{ maquina.estado_vivo.kg_producidos }}</p>
          </div>
          <!-- target="_blank" abre en nueva pestaña -->
          <a
            :href="`/planta/monitor-maquina/${maquina.id}`"
            target="_blank"
            rel="noopener noreferrer"
            class="mt-4 inline-flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors duration-200"
          >
            Ver Monitor
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '../../Layouts/MainLayout.vue';

defineProps({
  maquinas: Array,
});
</script>