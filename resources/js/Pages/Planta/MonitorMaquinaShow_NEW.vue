<template>
    <div class="h-screen w-screen bg-gradient-to-br from-gray-200 via-gray-300 to-gray-200 overflow-hidden">
        <Head :title="`Monitor ${maquina.nombre}`" />

        <!-- Panel Neomórfico - Fullscreen -->
        <div class="h-full w-full flex flex-col p-4 lg:p-6">
            <div class="flex-1 bg-gray-200 rounded-3xl shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] flex flex-col overflow-hidden">
                <!-- Barra Superior con estilo neomórfico -->
                <div class="bg-gray-200 p-3 lg:p-4 flex items-center justify-between border-b-2 border-gray-300">
                    <div class="flex items-center gap-2">
                        <!-- Iconos de función -->
                        <button class="w-10 h-10 bg-white rounded flex items-center justify-center hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </button>
                        <button class="w-10 h-10 bg-yellow-400 rounded flex items-center justify-center hover:bg-yellow-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.964-1.333-2.732 0L3.084 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </button>
                        <button class="w-10 h-10 bg-emerald-400 rounded flex items-center justify-center hover:bg-emerald-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="text-white text-sm font-semibold">
                        {{ new Date().toLocaleString('es-ES') }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span :class="['w-3 h-3 rounded-full', isOnline ? 'bg-green-400 animate-pulse' : 'bg-red-500']"></span>
                        <span class="text-white text-sm font-semibold">{{ isOnline ? 'ONLINE' : 'OFFLINE' }}</span>
                    </div>
                </div>

                <!-- Contenido Principal - Flex para ocupar todo el espacio -->
                <div class="flex-1 p-4 lg:p-6 bg-gray-100 overflow-y-auto">
                    <!-- 3 Columnas Verticales -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 h-full">
                        <!-- COLUMNA 1: KG PRODUCIDOS + CARACTERÍSTICAS MÁQUINA -->
                        <div class="flex flex-col gap-4">
                            <!-- KG Producidos (arriba) -->
                            <div class="bg-gray-200 rounded-3xl p-4 lg:p-6 xl:p-8 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff]">
                                <div class="text-xs lg:text-sm xl:text-base font-black text-gray-600 mb-2 uppercase tracking-wider">KG Producidos</div>
                                <div class="text-4xl lg:text-5xl xl:text-7xl font-black text-blue-600 font-mono text-center py-4">
                                    {{ formatNumber(maquina.estado_vivo?.kg_producidos) }}
                                </div>
                                <div class="text-xs lg:text-sm text-gray-500 text-center">Total Acumulado</div>
                            </div>

                            <!-- Características Máquina (abajo) -->
                            <div class="bg-gray-200 rounded-3xl p-4 lg:p-6 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] flex-1">
                                <div class="bg-emerald-500 text-white px-3 py-2 mb-4 font-bold text-sm lg:text-base rounded-xl text-center">
                                    CARACTERÍSTICAS MÁQUINA SELECCIONADA
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700 mb-1 block">Nombre Máquina</label>
                                        <div class="bg-blue-500 text-white p-3 rounded-xl font-bold text-center text-sm lg:text-base">
                                            {{ maquina.nombre }}
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700 mb-1 block">Código Máquina</label>
                                        <input type="text" :value="maquina.codigo" readonly class="w-full p-2 border-2 border-gray-300 rounded-xl bg-white text-gray-800 font-mono text-sm lg:text-base">
                                    </div>

                                    <div>
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700 mb-1 block">Tipo Máquina</label>
                                        <input type="text" :value="maquina.tipo?.nombre" readonly class="w-full p-2 border-2 border-gray-300 rounded-xl bg-gray-50 text-gray-600 text-sm lg:text-base">
                                    </div>

                                    <div>
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700 mb-1 block">Velocidad Actual</label>
                                        <div class="flex items-center gap-2">
                                            <input type="text" :value="formatNumber(maquina.estado_vivo?.velocidad_actual)" readonly class="flex-1 p-2 border-2 border-gray-300 rounded-xl bg-white text-gray-800 font-mono text-right text-sm lg:text-base">
                                            <span class="text-xs lg:text-sm text-gray-600 font-semibold">r/min</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA 2: OEE ACTUAL + MÉTRICAS PRODUCCIÓN -->
                        <div class="flex flex-col gap-4">
                            <!-- OEE Actual (arriba) -->
                            <div class="bg-gray-200 rounded-3xl p-4 lg:p-6 xl:p-8 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff]">
                                <div class="text-xs lg:text-sm xl:text-base font-black text-gray-600 mb-2 uppercase tracking-wider">OEE Actual</div>
                                <div class="text-4xl lg:text-5xl xl:text-7xl font-black text-green-600 font-mono text-center py-4">
                                    {{ formatNumber(maquina.estado_vivo?.oee_actual) }}%
                                </div>
                                <div class="text-xs lg:text-sm text-gray-500 text-center">Eficiencia Global</div>
                            </div>

                            <!-- Métricas Producción (abajo) -->
                            <div class="bg-gray-200 rounded-3xl p-4 lg:p-6 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] flex-1">
                                <div class="bg-emerald-500 text-white px-3 py-2 mb-4 font-bold text-sm lg:text-base rounded-xl text-center">
                                    MÉTRICAS DE PRODUCCIÓN
                                </div>
                                <div class="space-y-2">
                                    <!-- Piezas Producidas -->
                                    <div class="grid grid-cols-2 gap-2 items-center">
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700">Piezas Producidas</label>
                                        <input type="text" :value="formatInteger(maquina.estado_vivo?.piezas_producidas)" readonly class="p-1.5 border-2 border-gray-300 rounded-lg bg-white text-gray-800 font-mono text-right text-xs lg:text-sm">
                                    </div>

                                    <!-- Metros Producidos -->
                                    <div class="grid grid-cols-2 gap-2 items-center">
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700">Metros Producidos</label>
                                        <div class="flex items-center gap-1">
                                            <input type="text" :value="formatNumber(maquina.estado_vivo?.metros_producidos)" readonly class="flex-1 p-1.5 border-2 border-gray-300 rounded-lg bg-white text-gray-800 font-mono text-right text-xs lg:text-sm">
                                            <span class="text-xs text-gray-600">m</span>
                                        </div>
                                    </div>

                                    <!-- Velocidad Fast -->
                                    <div class="grid grid-cols-2 gap-2 items-center">
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700">Velocidad Fast</label>
                                        <div class="flex items-center gap-1">
                                            <input type="text" :value="formatNumber(maquina.estado_vivo?.velocidad_fast)" readonly class="flex-1 p-1.5 border-2 border-gray-300 rounded-lg bg-white text-gray-800 font-mono text-right text-xs lg:text-sm">
                                            <span class="text-xs text-gray-600">r/min</span>
                                        </div>
                                    </div>

                                    <!-- Velocidad Slow -->
                                    <div class="grid grid-cols-2 gap-2 items-center">
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700">Velocidad Slow</label>
                                        <div class="flex items-center gap-1">
                                            <input type="text" :value="formatNumber(maquina.estado_vivo?.velocidad_slow)" readonly class="flex-1 p-1.5 border-2 border-gray-300 rounded-lg bg-white text-gray-800 font-mono text-right text-xs lg:text-sm">
                                            <span class="text-xs text-gray-600">r/min</span>
                                        </div>
                                    </div>

                                    <!-- Tiempo Ciclo Actual -->
                                    <div class="grid grid-cols-2 gap-2 items-center">
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700">Tiempo Ciclo Actual</label>
                                        <div class="flex items-center gap-1">
                                            <input type="text" :value="formatNumber(maquina.estado_vivo?.tiempo_ciclo_actual)" readonly class="flex-1 p-1.5 border-2 border-gray-300 rounded-lg bg-white text-gray-800 font-mono text-right text-xs lg:text-sm">
                                            <span class="text-xs text-gray-600">seg</span>
                                        </div>
                                    </div>

                                    <!-- Tiempo Ciclo Teórico -->
                                    <div class="grid grid-cols-2 gap-2 items-center">
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700">Tiempo Ciclo Teórico</label>
                                        <div class="flex items-center gap-1">
                                            <input type="text" :value="formatNumber(maquina.estado_vivo?.tiempo_ciclo_teorico)" readonly class="flex-1 p-1.5 border-2 border-gray-300 rounded-lg bg-white text-gray-800 font-mono text-right text-xs lg:text-sm">
                                            <span class="text-xs text-gray-600">seg</span>
                                        </div>
                                    </div>

                                    <!-- Producción ID -->
                                    <div class="grid grid-cols-2 gap-2 items-center">
                                        <label class="text-xs lg:text-sm font-semibold text-gray-700">Producción ID</label>
                                        <input type="text" :value="maquina.estado_vivo?.produccion_id || 'N/A'" readonly class="p-1.5 border-2 border-gray-300 rounded-lg bg-gray-50 text-gray-600 font-mono text-right text-xs lg:text-sm">
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-300">
                                    <div class="text-xs font-semibold text-gray-700 mb-1">Última Actualización</div>
                                    <div class="bg-white p-2 rounded-lg font-mono text-xs text-center shadow-[inset_2px_2px_4px_#bebebe]">
                                        {{ formatDateTime(maquina.estado_vivo?.updated_at) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA 3: ESTADO + BOTONES REDONDOS -->
                        <div class="flex flex-col gap-4">
                            <!-- Estado (arriba) -->
                            <div class="bg-gray-200 rounded-3xl p-4 lg:p-6 xl:p-8 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff]">
                                <div class="text-xs lg:text-sm xl:text-base font-black text-gray-600 mb-2 uppercase tracking-wider">Estado</div>
                                <div :class="['text-2xl lg:text-3xl xl:text-5xl font-black font-mono text-center py-4', getEstadoColorText(maquina.estado_vivo?.estado)]">
                                    {{ maquina.estado_vivo?.estado || "DESCONOCIDO" }}
                                </div>
                                <div class="text-xs lg:text-sm text-gray-500 text-center">{{ tiempoEnEstadoFormateado }}</div>
                            </div>

                            <!-- Botones Redondos de Control (abajo) -->
                            <div class="bg-gray-200 rounded-3xl p-4 lg:p-6 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] flex-1">
                                <div class="bg-emerald-500 text-white px-3 py-2 mb-4 font-bold text-sm lg:text-base rounded-xl text-center">
                                    CONTROLES DE ESTADO
                                </div>
                                <div class="grid grid-cols-2 gap-3 lg:gap-4">
                                    <!-- Botón Produciendo -->
                                    <button
                                        @click="cambiarEstado('Produciendo')"
                                        :class="[
                                            'aspect-square rounded-full font-black text-xs lg:text-sm transition-all duration-300 flex flex-col items-center justify-center gap-1',
                                            simulacion.estado === 'Produciendo'
                                                ? 'bg-green-500 text-white shadow-[inset_8px_8px_16px_rgba(0,0,0,0.2),inset_-8px_-8px_16px_rgba(255,255,255,0.1)] transform scale-95'
                                                : 'bg-gray-200 text-green-700 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] hover:shadow-[inset_2px_2px_4px_#bebebe,inset_-2px_-2px_4px_#ffffff] active:scale-95',
                                        ]"
                                    >
                                        <span class="text-3xl lg:text-4xl">▶️</span>
                                        <span class="text-xs">Produciendo</span>
                                    </button>

                                    <!-- Botón Pausada -->
                                    <button
                                        @click="cambiarEstado('Pausada')"
                                        :class="[
                                            'aspect-square rounded-full font-black text-xs lg:text-sm transition-all duration-300 flex flex-col items-center justify-center gap-1',
                                            simulacion.estado === 'Pausada'
                                                ? 'bg-yellow-500 text-white shadow-[inset_8px_8px_16px_rgba(0,0,0,0.2),inset_-8px_-8px_16px_rgba(255,255,255,0.1)] transform scale-95'
                                                : 'bg-gray-200 text-yellow-700 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] hover:shadow-[inset_2px_2px_4px_#bebebe,inset_-2px_-2px_4px_#ffffff] active:scale-95',
                                        ]"
                                    >
                                        <span class="text-3xl lg:text-4xl">⏸️</span>
                                        <span class="text-xs">Pausada</span>
                                    </button>

                                    <!-- Botón Parada -->
                                    <button
                                        @click="cambiarEstado('Parada')"
                                        :class="[
                                            'aspect-square rounded-full font-black text-xs lg:text-sm transition-all duration-300 flex flex-col items-center justify-center gap-1',
                                            simulacion.estado === 'Parada'
                                                ? 'bg-gray-600 text-white shadow-[inset_8px_8px_16px_rgba(0,0,0,0.2),inset_-8px_-8px_16px_rgba(255,255,255,0.1)] transform scale-95'
                                                : 'bg-gray-200 text-gray-800 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] hover:shadow-[inset_2px_2px_4px_#bebebe,inset_-2px_-2px_4px_#ffffff] active:scale-95',
                                        ]"
                                    >
                                        <span class="text-3xl lg:text-4xl">⏹️</span>
                                        <span class="text-xs">Parada</span>
                                    </button>

                                    <!-- Botón Offline -->
                                    <button
                                        @click="cambiarEstado('Offline')"
                                        :class="[
                                            'aspect-square rounded-full font-black text-xs lg:text-sm transition-all duration-300 flex flex-col items-center justify-center gap-1',
                                            simulacion.estado === 'Offline'
                                                ? 'bg-red-600 text-white shadow-[inset_8px_8px_16px_rgba(0,0,0,0.2),inset_-8px_-8px_16px_rgba(255,255,255,0.1)] transform scale-95'
                                                : 'bg-gray-200 text-red-700 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] hover:shadow-[inset_2px_2px_4px_#bebebe,inset_-2px_-2px_4px_#ffffff] active:scale-95',
                                        ]"
                                    >
                                        <span class="text-3xl lg:text-4xl">🔴</span>
                                        <span class="text-xs">Offline</span>
                                    </button>

                                    <!-- Botón Alarma -->
                                    <button
                                        @click="cambiarEstado('Alarma')"
                                        :class="[
                                            'aspect-square rounded-full font-black text-xs lg:text-sm transition-all duration-300 flex flex-col items-center justify-center gap-1',
                                            simulacion.estado === 'Alarma'
                                                ? 'bg-orange-500 text-white shadow-[inset_8px_8px_16px_rgba(0,0,0,0.2),inset_-8px_-8px_16px_rgba(255,255,255,0.1)] transform scale-95'
                                                : 'bg-gray-200 text-orange-700 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] hover:shadow-[inset_2px_2px_4px_#bebebe,inset_-2px_-2px_4px_#ffffff] active:scale-95',
                                        ]"
                                    >
                                        <span class="text-3xl lg:text-4xl">⚠️</span>
                                        <span class="text-xs">Alarma</span>
                                    </button>

                                    <!-- Botón Mantenimiento -->
                                    <button
                                        @click="cambiarEstado('Mantenimiento')"
                                        :class="[
                                            'aspect-square rounded-full font-black text-xs lg:text-sm transition-all duration-300 flex flex-col items-center justify-center gap-1',
                                            simulacion.estado === 'Mantenimiento'
                                                ? 'bg-blue-500 text-white shadow-[inset_8px_8px_16px_rgba(0,0,0,0.2),inset_-8px_-8px_16px_rgba(255,255,255,0.1)] transform scale-95'
                                                : 'bg-gray-200 text-blue-700 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] hover:shadow-[inset_2px_2px_4px_#bebebe,inset_-2px_-2px_4px_#ffffff] active:scale-95',
                                        ]"
                                    >
                                        <span class="text-3xl lg:text-4xl">🔧</span>
                                        <span class="text-xs">Manten.</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barra Inferior -->
                <div class="bg-gray-700 p-2 flex items-center justify-between text-white text-sm">
                    <div>Cola de producción: <span class="font-bold">{{ colaProduccion.length }}</span> pendientes</div>
                    <div>Última actualización: <span class="font-mono">{{ ultimaActualizacion }}</span></div>
                </div>
            </div>
        </div>

        <!-- Panel de Configuración (Modal o Drawer) -->
        <div v-if="mostrarConfiguracion" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="mostrarConfiguracion = false">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4" @click.stop>
                <h3 class="text-xl font-bold mb-4">Configuración de Simulación</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Velocidad de Producción (kg/min)</label>
                        <input v-model.number="simulacion.config.velocidadProduccion" type="number" step="0.1" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Intervalo de Generación (seg)</label>
                        <input v-model.number="simulacion.config.intervaloGeneracion" type="number" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">OEE Mínimo (%)</label>
                        <input v-model.number="simulacion.config.oeeMin" type="number" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">OEE Máximo (%)</label>
                        <input v-model.number="simulacion.config.oeeMax" type="number" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Velocidad Mínima</label>
                        <input v-model.number="simulacion.config.velocidadMin" type="number" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Velocidad Máxima</label>
                        <input v-model.number="simulacion.config.velocidadMax" type="number" class="w-full p-2 border rounded">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button @click="mostrarConfiguracion = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                    <button @click="guardarConfiguracion" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import { ref, reactive, computed, onMounted, onUnmounted } from "vue";
