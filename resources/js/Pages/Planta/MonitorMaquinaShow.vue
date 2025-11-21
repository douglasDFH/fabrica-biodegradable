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

      <!-- Controles de simulación -->
      <div class="text-center mb-8">
        <div class="flex justify-center space-x-4 mb-4">
          <button
            @click="play"
            :disabled="simulacion.estado === 'playing'"
            class="bg-green-500 hover:bg-green-600 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg text-xl transition duration-300"
          >
            ▶️ Play
          </button>
          <button
            @click="pause"
            :disabled="simulacion.estado === 'stopped'"
            class="bg-yellow-500 hover:bg-yellow-600 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg text-xl transition duration-300"
          >
            ⏸️ Pause
          </button>
          <button
            @click="stop"
            class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg text-xl transition duration-300"
          >
            ⏹️ Stop
          </button>
        </div>
        <p class="text-lg text-gray-700">Estado: {{ simulacion.estado.toUpperCase() }}</p>
        <p class="text-sm text-gray-500">Conexión: {{ isOnline ? 'Online' : 'Offline' }}</p>
        <p class="text-sm text-gray-500">Producciones en cola: {{ colaProduccion.length }}</p>
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
import { onMounted, reactive, ref, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  maquina: Object,
});

const maquina = reactive(props.maquina);
const isOnline = ref(navigator.onLine);
const simulacion = reactive({
  estado: 'stopped', // 'stopped', 'playing', 'paused'
  intervalo: null,
});
const colaProduccion = ref(JSON.parse(localStorage.getItem('colaProduccion') || '[]'));

const guardarCola = () => {
  localStorage.setItem('colaProduccion', JSON.stringify(colaProduccion.value));
};

// Limpiar producciones inválidas de la cola (anteriores versiones)
colaProduccion.value = colaProduccion.value.filter(prod =>
  prod.kg_incremento !== undefined &&
  prod.oee !== undefined &&
  prod.velocidad !== undefined
);
guardarCola();

// Detectar cambios de conexión
const updateOnlineStatus = () => {
  isOnline.value = navigator.onLine;
  if (isOnline.value) {
    enviarColaProduccion();
  }
};

window.addEventListener('online', updateOnlineStatus);
window.addEventListener('offline', updateOnlineStatus);

onUnmounted(() => {
  window.removeEventListener('online', updateOnlineStatus);
  window.removeEventListener('offline', updateOnlineStatus);
  if (simulacion.intervalo) {
    clearInterval(simulacion.intervalo);
  }
  // Dejar de escuchar el canal dashboard
  if (window.Echo) {
    window.Echo.leave('dashboard');
  }
});

const actualizarEstadoMaquina = async (nuevoEstado) => {
  try {
    const response = await axios.put(`/api/maquina/${maquina.id}/estado`, {
      estado: nuevoEstado
    });
    
    if (response.data.success) {
      console.log('Estado actualizado:', response.data.estado);
    }
  } catch (error) {
    console.error('Error actualizando estado:', error);
  }
};

const play = async () => {
  if (simulacion.estado === 'playing') return;

  simulacion.estado = 'playing';
  
  // Actualizar estado en el servidor
  await actualizarEstadoMaquina('Produciendo');
  
  simulacion.intervalo = setInterval(() => {
    if (simulacion.estado === 'playing') {
      simularProduccion();
    }
  }, 5000); // Simular cada 5 segundos
};

const pause = async () => {
  simulacion.estado = 'paused';
  
  // Actualizar estado en el servidor
  await actualizarEstadoMaquina('Pausada');
  
  if (simulacion.intervalo) {
    clearInterval(simulacion.intervalo);
    simulacion.intervalo = null;
  }
};

const stop = async () => {
  simulacion.estado = 'stopped';
  
  // Actualizar estado en el servidor
  await actualizarEstadoMaquina('Parada');
  
  if (simulacion.intervalo) {
    clearInterval(simulacion.intervalo);
    simulacion.intervalo = null;
  }
};

const simularProduccion = () => {
  const kgIncremento = Math.random() * 10 + 5; // KG aleatorios entre 5-15
  const oee = Math.random() * 20 + 80; // OEE entre 80-100
  const velocidad = Math.random() * 20 + 80; // Velocidad entre 80-100

  const produccion = {
    maquina_id: maquina.id,
    kg_incremento: kgIncremento,
    oee: oee,
    velocidad: velocidad,
    timestamp: Date.now(),
  };

  colaProduccion.value.push(produccion);
  guardarCola();

  if (isOnline.value) {
    enviarColaProduccion();
  }
};

const enviarColaProduccion = async () => {
  if (colaProduccion.value.length === 0) return;

  // Filtrar solo producciones válidas (con kg_incremento, oee, velocidad)
  const produccionesValidas = colaProduccion.value.filter(prod =>
    prod.kg_incremento !== undefined &&
    prod.oee !== undefined &&
    prod.velocidad !== undefined
  );

  if (produccionesValidas.length === 0) return;

  try {
    const response = await axios.post('/api/simular-produccion', {
      producciones: produccionesValidas,
    });

    if (response.data.success) {
      // Remover solo las producciones válidas enviadas
      colaProduccion.value = colaProduccion.value.filter(prod =>
        !produccionesValidas.includes(prod)
      );
      guardarCola();
    }
  } catch (error) {
    console.error('Error enviando producciones:', error);
  }
};

onMounted(() => {
  console.log('Iniciando monitor para maquina ' + maquina.id);
  
  // Escuchar actualizaciones en tiempo real desde el canal dashboard
  if (window.Echo) {
    window.Echo.channel('dashboard')
      .listen('.maquina.estado.actualizado', (e) => {
        // Solo actualizar si es para esta máquina
        if (e.estado && e.estado.maquina_id === maquina.id) {
          maquina.estado_vivo = e.estado;
        }
      })
      .listen('.produccion.registrada', (e) => {
        // Actualizar estado si es para esta máquina
        if (e.estado && e.estado.maquina_id === maquina.id) {
          maquina.estado_vivo = e.estado;
        }
      });
  }

  // Intentar enviar cola al cargar si está online
  if (isOnline.value) {
    enviarColaProduccion();
  }
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