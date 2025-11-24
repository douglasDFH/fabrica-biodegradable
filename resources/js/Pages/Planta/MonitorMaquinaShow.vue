<template>
    <div class="min-h-screen bg-gray-100 p-6">
        <Head :title="`Monitor ${maquina.nombre}`" />

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Monitor Visual -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tarjeta Principal -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div
                        class="bg-blue-600 p-4 text-white flex justify-between items-center"
                    >
                        <h1 class="text-2xl font-bold">{{ maquina.nombre }}</h1>
                        <span
                            class="px-3 py-1 rounded-full text-sm font-semibold"
                            :class="getEstadoColor(maquina.estado_vivo?.estado)"
                        >
                            {{ maquina.estado_vivo?.estado || "DESCONOCIDO" }}
                        </span>
                    </div>

                    <div class="p-6 grid grid-cols-2 gap-8">
                        <div
                            class="text-center p-4 bg-blue-50 rounded-lg border border-blue-100"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-600 mb-2"
                            >
                                KG PRODUCIDOS
                            </h2>
                            <p class="text-4xl font-bold text-blue-600">
                                {{
                                    formatNumber(
                                        maquina.estado_vivo?.kg_producidos
                                    )
                                }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Total Acumulado
                            </p>
                        </div>

                        <div
                            class="text-center p-4 bg-green-50 rounded-lg border border-green-100"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-600 mb-2"
                            >
                                OEE ACTUAL
                            </h2>
                            <p class="text-4xl font-bold text-green-600">
                                {{
                                    formatNumber(
                                        maquina.estado_vivo?.oee_actual
                                    )
                                }}%
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Eficiencia Global
                            </p>
                        </div>

                        <div
                            class="text-center p-4 bg-purple-50 rounded-lg border border-purple-100"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-600 mb-2"
                            >
                                VELOCIDAD
                            </h2>
                            <p class="text-4xl font-bold text-purple-600">
                                {{
                                    formatNumber(
                                        maquina.estado_vivo?.velocidad_actual
                                    )
                                }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">r/min</p>
                        </div>

                        <div
                            class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-600 mb-2"
                            >
                                INFO MÁQUINA
                            </h2>
                            <div class="text-left text-sm space-y-1">
                                <p>
                                    <span class="font-medium">Código:</span>
                                    {{ maquina.codigo }}
                                </p>
                                <p>
                                    <span class="font-medium">Tipo:</span>
                                    {{ maquina.tipo?.nombre }}
                                </p>
                                <p>
                                    <span class="font-medium">Conexión:</span>
                                    <span
                                        :class="
                                            isOnline
                                                ? 'text-green-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        {{ isOnline ? "Online" : "Offline" }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Control de Estados -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Control de Estado
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <button
                            @click="cambiarEstado('Produciendo')"
                            :class="[
                                'py-3 px-4 rounded-lg font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm',
                                simulacion.estado === 'Produciendo'
                                    ? 'bg-green-600 text-white ring-2 ring-green-300'
                                    : 'bg-green-100 text-green-800 hover:bg-green-200',
                            ]"
                        >
                            ▶️ Produciendo
                        </button>
                        <button
                            @click="cambiarEstado('Pausada')"
                            :class="[
                                'py-3 px-4 rounded-lg font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm',
                                simulacion.estado === 'Pausada'
                                    ? 'bg-yellow-500 text-white ring-2 ring-yellow-300'
                                    : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200',
                            ]"
                        >
                            ⏸️ Pausada
                        </button>
                        <button
                            @click="cambiarEstado('Parada')"
                            :class="[
                                'py-3 px-4 rounded-lg font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm',
                                simulacion.estado === 'Parada'
                                    ? 'bg-gray-600 text-white ring-2 ring-gray-300'
                                    : 'bg-gray-100 text-gray-800 hover:bg-gray-200',
                            ]"
                        >
                            ⏹️ Parada
                        </button>
                        <button
                            @click="cambiarEstado('Offline')"
                            :class="[
                                'py-3 px-4 rounded-lg font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm',
                                simulacion.estado === 'Offline'
                                    ? 'bg-red-600 text-white ring-2 ring-red-300'
                                    : 'bg-red-100 text-red-800 hover:bg-red-200',
                            ]"
                        >
                            🔴 Offline
                        </button>
                        <button
                            @click="cambiarEstado('Alarma')"
                            :class="[
                                'py-3 px-4 rounded-lg font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm',
                                simulacion.estado === 'Alarma'
                                    ? 'bg-orange-600 text-white ring-2 ring-orange-300'
                                    : 'bg-orange-100 text-orange-800 hover:bg-orange-200',
                            ]"
                        >
                            ⚠️ Alarma
                        </button>
                        <button
                            @click="cambiarEstado('Mantenimiento')"
                            :class="[
                                'py-3 px-4 rounded-lg font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm',
                                simulacion.estado === 'Mantenimiento'
                                    ? 'bg-blue-600 text-white ring-2 ring-blue-300'
                                    : 'bg-blue-100 text-blue-800 hover:bg-blue-200',
                            ]"
                        >
                            🔧 Mantenimiento
                        </button>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Configuración y Estadísticas -->
            <div class="space-y-6">
                <!-- Configuración de Simulación -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3
                        class="text-lg font-bold text-gray-800 mb-4 border-b pb-2"
                    >
                        Configuración Simulación
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Velocidad Producción (kg/min)</label
                            >
                            <input
                                type="number"
                                v-model.number="
                                    simulacion.config.velocidadProduccion
                                "
                                @change="guardarConfiguracion"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Intervalo Generación (seg)</label
                            >
                            <input
                                type="number"
                                v-model.number="
                                    simulacion.config.intervaloGeneracion
                                "
                                @change="guardarConfiguracion"
                                min="1"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500"
                                    >OEE Min (%)</label
                                >
                                <input
                                    type="number"
                                    v-model.number="simulacion.config.oeeMin"
                                    @change="guardarConfiguracion"
                                    min="0"
                                    max="100"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500"
                                    >OEE Max (%)</label
                                >
                                <input
                                    type="number"
                                    v-model.number="simulacion.config.oeeMax"
                                    @change="guardarConfiguracion"
                                    min="0"
                                    max="100"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500"
                                    >Vel. Min (r/min)</label
                                >
                                <input
                                    type="number"
                                    v-model.number="
                                        simulacion.config.velocidadMin
                                    "
                                    @change="guardarConfiguracion"
                                    min="0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500"
                                    >Vel. Max (r/min)</label
                                >
                                <input
                                    type="number"
                                    v-model.number="
                                        simulacion.config.velocidadMax
                                    "
                                    @change="guardarConfiguracion"
                                    min="0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de Sesión -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3
                        class="text-lg font-bold text-gray-800 mb-4 border-b pb-2"
                    >
                        Estadísticas Sesión
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Generadas:</span>
                            <span class="font-bold">{{
                                simulacion.stats.produccionesGeneradas
                            }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Enviadas:</span>
                            <span class="font-bold text-green-600">{{
                                simulacion.stats.produccionesEnviadas
                            }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600"
                                >Pendientes (Cola):</span
                            >
                            <span class="font-bold text-orange-600">{{
                                colaProduccion.length
                            }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tiempo en Estado:</span>
                            <span class="font-mono">{{
                                tiempoEnEstadoFormateado
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import { onMounted, reactive, ref, onUnmounted, computed, watch } from "vue";
import axios from "axios";

const props = defineProps({
    maquina: Object,
});

const maquina = reactive(props.maquina);
const isOnline = ref(navigator.onLine);
const tiempoInicioEstado = ref(Date.now());
const tiempoActual = ref(Date.now());

const simulacion = reactive({
    estado: "Parada",
    intervalo: null,
    config: {
        velocidadProduccion: 10.0,
        intervaloGeneracion: 5,
        oeeMin: 80,
        oeeMax: 100,
        velocidadMin: 80,
        velocidadMax: 100,
    },
    stats: {
        produccionesGeneradas: 0,
        produccionesEnviadas: 0,
    },
});

const colaProduccion = ref(
    JSON.parse(localStorage.getItem("colaProduccion") || "[]")
);

// Computed properties
const tiempoEnEstadoFormateado = computed(() => {
    const diff = Math.floor(
        (tiempoActual.value - tiempoInicioEstado.value) / 1000
    );
    const h = Math.floor(diff / 3600)
        .toString()
        .padStart(2, "0");
    const m = Math.floor((diff % 3600) / 60)
        .toString()
        .padStart(2, "0");
    const s = (diff % 60).toString().padStart(2, "0");
    return `${h}:${m}:${s}`;
});

// Helpers
const formatNumber = (num) => {
    return num ? Number(num).toFixed(2) : "0.00";
};

const getEstadoColor = (estado) => {
    const colors = {
        Produciendo: "bg-green-500",
        Pausada: "bg-yellow-500",
        Parada: "bg-gray-500",
        Offline: "bg-red-500",
        Alarma: "bg-orange-500",
        Mantenimiento: "bg-blue-500",
    };
    return colors[estado] || "bg-gray-400";
};

const guardarCola = () => {
    localStorage.setItem(
        "colaProduccion",
        JSON.stringify(colaProduccion.value)
    );
};

// Lógica de Simulación
const cambiarEstado = async (nuevoEstado) => {
    if (simulacion.estado === nuevoEstado) return;

    // Si cambiamos a un estado de detención (Parada, Mantenimiento),
    // enviamos un último registro para cerrar la producción
    if (
        ["Parada", "Mantenimiento"].includes(nuevoEstado) &&
        simulacion.estado === "Produciendo"
    ) {
        const ultimaProduccion = {
            maquina_id: maquina.id,
            kg_incremento: 0, // No incrementamos, solo cerramos
            oee: simulacion.config.oeeMin, // Usamos el mínimo o el último valor real
            velocidad: 0,
            timestamp_generacion: Date.now(),
            is_last_register: true,
        };
        colaProduccion.value.push(ultimaProduccion);
        guardarCola();
        if (isOnline.value) enviarColaProduccion();
    }

    simulacion.estado = nuevoEstado;
    tiempoInicioEstado.value = Date.now();

    // ACTUALIZAR ESTADO VISUAL INMEDIATAMENTE (UI optimista)
    if (maquina.estado_vivo) {
        maquina.estado_vivo.estado = nuevoEstado;
    }

    // Actualizar estado en servidor
    try {
        await axios.put(`/api/maquina/${maquina.id}/estado`, {
            estado: nuevoEstado,
        });

        // Guardar configuración de simulación también para persistencia
        await guardarConfiguracion();
    } catch (error) {
        console.error("Error actualizando estado:", error);
        // Revertir el cambio visual si falla
        if (maquina.estado_vivo) {
            maquina.estado_vivo.estado = simulacion.estado;
        }
    }

    gestionarIntervaloSimulacion();
};

const gestionarIntervaloSimulacion = () => {
    // Limpiar intervalo existente
    if (simulacion.intervalo) {
        clearInterval(simulacion.intervalo);
        simulacion.intervalo = null;
    }

    // Estados que DETIENEN la producción (Stop/Pause)
    const estadosDetienen = ["Pausada", "Parada", "Mantenimiento"];

    // Si el estado actual NO está en la lista de detención, seguimos produciendo
    // Esto permite producir en: Produciendo, Offline, Alarma
    if (!estadosDetienen.includes(simulacion.estado)) {
        simulacion.intervalo = setInterval(() => {
            simularProduccion();
        }, simulacion.config.intervaloGeneracion * 1000);
    }
};

const simularProduccion = () => {
    // Asegurar que la configuración tenga valores válidos
    const config = {
        velocidadProduccion: parseFloat(simulacion.config.velocidadProduccion) || 10.0,
        intervaloGeneracion: parseInt(simulacion.config.intervaloGeneracion) || 5,
        oeeMin: parseFloat(simulacion.config.oeeMin) || 80,
        oeeMax: parseFloat(simulacion.config.oeeMax) || 100,
        velocidadMin: parseFloat(simulacion.config.velocidadMin) || 80,
        velocidadMax: parseFloat(simulacion.config.velocidadMax) || 100,
    };

    // Calcular valores aleatorios dentro de rangos configurados
    let oee = parseFloat(
        (
            Math.random() *
                (config.oeeMax - config.oeeMin) +
            config.oeeMin
        ).toFixed(2)
    );
    if (oee > 100) oee = 100.0;
    if (isNaN(oee)) oee = 85.0;

    const velocidad = parseFloat(
        (
            Math.random() *
                (config.velocidadMax -
                    config.velocidadMin) +
            config.velocidadMin
        ).toFixed(2)
    );
    if (isNaN(velocidad)) velocidad = 90.0;

    // Calcular incremento basado en velocidad de producción configurada
    // velocidadProduccion es kg/min, intervalo es segundos
    const kgPorSegundo = config.velocidadProduccion / 60;
    const kgIncremento = parseFloat(
        (kgPorSegundo * config.intervaloGeneracion).toFixed(2)
    );
    if (isNaN(kgIncremento)) kgIncremento = 0.83; // 10kg/min / 60 * 5seg = 0.83kg

    console.log('Valores calculados:', {
        oee,
        velocidad,
        kgIncremento,
        config
    });

    const produccion = {
        maquina_id: maquina.id,
        kg_incremento: kgIncremento,
        oee: oee,
        velocidad: velocidad,
        timestamp_generacion: Date.now(),
    };

    console.log('Producción a enviar:', produccion);

    colaProduccion.value.push(produccion);
    simulacion.stats.produccionesGeneradas++;
    guardarCola();

    if (isOnline.value) {
        enviarColaProduccion();
    }
};

const enviarColaProduccion = async () => {
    if (colaProduccion.value.length === 0) return;

    const produccionesParaEnviar = [...colaProduccion.value];

    console.log('Enviando producciones:', produccionesParaEnviar);

    try {
        const response = await axios.post("/api/simular-produccion", {
            producciones: produccionesParaEnviar,
        });

        console.log('Respuesta del servidor:', response.data);

        if (response.data.success) {
            simulacion.stats.produccionesEnviadas +=
                produccionesParaEnviar.length;

            // Remover las producciones enviadas de la cola
            // Usamos timestamp_generacion para identificar cuáles remover
            const timestampsEnviados = produccionesParaEnviar.map(
                (p) => p.timestamp_generacion
            );
            colaProduccion.value = colaProduccion.value.filter(
                (p) => !timestampsEnviados.includes(p.timestamp_generacion)
            );

            guardarCola();
        }
    } catch (error) {
        console.error("Error enviando producciones:", error);
    }
};

const guardarConfiguracion = async () => {
    try {
        await axios.put(`/api/maquina/${maquina.id}/simulacion`, {
            simulacion_activa: !["Pausada", "Parada", "Mantenimiento"].includes(
                simulacion.estado
            ),
            velocidad_simulacion: simulacion.config.velocidadProduccion,
            intervalo_simulacion: simulacion.config.intervaloGeneracion,
            oee_min: simulacion.config.oeeMin,
            oee_max: simulacion.config.oeeMax,
            velocidad_min: simulacion.config.velocidadMin,
            velocidad_max: simulacion.config.velocidadMax,
        });

        // Reiniciar intervalo si cambiaron parámetros y está produciendo (o en estado activo)
        if (
            !["Pausada", "Parada", "Mantenimiento"].includes(simulacion.estado)
        ) {
            gestionarIntervaloSimulacion();
        }
    } catch (error) {
        console.error("Error guardando configuración:", error);
    }
};

// Lifecycle Hooks
onMounted(async () => {
    console.log("Iniciando simulador...");

    // LIMPIAR COLA DE PRODUCCIÓN ANTIGUA CON FORMATO INCOMPATIBLE
    try {
        const colaGuardada = JSON.parse(localStorage.getItem("colaProduccion") || "[]");
        // Filtrar solo las producciones con el formato correcto
        const colaLimpia = colaGuardada.filter(p =>
            p.hasOwnProperty('kg_incremento') &&
            p.hasOwnProperty('oee') &&
            p.hasOwnProperty('velocidad') &&
            p.hasOwnProperty('timestamp_generacion')
        );

        if (colaLimpia.length !== colaGuardada.length) {
            console.warn(`Limpiando ${colaGuardada.length - colaLimpia.length} producciones antiguas con formato incompatible`);
            localStorage.setItem("colaProduccion", JSON.stringify(colaLimpia));
            colaProduccion.value = colaLimpia;
        }
    } catch (error) {
        console.error("Error limpiando cola:", error);
        // En caso de error, limpiar completamente
        localStorage.setItem("colaProduccion", "[]");
        colaProduccion.value = [];
    }

    // Recuperar estado inicial
    try {
        // Nota: Podríamos necesitar un endpoint específico para obtener config de simulación
        // Por ahora asumimos que viene en maquina.estado_vivo o lo pedimos aparte
        // Como agregamos campos a la tabla, deberían venir en maquina.estado_vivo si se recarga
        if (maquina.estado_vivo) {
            const estado = maquina.estado_vivo;
            simulacion.estado = estado.estado;

            // Si hay configuración guardada, usarla
            if (estado.velocidad_simulacion) {
                simulacion.config.velocidadProduccion = parseFloat(
                    estado.velocidad_simulacion
                );
                simulacion.config.intervaloGeneracion = parseInt(
                    estado.intervalo_simulacion
                );
                simulacion.config.oeeMin = parseFloat(estado.oee_min);
                simulacion.config.oeeMax = parseFloat(estado.oee_max);
                simulacion.config.velocidadMin = parseFloat(
                    estado.velocidad_min
                );
                simulacion.config.velocidadMax = parseFloat(
                    estado.velocidad_max
                );
            }

            // Restaurar simulación si estaba activa (cualquier estado que no detenga)
            if (
                !["Pausada", "Parada", "Mantenimiento"].includes(
                    simulacion.estado
                )
            ) {
                gestionarIntervaloSimulacion();
            }
        }
    } catch (error) {
        console.error("Error recuperando estado:", error);
    }

    // Listeners de conexión
    window.addEventListener("online", () => {
        isOnline.value = true;
        enviarColaProduccion();
    });
    window.addEventListener("offline", () => {
        isOnline.value = false;
    });

    // Timer para UI
    setInterval(() => {
        tiempoActual.value = Date.now();
    }, 1000);

    // WebSocket
    if (window.Echo) {
        window.Echo.channel("dashboard")
            .listen(".maquina.estado.actualizado", (e) => {
                console.log("Evento recibido: maquina.estado.actualizado", e);
                if (e.estado && e.estado.maquina_id === maquina.id) {
                    // IMPORTANTE: Actualizar TODOS los campos incluyendo el estado
                    // Este evento se emite cuando el usuario cambia el estado manualmente
                    Object.assign(maquina.estado_vivo, e.estado);

                    // Sincronizar el estado local de simulación con el estado de la máquina
                    if (e.estado.estado && simulacion.estado !== e.estado.estado) {
                        simulacion.estado = e.estado.estado;
                    }

                    console.log("Estado actualizado:", maquina.estado_vivo);
                }
            })
            .listen(".produccion.registrada", (e) => {
                console.log("Evento recibido: produccion.registrada", e);
                if (e.estado && e.estado.maquina_id === maquina.id) {
                    // IMPORTANTE: Solo actualizar MÉTRICAS, NO el campo 'estado'
                    // Este evento se emite cuando se registra producción
                    // El estado ya fue establecido por el usuario y NO debe cambiar
                    if (maquina.estado_vivo) {
                        maquina.estado_vivo.kg_producidos = e.estado.kg_producidos;
                        maquina.estado_vivo.oee_actual = e.estado.oee_actual;
                        maquina.estado_vivo.velocidad_actual = e.estado.velocidad_actual;
                        if (e.estado.produccion_id) {
                            maquina.estado_vivo.produccion_id = e.estado.produccion_id;
                        }
                        // NO actualizar: maquina.estado_vivo.estado
                    }
                    console.log("Métricas actualizadas (estado preservado):", maquina.estado_vivo);
                }
            })
            .error((error) => {
                console.error("Error en WebSocket:", error);
            });

        console.log("WebSocket configurado correctamente en canal 'dashboard'");
    } else {
        console.error("Echo no está disponible. Verifica la configuración de Reverb.");
    }

    // Intentar enviar cola pendiente
    if (isOnline.value) {
        enviarColaProduccion();
    }
});

onUnmounted(() => {
    if (simulacion.intervalo) clearInterval(simulacion.intervalo);
    if (window.Echo) window.Echo.leave("dashboard");
});
</script>
