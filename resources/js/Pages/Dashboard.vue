<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-gray-900 mb-2">🏭 Dashboard de Producción</h1>
          <p class="text-gray-600">Monitoreo en tiempo real de la fábrica biodegradable</p>
          <div class="mt-2 flex items-center gap-4">
            <div class="text-sm text-gray-500">
              Última actualización: {{ ultimaActualizacion }}
            </div>
            <div v-if="wsConnected" class="flex items-center text-sm text-green-600">
              <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
              WebSocket conectado
            </div>
            <div v-else class="flex items-center text-sm text-red-600">
              <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
              WebSocket desconectado
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
          <p class="mt-4 text-gray-600">Cargando datos en tiempo real...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Error al cargar datos</h3>
              <div class="mt-2 text-sm text-red-700">{{ error }}</div>
              <button @click="reconnectWebSocket" class="mt-3 text-sm font-medium text-red-800 hover:text-red-900">
                Reintentar conexión
              </button>
            </div>
          </div>
        </div>

        <!-- Dashboard Content -->
        <div v-else>
          <!-- Métricas Principales -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total KG -->
            <MetricCard
              title="Total KG Producidos"
              :value="formatNumber(estadisticas.total_kg)"
              border-class="border-green-500"
              icon-bg-class="bg-green-100"
            >
              <template #icon>
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
              </template>
            </MetricCard>

            <!-- Total Producciones -->
            <MetricCard
              title="Total Producciones"
              :value="estadisticas.total_producciones || 0"
              border-class="border-blue-500"
              icon-bg-class="bg-blue-100"
            >
              <template #icon>
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
              </template>
            </MetricCard>

            <!-- Máquinas Activas -->
            <MetricCard
              title="Máquinas Activas"
              :value="estadisticas.maquinas_activas || 0"
              border-class="border-purple-500"
              icon-bg-class="bg-purple-100"
            >
              <template #icon>
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
              </template>
            </MetricCard>

            <!-- OEE Promedio -->
            <MetricCard
              title="OEE Promedio"
              :value="formatOEE(estadisticas.oee_promedio)"
              border-class="border-yellow-500"
              icon-bg-class="bg-yellow-100"
            >
              <template #icon>
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
              </template>
            </MetricCard>
          </div>

          <!-- Producción por Máquina -->
          <ProductionByMachine :produccion-por-maquina="produccionPorMaquina" />

          <!-- Estados de Máquinas en Tiempo Real -->
          <MachineStates :estados-maquinas="estadosMaquinas" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MetricCard from '../Components/MetricCard.vue';
import ProductionByMachine from '../Components/ProductionByMachine.vue';
import MachineStates from '../Components/MachineStates.vue';