import axios from "axios";

const props = defineProps({
    maquina: Object,
});

const maquina = reactive(props.maquina);
const isOnline = ref(navigator.onLine);
const tiempoInicioEstado = ref(Date.now());
const tiempoActual = ref(Date.now());
const mostrarConfiguracion = ref(false);
const ultimaActualizacion = ref(new Date().toLocaleTimeString('es-ES'));

const simulacion = reactive({
    estado: maquina.estado_vivo?.estado || "Parada",
    intervalo: null,
    config: {
        velocidadProduccion: 10.0,
        intervaloGeneracion: 5,
        oeeMin: 80,
        oeeMax: 100,
        velocidadMin: 80,
        velocidadMax: 100,
    },
});

const colaProduccion = ref([]);

const tiempoEnEstadoFormateado = computed(() => {
    const diff = Math.floor((tiempoActual.value - tiempoInicioEstado.value) / 1000);
    const h = Math.floor(diff / 3600).toString().padStart(2, "0");
    const m = Math.floor((diff % 3600) / 60).toString().padStart(2, "0");
    const s = (diff % 60).toString().padStart(2, "0");
    return `${h}:${m}:${s}`;
});

const formatNumber = (num) => {
    return num ? Number(num).toFixed(2) : "0.00";
};

const formatInteger = (num) => {
    return num ? Number(num).toFixed(0) : "0";
};

