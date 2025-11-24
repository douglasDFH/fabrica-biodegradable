<template>
    <div class="h-screen w-screen overflow-hidden" :class="maquina.id === 2 ? 'bg-gray-900' : 'bg-gradient-to-br from-gray-200 via-gray-300 to-gray-200'">
        <Head :title="`Monitor ${maquina.nombre}`" />

        <!-- DISEÑO HMI CLÁSICO PARA MÁQUINA 2 (KraussMaffei) - OPTIMIZADO PARA TV -->
        <div v-if="maquina.id === 2" class="h-full w-full flex flex-col p-2">
            <div class="flex-1 bg-[#8B9E8E] rounded-lg shadow-[inset_0_0_20px_rgba(0,0,0,0.3)] flex flex-col overflow-hidden border-8 border-gray-800">

                <!-- BARRA SUPERIOR - 5 Indicadores Grandes (MÁS ALTOS) -->
                <div class="bg-[#6B7B6E] h-48 p-4 grid grid-cols-5 gap-4 border-b-4 border-[#4A5A4D]">
                    <!-- Indicador Estado con color de fondo dinámico -->
                    <div :class="[
                        'border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col items-center justify-center gap-2',
                        getEstadoColorBg(maquina.estado_vivo?.estado)
                    ]">
                        <div class="text-sm font-bold text-gray-900 uppercase tracking-wide">ESTADO</div>
                        <div class="text-5xl font-black font-mono text-white drop-shadow-lg leading-none">
                            {{ maquina.estado_vivo?.estado || "N/A" }}
                        </div>
                    </div>

                    <!-- Indicador Tiempo -->
                    <div class="bg-[#5A6A5D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col items-center justify-center gap-2">
                        <div class="text-sm font-bold text-gray-300 uppercase tracking-wide">⏱ TIEMPO</div>
                        <div class="text-6xl font-black font-mono text-white leading-none">
                            {{ tiempoEnEstadoFormateado }}
                        </div>
                    </div>

                    <!-- Indicador KG Producidos -->
                    <div class="bg-[#5A6A5D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col items-center justify-center gap-2">
                        <div class="text-sm font-bold text-gray-300 uppercase tracking-wide">📦 KG PRODUCIDOS</div>
                        <div class="text-6xl font-black font-mono text-blue-300 leading-none">
                            {{ formatNumber(maquina.estado_vivo?.kg_producidos) }}
                        </div>
                    </div>

                    <!-- Indicador OEE -->
                    <div class="bg-[#5A6A5D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col items-center justify-center gap-2">
                        <div class="text-sm font-bold text-gray-300 uppercase tracking-wide">📊 OEE ACTUAL</div>
                        <div class="text-6xl font-black font-mono text-green-300 leading-none">
                            {{ formatNumber(maquina.estado_vivo?.oee_actual) }}%
                        </div>
                    </div>

                    <!-- Indicador Velocidad -->
                    <div class="bg-[#5A6A5D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col items-center justify-center gap-2">
                        <div class="text-sm font-bold text-gray-300 uppercase tracking-wide">⚡ VELOCIDAD</div>
                        <div class="text-6xl font-black font-mono text-orange-300 leading-none">
                            {{ formatNumber(maquina.estado_vivo?.velocidad_actual) }}
                        </div>
                    </div>
                </div>

                <!-- CONTENIDO PRINCIPAL -->
                <div class="flex-1 bg-[#98AB9B] p-3 flex gap-3 overflow-hidden">

                    <!-- SIDEBAR IZQUIERDO - Nombre de Máquina + Parámetros Técnicos -->
                    <div class="w-72 bg-[#5A6A5D] border-4 border-gray-900 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col">
                        <!-- Nombre de la Máquina -->
                        <div class="bg-[#4A5A4D] px-3 py-4 border-b-4 border-gray-900 text-center">
                            <div class="text-2xl font-black font-mono text-white tracking-wider">
                                {{ maquina.nombre }}
                            </div>
                        </div>

                        <!-- Parámetros Técnicos -->
                        <div class="flex-1 p-4 overflow-y-auto">
                            <div class="bg-[#6A7D6D] px-3 py-2 border-2 border-gray-900 text-white font-bold text-base text-center mb-3">
                                PARÁMETROS TÉCNICOS
                            </div>

                            <div class="space-y-2 text-white font-mono font-bold text-base">
                                <div class="bg-[#4A5A4D] border-2 border-gray-900 px-3 py-2 rounded">
                                    <div class="text-xs text-gray-300 mb-1">Piezas Producidas</div>
                                    <div class="text-xl">{{ formatInteger(maquina.estado_vivo?.piezas_producidas) }}</div>
                                </div>

                                <div class="bg-[#4A5A4D] border-2 border-gray-900 px-3 py-2 rounded">
                                    <div class="text-xs text-gray-300 mb-1">Metros Producidos</div>
                                    <div class="text-xl">{{ formatNumber(maquina.estado_vivo?.metros_producidos) }} m</div>
                                </div>

                                <div class="bg-[#4A5A4D] border-2 border-gray-900 px-3 py-2 rounded">
                                    <div class="text-xs text-gray-300 mb-1">Velocidad Fast</div>
                                    <div class="text-xl">{{ formatNumber(maquina.estado_vivo?.velocidad_fast) }}</div>
                                </div>

                                <div class="bg-[#4A5A4D] border-2 border-gray-900 px-3 py-2 rounded">
                                    <div class="text-xs text-gray-300 mb-1">Velocidad Slow</div>
                                    <div class="text-xl">{{ formatNumber(maquina.estado_vivo?.velocidad_slow) }}</div>
                                </div>

                                <div class="bg-[#4A5A4D] border-2 border-gray-900 px-3 py-2 rounded">
                                    <div class="text-xs text-gray-300 mb-1">Tiempo Ciclo Actual</div>
                                    <div class="text-xl">{{ formatNumber(maquina.estado_vivo?.tiempo_ciclo_actual) }} seg</div>
                                </div>

                                <div class="bg-[#4A5A4D] border-2 border-gray-900 px-3 py-2 rounded">
                                    <div class="text-xs text-gray-300 mb-1">Tiempo Ciclo Teórico</div>
                                    <div class="text-xl">{{ formatNumber(maquina.estado_vivo?.tiempo_ciclo_teorico) }} seg</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ÁREA CENTRAL - Imagen de la Máquina (TODO EL ESPACIO, SIN PADDING) -->
                    <div class="flex-1 bg-[#7A8D7D] border-4 border-gray-900 shadow-[inset_2px_2px_6px_rgba(0,0,0,0.4)] flex items-center justify-center relative overflow-hidden">
                        <!-- Imagen de la máquina si existe -->
                        <div v-if="maquina.foto" class="absolute inset-0 flex items-center justify-center">
                            <img :src="`/storage/${maquina.foto}`" :alt="maquina.nombre" class="w-full h-full object-contain">
                        </div>

                        <!-- Mensaje si no hay imagen -->
                        <div v-else class="text-center text-gray-600 text-5xl font-bold opacity-30">
                            SIN IMAGEN DE MÁQUINA
                        </div>
                    </div>

                    <!-- SIDEBAR DERECHO - Control de Estado -->
                    <div class="w-64 bg-[#7A8D7D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col gap-3">
                        <div class="bg-[#5A6A5D] px-3 py-3 border-2 border-gray-900 text-white font-bold text-lg text-center">
                            CONTROL DE ESTADO
                        </div>

                        <div class="flex-1 flex flex-col gap-3 justify-center">
                            <button
                                v-for="estado in ['Produciendo', 'Pausada', 'Parada', 'Alarma', 'Mantenimiento', 'Offline']"
                                :key="estado"
                                @click="cambiarEstado(estado)"
                                :class="[
                                    'px-4 py-4 border-4 font-black text-lg transition-all',
                                    simulacion.estado === estado
                                        ? 'bg-[#4A5A4D] border-gray-900 text-white shadow-[inset_4px_4px_8px_rgba(0,0,0,0.6)]'
                                        : 'bg-[#6A7D6D] border-gray-700 text-gray-900 shadow-[4px_4px_8px_rgba(0,0,0,0.4)] hover:bg-[#5A6A5D] hover:text-white'
                                ]"
                            >
                                {{ estado.toUpperCase() }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- BARRA INFERIOR - Widgets Industriales (5 columnas) -->
                <div class="bg-[#6B7B6E] h-52 p-3 grid grid-cols-5 gap-3 border-t-4 border-[#4A5A4D]">

                    <!-- Widget 1: Reloj de Presión (Gauge) -->
                    <div class="bg-[#5A6A5D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col items-center justify-center gap-2">
                        <div class="text-base font-bold text-white">⏱ PRESIÓN</div>
                        <div class="relative w-40 h-40 flex items-center justify-center">
                            <!-- SVG Gauge Mejorado -->
                            <svg viewBox="0 0 120 120" class="w-full h-full">
                                <!-- Círculo de fondo -->
                                <circle cx="60" cy="60" r="50" fill="#374151" opacity="0.3"/>

                                <!-- Arco de fondo gris -->
                                <path d="M 20 60 A 40 40 0 1 1 100 60" fill="none" stroke="#4b5563" stroke-width="10" stroke-linecap="round"/>

                                <!-- Zona Verde (0-60% del arco) -->
                                <path d="M 20 60 A 40 40 0 0 1 60 20" fill="none" stroke="#22c55e" stroke-width="10" stroke-linecap="round"/>

                                <!-- Zona Amarilla (60-80% del arco) -->
                                <path d="M 60 20 A 40 40 0 0 1 88 36" fill="none" stroke="#eab308" stroke-width="10" stroke-linecap="round"/>

                                <!-- Zona Roja (80-100% del arco) -->
                                <path d="M 88 36 A 40 40 0 0 1 100 60" fill="none" stroke="#dc2626" stroke-width="10" stroke-linecap="round"/>

                                <!-- Marcas de escala -->
                                <line x1="20" y1="60" x2="25" y2="60" stroke="#ffffff" stroke-width="2"/>
                                <line x1="32" y1="32" x2="36" y2="36" stroke="#ffffff" stroke-width="2"/>
                                <line x1="60" y1="20" x2="60" y2="25" stroke="#ffffff" stroke-width="2"/>
                                <line x1="88" y1="32" x2="84" y2="36" stroke="#ffffff" stroke-width="2"/>
                                <line x1="100" y1="60" x2="95" y2="60" stroke="#ffffff" stroke-width="2"/>

                                <!-- Aguja (animada con rotación basada en presión) -->
                                <g :style="`transform-origin: 60px 60px; transform: rotate(${presionActual * 1.8 - 90}deg)`">
                                    <line x1="60" y1="60" x2="60" y2="28" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                                    <circle cx="60" cy="60" r="6" fill="#ffffff"/>
                                    <circle cx="60" cy="60" r="3" fill="#dc2626"/>
                                </g>

                                <!-- Texto central -->
                                <text x="60" y="75" text-anchor="middle" fill="#ffffff" font-size="10" font-weight="bold" font-family="monospace">
                                    {{ presionActual }}
                                </text>
                                <text x="60" y="85" text-anchor="middle" fill="#9ca3af" font-size="7" font-family="monospace">
                                    BAR
                                </text>
                            </svg>
                        </div>
                        <div class="flex gap-3 items-center">
                            <div class="flex items-center gap-1">
                                <span class="w-4 h-4 rounded-full bg-green-500 border-2 border-white"></span>
                                <span class="text-xs text-white font-bold">0-60</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-4 h-4 rounded-full bg-yellow-500 border-2 border-white"></span>
                                <span class="text-xs text-white font-bold">60-80</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-4 h-4 rounded-full bg-red-500 border-2 border-white"></span>
                                <span class="text-xs text-white font-bold">80+</span>
                            </div>
                        </div>
                    </div>

                    <!-- Widget 2: Reloj de Válvula de Alivio -->
                    <div class="bg-[#5A6A5D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col items-center justify-center gap-2">
                        <div class="text-base font-bold text-white">🔧 VÁLVULA ALIVIO</div>
                        <div class="relative w-40 h-40 flex items-center justify-center">
                            <!-- SVG Gauge para Válvula de Alivio -->
                            <svg viewBox="0 0 120 120" class="w-full h-full">
                                <circle cx="60" cy="60" r="50" fill="#374151" opacity="0.3"/>
                                <path d="M 20 60 A 40 40 0 1 1 100 60" fill="none" stroke="#4b5563" stroke-width="10" stroke-linecap="round"/>

                                <!-- Zonas de color -->
                                <path d="M 20 60 A 40 40 0 0 1 60 20" fill="none" stroke="#22c55e" stroke-width="10" stroke-linecap="round"/>
                                <path d="M 60 20 A 40 40 0 0 1 88 36" fill="none" stroke="#eab308" stroke-width="10" stroke-linecap="round"/>
                                <path d="M 88 36 A 40 40 0 0 1 100 60" fill="none" stroke="#dc2626" stroke-width="10" stroke-linecap="round"/>

                                <!-- Marcas -->
                                <line x1="20" y1="60" x2="25" y2="60" stroke="#ffffff" stroke-width="2"/>
                                <line x1="32" y1="32" x2="36" y2="36" stroke="#ffffff" stroke-width="2"/>
                                <line x1="60" y1="20" x2="60" y2="25" stroke="#ffffff" stroke-width="2"/>
                                <line x1="88" y1="32" x2="84" y2="36" stroke="#ffffff" stroke-width="2"/>
                                <line x1="100" y1="60" x2="95" y2="60" stroke="#ffffff" stroke-width="2"/>

                                <!-- Aguja -->
                                <g :style="`transform-origin: 60px 60px; transform: rotate(${valvulaAlivio * 1.8 - 90}deg)`">
                                    <line x1="60" y1="60" x2="60" y2="28" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                                    <circle cx="60" cy="60" r="6" fill="#ffffff"/>
                                    <circle cx="60" cy="60" r="3" fill="#eab308"/>
                                </g>

                                <text x="60" y="75" text-anchor="middle" fill="#ffffff" font-size="10" font-weight="bold" font-family="monospace">
                                    {{ valvulaAlivio }}
                                </text>
                                <text x="60" y="85" text-anchor="middle" fill="#9ca3af" font-size="7" font-family="monospace">
                                    PSI
                                </text>
                            </svg>
                        </div>
                        <div class="flex gap-2 items-center text-xs text-white font-bold">
                            <span>0-60</span> | <span>60-80</span> | <span>80+</span>
                        </div>
                    </div>

                    <!-- Widget 3: Reloj de Aceite -->
                    <div class="bg-[#5A6A5D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col items-center justify-center gap-2">
                        <div class="text-base font-bold text-white">🛢 ACEITE</div>
                        <div class="relative w-40 h-40 flex items-center justify-center">
                            <!-- SVG Gauge para Aceite -->
                            <svg viewBox="0 0 120 120" class="w-full h-full">
                                <circle cx="60" cy="60" r="50" fill="#374151" opacity="0.3"/>
                                <path d="M 20 60 A 40 40 0 1 1 100 60" fill="none" stroke="#4b5563" stroke-width="10" stroke-linecap="round"/>

                                <!-- Zonas de color (invertidas: bajo es rojo, alto es verde) -->
                                <path d="M 20 60 A 40 40 0 0 1 60 20" fill="none" stroke="#dc2626" stroke-width="10" stroke-linecap="round"/>
                                <path d="M 60 20 A 40 40 0 0 1 88 36" fill="none" stroke="#eab308" stroke-width="10" stroke-linecap="round"/>
                                <path d="M 88 36 A 40 40 0 0 1 100 60" fill="none" stroke="#22c55e" stroke-width="10" stroke-linecap="round"/>

                                <!-- Marcas -->
                                <line x1="20" y1="60" x2="25" y2="60" stroke="#ffffff" stroke-width="2"/>
                                <line x1="32" y1="32" x2="36" y2="36" stroke="#ffffff" stroke-width="2"/>
                                <line x1="60" y1="20" x2="60" y2="25" stroke="#ffffff" stroke-width="2"/>
                                <line x1="88" y1="32" x2="84" y2="36" stroke="#ffffff" stroke-width="2"/>
                                <line x1="100" y1="60" x2="95" y2="60" stroke="#ffffff" stroke-width="2"/>

                                <!-- Aguja -->
                                <g :style="`transform-origin: 60px 60px; transform: rotate(${nivelAceite * 1.8 - 90}deg)`">
                                    <line x1="60" y1="60" x2="60" y2="28" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                                    <circle cx="60" cy="60" r="6" fill="#ffffff"/>
                                    <circle cx="60" cy="60" r="3" fill="#22c55e"/>
                                </g>

                                <text x="60" y="75" text-anchor="middle" fill="#ffffff" font-size="10" font-weight="bold" font-family="monospace">
                                    {{ nivelAceite }}
                                </text>
                                <text x="60" y="85" text-anchor="middle" fill="#9ca3af" font-size="7" font-family="monospace">
                                    NIVEL
                                </text>
                            </svg>
                        </div>
                        <div class="flex gap-2 items-center text-xs text-white font-bold">
                            <span class="text-red-400">BAJO</span> | <span class="text-yellow-400">MED</span> | <span class="text-green-400">ALTO</span>
                        </div>
                    </div>

                    <!-- Widget 4: Gráfico de Secuencia Mejorado (3 líneas) -->
                    <div class="bg-[#5A6A5D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col items-center justify-center">
                        <div class="text-sm font-bold text-white mb-2">📊 SECUENCIA</div>
                        <div class="w-full h-32 bg-[#1f2937] border-2 border-gray-900 rounded overflow-hidden relative">
                            <!-- Canvas para gráfico de 3 líneas -->
                            <canvas ref="multiLineCanvas" class="w-full h-full"></canvas>
                        </div>
                        <div class="flex gap-3 text-xs font-bold mt-1">
                            <span class="text-green-400">● Velocidad</span>
                            <span class="text-blue-400">● Tiempo</span>
                            <span class="text-red-400">● Calor</span>
                        </div>
                    </div>

                    <!-- Widget 5: Panel de Alarmas -->
                    <div class="bg-[#5A6A5D] border-4 border-gray-900 p-4 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.3)] flex flex-col">
                        <div class="text-sm font-bold text-white mb-2 flex items-center justify-center gap-2">
                            <span>🔔</span>
                            <span>ALARMAS</span>
                        </div>
                        <div class="flex-1 bg-[#1f2937] border-2 border-gray-900 rounded p-3 overflow-y-auto">
                            <div v-if="alarmasActivas.length === 0" class="text-center text-green-400 font-bold text-lg">
                                ✓ Sin alertas
                            </div>
                            <div v-else class="space-y-2">
                                <div
                                    v-for="(alarma, index) in alarmasActivas"
                                    :key="index"
                                    class="bg-red-900 border-2 border-red-600 px-2 py-1 rounded text-xs text-white font-bold animate-pulse"
                                >
                                    ⚠ {{ alarma }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs font-mono text-gray-300 mt-2 text-center">
                            Última actualización: {{ ultimaActualizacion }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- DISEÑO NEOMÓRFICO PARA MÁQUINA 1 Y OTRAS (Arburg, etc) -->
        <div v-else class="h-full w-full flex flex-col p-4 lg:p-6">
            <div class="flex-1 bg-gray-200 rounded-3xl shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] flex flex-col overflow-hidden">
                <!-- Barra Superior -->
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
                            <div class="bg-gray-200 rounded-3xl p-4 lg:p-6 shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] flex-1 flex flex-col">
                                <div class="bg-emerald-500 text-white px-3 py-2 mb-4 font-bold text-sm lg:text-base rounded-xl text-center">
                                    CONTROLES DE ESTADO
                                </div>
                                <div class="grid grid-cols-3 gap-3 lg:gap-4 place-items-center flex-1 content-center">
                                    <!-- Botón Produciendo - Estilo Glossy Industrial -->
                                    <button
                                        @click="cambiarEstado('Produciendo')"
                                        class="relative w-16 h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 rounded-full transition-all duration-200 hover:scale-105 active:scale-95"
                                        :style="{
                                            background: simulacion.estado === 'Produciendo'
                                                ? 'radial-gradient(circle at 30% 30%, #86efac, #22c55e 45%, #15803d)'
                                                : 'radial-gradient(circle at 30% 30%, #86efac, #22c55e 45%, #16a34a)',
                                            boxShadow: '0 6px 12px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.4), inset 0 -2px 8px rgba(0,0,0,0.3)',
                                            border: '4px solid #374151'
                                        }"
                                    >
                                        <span class="text-white text-3xl lg:text-4xl xl:text-5xl drop-shadow-lg">▶</span>
                                    </button>

                                    <!-- Botón Pausada -->
                                    <button
                                        @click="cambiarEstado('Pausada')"
                                        class="relative w-16 h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 rounded-full transition-all duration-200 hover:scale-105 active:scale-95"
                                        :style="{
                                            background: simulacion.estado === 'Pausada'
                                                ? 'radial-gradient(circle at 30% 30%, #fde047, #eab308 45%, #a16207)'
                                                : 'radial-gradient(circle at 30% 30%, #fde047, #eab308 45%, #ca8a04)',
                                            boxShadow: '0 6px 12px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.4), inset 0 -2px 8px rgba(0,0,0,0.3)',
                                            border: '4px solid #374151'
                                        }"
                                    >
                                        <span class="text-white text-3xl lg:text-4xl xl:text-5xl drop-shadow-lg font-black">❚❚</span>
                                    </button>

                                    <!-- Botón Parada -->
                                    <button
                                        @click="cambiarEstado('Parada')"
                                        class="relative w-16 h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 rounded-full transition-all duration-200 hover:scale-105 active:scale-95"
                                        :style="{
                                            background: simulacion.estado === 'Parada'
                                                ? 'radial-gradient(circle at 30% 30%, #dc2626, #b91c1c 45%, #7f1d1d)'
                                                : 'radial-gradient(circle at 30% 30%, #f87171, #dc2626 45%, #991b1b)',
                                            boxShadow: '0 6px 12px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.4), inset 0 -2px 8px rgba(0,0,0,0.3)',
                                            border: '4px solid #374151'
                                        }"
                                    >
                                        <span class="text-white text-2xl lg:text-3xl xl:text-4xl drop-shadow-lg font-black">■</span>
                                    </button>

                                    <!-- Botón Offline -->
                                    <button
                                        @click="cambiarEstado('Offline')"
                                        class="relative w-16 h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 rounded-full transition-all duration-200 hover:scale-105 active:scale-95"
                                        :style="{
                                            background: simulacion.estado === 'Offline'
                                                ? 'radial-gradient(circle at 30% 30%, #6b7280, #4b5563 45%, #1f2937)'
                                                : 'radial-gradient(circle at 30% 30%, #9ca3af, #6b7280 45%, #374151)',
                                            boxShadow: '0 6px 12px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.4), inset 0 -2px 8px rgba(0,0,0,0.3)',
                                            border: '4px solid #374151'
                                        }"
                                    >
                                        <span class="text-white text-2xl lg:text-3xl xl:text-4xl drop-shadow-lg font-black">●</span>
                                    </button>

                                    <!-- Botón Alarma -->
                                    <button
                                        @click="cambiarEstado('Alarma')"
                                        class="relative w-16 h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 rounded-full transition-all duration-200 hover:scale-105 active:scale-95"
                                        :style="{
                                            background: simulacion.estado === 'Alarma'
                                                ? 'radial-gradient(circle at 30% 30%, #fb923c, #f97316 45%, #c2410c)'
                                                : 'radial-gradient(circle at 30% 30%, #fdba74, #f97316 45%, #ea580c)',
                                            boxShadow: '0 6px 12px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.4), inset 0 -2px 8px rgba(0,0,0,0.3)',
                                            border: '4px solid #374151'
                                        }"
                                    >
                                        <span class="text-white text-2xl lg:text-3xl xl:text-4xl drop-shadow-lg font-black">⚠</span>
                                    </button>

                                    <!-- Botón Mantenimiento -->
                                    <button
                                        @click="cambiarEstado('Mantenimiento')"
                                        class="relative w-16 h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 rounded-full transition-all duration-200 hover:scale-105 active:scale-95"
                                        :style="{
                                            background: simulacion.estado === 'Mantenimiento'
                                                ? 'radial-gradient(circle at 30% 30%, #60a5fa, #3b82f6 45%, #1e40af)'
                                                : 'radial-gradient(circle at 30% 30%, #93c5fd, #3b82f6 45%, #2563eb)',
                                            boxShadow: '0 6px 12px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.4), inset 0 -2px 8px rgba(0,0,0,0.3)',
                                            border: '4px solid #374151'
                                        }"
                                    >
                                        <span class="text-white text-2xl lg:text-3xl xl:text-4xl drop-shadow-lg font-black">🔧</span>
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

