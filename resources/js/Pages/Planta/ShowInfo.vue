<template>
    <MainLayout>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-8">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Reporte de Máquina: {{ maquina.nombre }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Código: {{ maquina.codigo }} | Tipo: {{ maquina.tipo?.nombre }}
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <Link href="/planta/monitor-maquina"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Volver al Monitor
                    </Link>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Filtros de Fecha</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Selecciona el rango de fechas para calcular las métricas.
                        </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Desde</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" v-model="form.fecha_inicio"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Hasta</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" v-model="form.fecha_fin"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button @click="applyFilters"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Aplicar Filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrics Cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Horas Trabajadas
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ metrics.horas_trabajadas }} h
                        </dd>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Total Material Producido
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ metrics.kg_producidos }} kg
                        </dd>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            OEE Promedio
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ metrics.oee_promedio }}%
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Productions Table -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Orden
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Inicio
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fin
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kg Producidos
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="produccion in producciones" :key="produccion.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ produccion.numero_orden }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(produccion.fecha_inicio) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(produccion.fecha_fin) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ produccion.cantidad_producida_kg }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': produccion.estado === 'Finalizada',
                                                    'bg-yellow-100 text-yellow-800': produccion.estado === 'EnCurso',
                                                    'bg-gray-100 text-gray-800': produccion.estado === 'Planificada',
                                                    'bg-red-100 text-red-800': produccion.estado === 'Cancelada'
                                                }">
                                                {{ produccion.estado }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="producciones.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No hay producciones en este rango de fechas.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    maquina: Object,
    metrics: Object,
    filters: Object,
    producciones: Array,
});

const form = useForm({
    fecha_inicio: props.filters.fecha_inicio,
    fecha_fin: props.filters.fecha_fin,
});

const applyFilters = () => {
    router.get(`/planta/${props.maquina.id}/getInfo`, {
        fecha_inicio: form.fecha_inicio,
        fecha_fin: form.fecha_fin,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString();
};
</script>