const formatDateTime = (dateTime) => {
    if (!dateTime) return "N/A";
    try {
        return new Date(dateTime).toLocaleString('es-ES', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    } catch (e) {
        return "Fecha inválida";
    }
};

const getEstadoColorText = (estado) => {
    const colors = {
        Produciendo: "text-green-600",
        Pausada: "text-yellow-600",
        Parada: "text-gray-600",
        Offline: "text-red-600",
        Alarma: "text-orange-600",
        Mantenimiento: "text-blue-600",
    };
    return colors[estado] || "text-gray-600";
};

const cambiarEstado = async (nuevoEstado) => {
    if (simulacion.estado === nuevoEstado) return;

    simulacion.estado = nuevoEstado;
    tiempoInicioEstado.value = Date.now();

    if (maquina.estado_vivo) {
        maquina.estado_vivo.estado = nuevoEstado;
    }

    try {
        await axios.put(`/api/maquina/${maquina.id}/estado`, { estado: nuevoEstado });
        gestionarIntervaloSimulacion();
    } catch (error) {
        console.error("Error actualizando estado:", error);
    }
};

const gestionarIntervaloSimulacion = () => {
    if (simulacion.intervalo) {
        clearInterval(simulacion.intervalo);
        simulacion.intervalo = null;
    }

    const estadosDetienen = ["Pausada", "Parada", "Mantenimiento"];

    if (!estadosDetienen.includes(simulacion.estado)) {
        simulacion.intervalo = setInterval(() => {
            simularProduccion();
        }, simulacion.config.intervaloGeneracion * 1000);
    }
};

const simularProduccion = () => {
    const oee = parseFloat((Math.random() * (simulacion.config.oeeMax - simulacion.config.oeeMin) + simulacion.config.oeeMin).toFixed(2));
    const velocidad = parseFloat((Math.random() * (simulacion.config.velocidadMax - simulacion.config.velocidadMin) + simulacion.config.velocidadMin).toFixed(2));
    const kgIncremento = parseFloat((simulacion.config.velocidadProduccion * (simulacion.config.intervaloGeneracion / 60)).toFixed(2));

    const produccion = {
        maquina_id: maquina.id,
        kg_incremento: kgIncremento,
        oee: oee,
        velocidad: velocidad,
        timestamp_generacion: Date.now(),
    };

    enviarProduccion([produccion]);
};

const enviarProduccion = async (producciones) => {
    try {
        console.log('Enviando producciones:', producciones);
        const response = await axios.post("/api/simular-produccion", { producciones });
        console.log('Respuesta del servidor:', response.data);
        ultimaActualizacion.value = new Date().toLocaleTimeString('es-ES');
    } catch (error) {
        console.error("Error enviando producción:", error);
    }
};

const guardarConfiguracion = async () => {
    mostrarConfiguracion.value = false;
    try {
        await axios.put(`/api/maquina/${maquina.id}/simulacion`, {
            simulacion_activa: !["Pausada", "Parada", "Mantenimiento"].includes(simulacion.estado),
            velocidad_simulacion: simulacion.config.velocidadProduccion,
            intervalo_simulacion: simulacion.config.intervaloGeneracion,
            oee_min: simulacion.config.oeeMin,
            oee_max: simulacion.config.oeeMax,
            velocidad_min: simulacion.config.velocidadMin,
            velocidad_max: simulacion.config.velocidadMax,
        });

        if (!["Pausada", "Parada", "Mantenimiento"].includes(simulacion.estado)) {
            gestionarIntervaloSimulacion();
        }
    } catch (error) {
        console.error("Error guardando configuración:", error);
    }
};

onMounted(() => {
    setInterval(() => {
        tiempoActual.value = Date.now();
    }, 1000);

    window.addEventListener("online", () => { isOnline.value = true; });
    window.addEventListener("offline", () => { isOnline.value = false; });

    // WebSocket
    if (window.Echo) {
        window.Echo.channel("dashboard")
            .listen(".maquina.estado.actualizado", (e) => {
                if (e.estado && e.estado.maquina_id === maquina.id) {
                    Object.assign(maquina.estado_vivo, e.estado);
                    if (e.estado.estado && simulacion.estado !== e.estado.estado) {
                        simulacion.estado = e.estado.estado;
                    }
                }
            })
            .listen(".produccion.registrada", (e) => {
                if (e.estado && e.estado.maquina_id === maquina.id) {
                    if (maquina.estado_vivo) {
                        // Actualizar TODOS los campos excepto 'estado'
                        maquina.estado_vivo.kg_producidos = e.estado.kg_producidos;
                        maquina.estado_vivo.oee_actual = e.estado.oee_actual;
                        maquina.estado_vivo.velocidad_actual = e.estado.velocidad_actual;
                        maquina.estado_vivo.piezas_producidas = e.estado.piezas_producidas;
                        maquina.estado_vivo.metros_producidos = e.estado.metros_producidos;
                        maquina.estado_vivo.velocidad_fast = e.estado.velocidad_fast;
                        maquina.estado_vivo.velocidad_slow = e.estado.velocidad_slow;
                        maquina.estado_vivo.tiempo_ciclo_actual = e.estado.tiempo_ciclo_actual;
                        maquina.estado_vivo.tiempo_ciclo_teorico = e.estado.tiempo_ciclo_teorico;
                        maquina.estado_vivo.produccion_id = e.estado.produccion_id;
                        maquina.estado_vivo.updated_at = e.estado.updated_at;
                        // NO actualizar: maquina.estado_vivo.estado

                        ultimaActualizacion.value = new Date().toLocaleTimeString('es-ES');
                    }
                }
            });
    }
});

onUnmounted(() => {
    if (simulacion.intervalo) clearInterval(simulacion.intervalo);
    if (window.Echo) window.Echo.leave("dashboard");
});
</script>