export default {
  components: {
    MetricCard,
    ProductionByMachine,
    MachineStates
  },

  data() {
    return {
      loading: true,
      error: null,
      wsConnected: false,
      ultimaActualizacion: 'Esperando datos...',
      
      // Datos del dashboard
      estadisticas: {
        total_kg: 0,
        total_producciones: 0,
        maquinas_activas: 0,
        oee_promedio: 0
      },
      produccionPorMaquina: [],
      estadosMaquinas: [],
      
      // Referencias de Echo
      dashboardChannel: null,
      reconnectAttempts: 0,
      maxReconnectAttempts: 5
    }
  },

  mounted() {
    console.log('Dashboard Vue mounted - Modo SPA en tiempo real');
    this.setupWebSocketConnection();
  },

  beforeUnmount() {
    this.cleanupWebSocketConnection();
  },

  methods: {
    setupWebSocketConnection() {
      if (typeof window.Echo === 'undefined') {
        this.error = 'WebSocket no está disponible. Por favor, verifica la configuración.';
        this.loading = false;
        return;
      }

      try {
        console.log('Conectando al canal dashboard...');
        
        // Suscribirse al canal dashboard
        this.dashboardChannel = window.Echo.channel('dashboard');
        
        // Listener para producción registrada
        this.dashboardChannel.listen('.produccion.registrada', (data) => {
          console.log('📦 Producción registrada:', data);
          this.handleProduccionRegistrada(data);
        });
        
        // Listener para estado de máquina actualizado
        this.dashboardChannel.listen('.maquina.estado.actualizado', (data) => {
          console.log('⚙️ Estado de máquina actualizado:', data);
          this.handleMaquinaEstadoActualizado(data);
        });

        // Marcar como conectado
        this.wsConnected = true;
        this.loading = false;
        this.reconnectAttempts = 0;
        
        console.log('✅ WebSocket conectado correctamente al canal dashboard');
        
      } catch (error) {
        console.error('❌ Error configurando WebSocket:', error);
        this.error = 'Error al conectar con WebSocket: ' + error.message;
        this.loading = false;
        this.wsConnected = false;
        
        // Intentar reconectar
        this.scheduleReconnect();
      }
    },

    cleanupWebSocketConnection() {
      if (this.dashboardChannel) {
        console.log('Desconectando del canal dashboard...');
        window.Echo.leave('dashboard');
        this.dashboardChannel = null;
      }
      this.wsConnected = false;
    },

    reconnectWebSocket() {
      console.log('Intentando reconectar WebSocket...');
      this.error = null;
      this.loading = true;
      this.cleanupWebSocketConnection();
      this.setupWebSocketConnection();
    },

    scheduleReconnect() {
      if (this.reconnectAttempts < this.maxReconnectAttempts) {
        this.reconnectAttempts++;
        const delay = Math.min(1000 * Math.pow(2, this.reconnectAttempts), 30000);
        
        console.log(`Reintentando conexión en ${delay}ms (intento ${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
        
        setTimeout(() => {
          this.reconnectWebSocket();
        }, delay);
      } else {
        console.error('Máximo número de intentos de reconexión alcanzado');
        this.error = 'No se pudo conectar al servidor WebSocket después de varios intentos.';
      }
    },

    handleProduccionRegistrada(data) {
      try {
        // Actualizar estadísticas si vienen en el evento
        if (data.estadisticas) {
          this.estadisticas = { ...data.estadisticas };
        }

        // Actualizar producción por máquina si viene en el evento
        if (data.produccionPorMaquina) {
          this.produccionPorMaquina = [...data.produccionPorMaquina];
        }

        // Actualizar estado de máquina si viene en el evento
        if (data.estado) {
          this.actualizarEstadoMaquina(data.estado);
        }

        // Actualizar timestamp
        this.ultimaActualizacion = new Date().toLocaleString('es-ES');
        
      } catch (error) {
        console.error('Error procesando evento de producción:', error);
      }
    },

    handleMaquinaEstadoActualizado(data) {
      try {
        // Actualizar estadísticas si vienen en el evento
        if (data.estadisticas) {
          this.estadisticas = { ...data.estadisticas };
        }

        // Actualizar producción por máquina si viene en el evento
        if (data.produccionPorMaquina) {
          this.produccionPorMaquina = [...data.produccionPorMaquina];
        }

        // Actualizar estado de máquina
        if (data.estado) {
          this.actualizarEstadoMaquina(data.estado);
        }

        // Actualizar timestamp
        this.ultimaActualizacion = new Date().toLocaleString('es-ES');
        
      } catch (error) {
        console.error('Error procesando evento de máquina:', error);
      }
    },

    actualizarEstadoMaquina(nuevoEstado) {
      const index = this.estadosMaquinas.findIndex(
        e => e.maquina_id === nuevoEstado.maquina_id
      );

      if (index !== -1) {
        // Actualizar existente
        this.estadosMaquinas[index] = { ...nuevoEstado };
      } else {
        // Agregar nuevo
        this.estadosMaquinas.push({ ...nuevoEstado });
      }
    },

    formatNumber(value) {
      if (value === null || value === undefined) return '0';
      const num = parseFloat(value);
      return isNaN(num) ? '0' : num.toLocaleString('es-ES', { maximumFractionDigits: 2 });
    },

    formatOEE(value) {
      if (value === null || value === undefined) return '0.0%';
      const num = parseFloat(value);
      return isNaN(num) ? '0.0%' : num.toFixed(1) + '%';
    }
  }
};
</script>