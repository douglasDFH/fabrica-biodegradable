<template>
  <div class="min-h-screen bg-blue-600 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-8 max-w-2xl w-full mx-4">
      <h1 class="text-4xl font-bold text-center mb-8 text-blue-600">{{ maquina.nombre }}</h1>

      <div class="grid grid-cols-2 gap-6 mb-8">
        <div class="text-center">
          <h2 class="text-2xl font-semibold text-gray-700">KG PRODUCIDOS</h2>
          <p class="text-5xl font-bold text-blue-600">{{ maquina.estado_vivo?.kg_producidos || 0.0 }}</p>
        </div>
        <div class="text-center">
          <h2 class="text-2xl font-semibold text-gray-700">OEE ACTUAL</h2>
          <p class="text-5xl font-bold text-green-600">{{ maquina.estado_vivo?.oee_actual || 0.0 }}%</p>
        </div>
      </div>

      <div class="text-center mb-8">
        <h2 class="text-2xl font-semibold text-gray-700">VELOCIDAD</h2>
        <p class="text-5xl font-bold text-purple-600">{{ maquina.estado_vivo?.velocidad_actual || 0 }} r/min</p>
      </div>

      <div class="text-center">
        <button
          @click="iniciarProduccion"
          class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-8 rounded-lg text-2xl transition duration-300 ease-in-out transform hover:scale-105"
        >
          INICIAR PRODUCCIÓN
        </button>
      </div>

      <div class="mt-8 text-center">
        <p class="text-gray-600">Código: {{ maquina.codigo }}</p>
        <p class="text-gray-600">Tipo: {{ maquina.tipo?.nombre }}</p>
        <p class="text-gray-600">Estado: {{ maquina.estado }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive } from 'vue';

const props = defineProps({
  maquina: Object,
});

const maquina = reactive(props.maquina);

onMounted(() => {
  console.log('Iniciando polling para maquina ' + maquina.id);
  setInterval(() => {
    fetch(`/api/maquina/${maquina.id}/estado`)
      .then(response => response.json())
      .then(data => {
        console.log('Estado recibido:', data);
        maquina.estado_vivo = data;
      })
      .catch(error => console.error('Error fetching estado:', error));
  }, 1000);
});
</script>

<script>
import { Head } from '@inertiajs/vue3';

export default {
  components: {
    Head,
  },
};
</script>