// Variables para widgets de la barra inferior
const presionActual = ref(75); // Presión en bar (0-100)
const valvulaAlivio = ref(45); // Válvula de alivio en PSI (0-100)
const nivelAceite = ref(85); // Nivel de aceite (0-100)
const alarmasActivas = ref([]); // Array de alarmas activas
const multiLineCanvas = ref(null); // Referencia al canvas del gráfico de 3 líneas
let multiLineAnimationId = null;

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

const getEstadoColorBg = (estado) => {
    const colors = {
        Produciendo: "bg-green-600",
        Pausada: "bg-yellow-600",
        Parada: "bg-gray-600",
        Offline: "bg-red-600",
        Alarma: "bg-orange-600",
        Mantenimiento: "bg-blue-600",
    };
    return colors[estado] || "bg-gray-600";
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

// Función para animar el gráfico de 3 líneas (Velocidad, Tiempo, Calor)
const animateMultiLineGraph = () => {
    if (!multiLineCanvas.value) return;

    const canvas = multiLineCanvas.value;
    const ctx = canvas.getContext('2d');
    const width = canvas.width = canvas.offsetWidth;
    const height = canvas.height = canvas.offsetHeight;

    let offset = 0;
    const dataPoints = 100; // Número de puntos de datos históricos
    const velocidadData = [];
    const tiempoData = [];
    const calorData = [];

    // Inicializar datos con valores aleatorios
    for (let i = 0; i < dataPoints; i++) {
        velocidadData.push(Math.random() * 50 + 25); // 25-75
        tiempoData.push(Math.random() * 40 + 30); // 30-70
        calorData.push(Math.random() * 30 + 20); // 20-50
    }

    const draw = () => {
        // Fondo oscuro
        ctx.fillStyle = '#1f2937';
        ctx.fillRect(0, 0, width, height);

        // Dibujar cuadrícula (ejes X e Y)
        ctx.strokeStyle = '#374151';
        ctx.lineWidth = 1;

        // Líneas horizontales (eje Y)
        for (let i = 0; i <= 5; i++) {
            const y = (height / 5) * i;
            ctx.beginPath();
            ctx.moveTo(0, y);
            ctx.lineTo(width, y);
            ctx.stroke();
        }

        // Líneas verticales (eje X)
        for (let i = 0; i <= 10; i++) {
            const x = (width / 10) * i;
            ctx.beginPath();
            ctx.moveTo(x, 0);
            ctx.lineTo(x, height);
            ctx.stroke();
        }

        // Actualizar datos (scroll de izquierda a derecha)
        velocidadData.shift();
        velocidadData.push(Math.random() * 50 + 25);
        tiempoData.shift();
        tiempoData.push(Math.random() * 40 + 30);
        calorData.shift();
        calorData.push(Math.random() * 30 + 20);

        // Dibujar línea de Velocidad (Verde)
        ctx.strokeStyle = '#22c55e';
        ctx.lineWidth = 2;
        ctx.beginPath();
        for (let i = 0; i < velocidadData.length; i++) {
            const x = (width / dataPoints) * i;
            const y = height - (velocidadData[i] / 100) * height;
            if (i === 0) ctx.moveTo(x, y);
            else ctx.lineTo(x, y);
        }
        ctx.stroke();

        // Dibujar línea de Tiempo (Azul)
        ctx.strokeStyle = '#3b82f6';
        ctx.lineWidth = 2;
        ctx.beginPath();
        for (let i = 0; i < tiempoData.length; i++) {
            const x = (width / dataPoints) * i;
            const y = height - (tiempoData[i] / 100) * height;
            if (i === 0) ctx.moveTo(x, y);
            else ctx.lineTo(x, y);
        }
        ctx.stroke();

        // Dibujar línea de Calor (Rojo)
        ctx.strokeStyle = '#ef4444';
        ctx.lineWidth = 2;
        ctx.beginPath();
        for (let i = 0; i < calorData.length; i++) {
            const x = (width / dataPoints) * i;
            const y = height - (calorData[i] / 100) * height;
            if (i === 0) ctx.moveTo(x, y);
            else ctx.lineTo(x, y);
        }
        ctx.stroke();

        multiLineAnimationId = requestAnimationFrame(draw);
    };

    draw();
};

// Función para simular cambios en sensores (presión, válvula, aceite)
const simularSensores = () => {
    setInterval(() => {
        const estadoActual = simulacion.estado;

        if (estadoActual === 'Produciendo') {
            presionActual.value = parseFloat((60 + Math.random() * 25).toFixed(1));
            valvulaAlivio.value = parseFloat((40 + Math.random() * 30).toFixed(1));
            nivelAceite.value = parseFloat((75 + Math.random() * 20).toFixed(1));
        } else if (estadoActual === 'Pausada') {
            presionActual.value = parseFloat((30 + Math.random() * 20).toFixed(1));
            valvulaAlivio.value = parseFloat((20 + Math.random() * 25).toFixed(1));
            nivelAceite.value = parseFloat((60 + Math.random() * 25).toFixed(1));
        } else {
            presionActual.value = parseFloat((0 + Math.random() * 15).toFixed(1));
            valvulaAlivio.value = parseFloat((5 + Math.random() * 15).toFixed(1));
            nivelAceite.value = parseFloat((50 + Math.random() * 30).toFixed(1));
        }

        // Simular alarmas si hay condiciones críticas
        const alarmasPosibles = [];
        if (presionActual.value > 85) alarmasPosibles.push('Presión alta detectada');
        if (valvulaAlivio.value > 80) alarmasPosibles.push('Válvula de alivio crítica');
        if (nivelAceite.value < 30) alarmasPosibles.push('Nivel de aceite bajo');
        if (estadoActual === 'Alarma') {
            alarmasPosibles.push('Revisar sistema de enfriamiento', 'Temperatura excedida');
        }

        alarmasActivas.value = alarmasPosibles;
    }, 2000); // Actualizar cada 2 segundos
};

onMounted(() => {
    setInterval(() => {
        tiempoActual.value = Date.now();
    }, 1000);

    window.addEventListener("online", () => { isOnline.value = true; });
    window.addEventListener("offline", () => { isOnline.value = false; });

    // Iniciar animaciones para máquina 2 (HMI)
    if (maquina.id === 2) {
        setTimeout(() => {
            animateMultiLineGraph();
            simularSensores();
        }, 100);
    }

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
    if (multiLineAnimationId) cancelAnimationFrame(multiLineAnimationId);
});
</script>
