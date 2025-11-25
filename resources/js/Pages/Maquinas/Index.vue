<template>
  <MainLayout>
    <Head title="Gestión de Máquinas" />
    <div class="container mx-auto px-4 py-8">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Gestión de Máquinas</h1>
            <a href="/maquinas/create" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold">
                + Nueva Máquina
            </a>
        </div>

        <!-- Tabla de Máquinas -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="text-left p-4">Foto</th>
                        <th class="text-left p-4">Código</th>
                        <th class="text-left p-4">Nombre</th>
                        <th class="text-left p-4">Tipo</th>
                        <th class="text-left p-4">Estado</th>
                        <th class="text-left p-4">Ubicación</th>
                        <th class="text-left p-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="maquina in maquinas" :key="maquina.id" class="border-b hover:bg-gray-50">
                        <td class="p-4">
                            <img
                                v-if="maquina.foto"
                                :src="`/storage/${maquina.foto}`"
                                :alt="maquina.nombre"
                                class="w-16 h-16 object-cover rounded"
                            >
                            <div v-else class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Sin foto</span>
                            </div>
                        </td>
                        <td class="p-4 font-mono font-bold">{{ maquina.codigo }}</td>
                        <td class="p-4 font-semibold">{{ maquina.nombre }}</td>
                        <td class="p-4">{{ maquina.tipo?.nombre }}</td>
                        <td class="p-4">
                            <span :class="getEstadoBadge(maquina.estado)" class="px-3 py-1 rounded-full text-xs font-semibold">
                                {{ maquina.estado }}
                            </span>
                        </td>
                        <td class="p-4">{{ maquina.ubicacion_planta || 'N/A' }}</td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a :href="`/maquinas/${maquina.id}/edit`" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                    Editar
                                </a>
                                <button @click="eliminar(maquina.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import MainLayout from '../../Layouts/MainLayout.vue';

const props = defineProps({
    maquinas: Array,
});

const getEstadoBadge = (estado) => {
    const badges = {
        'Operativa': 'bg-green-100 text-green-800',
        'Mantenimiento': 'bg-yellow-100 text-yellow-800',
        'Fuera de servicio': 'bg-red-100 text-red-800',
    };
    return badges[estado] || 'bg-gray-100 text-gray-800';
};

const eliminar = (id) => {
    if (confirm('¿Estás seguro de eliminar esta máquina?')) {
        router.delete(`/maquinas/${id}`);
    }
};
</script>
