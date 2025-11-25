<template>
  <MainLayout>
    <Head title="Nueva Máquina" />
    <div class="container mx-auto px-4 py-8 max-w-4xl">

        <div class="mb-6">
            <h1 class="text-3xl font-bold">Nueva Máquina</h1>
            <a href="/maquinas" class="text-blue-500 hover:underline">← Volver al listado</a>
        </div>

        <form @submit.prevent="submit" class="bg-white rounded-lg shadow-md p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Código *</label>
                    <input
                        v-model="form.codigo"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                        required
                    >
                    <span v-if="form.errors.codigo" class="text-red-500 text-sm">{{ form.errors.codigo }}</span>
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Nombre *</label>
                    <input
                        v-model="form.nombre"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                        required
                    >
                    <span v-if="form.errors.nombre" class="text-red-500 text-sm">{{ form.errors.nombre }}</span>
                </div>

                <!-- Tipo de Máquina -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Tipo de Máquina *</label>
                    <select v-model="form.tipo_maquina_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">Seleccionar...</option>
                        <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">
                            {{ tipo.nombre }}
                        </option>
                    </select>
                    <span v-if="form.errors.tipo_maquina_id" class="text-red-500 text-sm">{{ form.errors.tipo_maquina_id }}</span>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Estado *</label>
                    <select v-model="form.estado" class="w-full border rounded px-3 py-2" required>
                        <option value="Operativa">Operativa</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Fuera de servicio">Fuera de servicio</option>
                    </select>
                    <span v-if="form.errors.estado" class="text-red-500 text-sm">{{ form.errors.estado }}</span>
                </div>

                <!-- Fecha de Compra -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Fecha de Compra</label>
                    <input
                        v-model="form.fecha_compra"
                        type="date"
                        class="w-full border rounded px-3 py-2"
                    >
                    <span v-if="form.errors.fecha_compra" class="text-red-500 text-sm">{{ form.errors.fecha_compra }}</span>
                </div>

                <!-- Horas Trabajadas -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Horas Trabajadas</label>
                    <input
                        v-model="form.horas_trabajadas"
                        type="number"
                        step="0.01"
                        class="w-full border rounded px-3 py-2"
                    >
                    <span v-if="form.errors.horas_trabajadas" class="text-red-500 text-sm">{{ form.errors.horas_trabajadas }}</span>
                </div>

                <!-- Ubicación Planta -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-2">Ubicación en Planta</label>
                    <input
                        v-model="form.ubicacion_planta"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                    >
                    <span v-if="form.errors.ubicacion_planta" class="text-red-500 text-sm">{{ form.errors.ubicacion_planta }}</span>
                </div>

                <!-- Foto -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-2">Foto de la Máquina</label>
                    <input
                        @change="handleFileUpload"
                        type="file"
                        accept="image/*"
                        class="w-full border rounded px-3 py-2"
                    >
                    <span v-if="form.errors.foto" class="text-red-500 text-sm">{{ form.errors.foto }}</span>
                    <p class="text-xs text-gray-500 mt-1">Formatos permitidos: JPG, PNG, GIF, SVG. Máximo 2MB.</p>

                    <!-- Vista previa -->
                    <div v-if="previewUrl" class="mt-4">
                        <img :src="previewUrl" alt="Preview" class="w-32 h-32 object-cover rounded border">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" :disabled="form.processing" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-semibold">
                    Crear Máquina
                </button>
                <a href="/maquinas" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded font-semibold">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
  </MainLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import MainLayout from '../../Layouts/MainLayout.vue';

const props = defineProps({
    tipos: Array,
});

const form = useForm({
    codigo: '',
    nombre: '',
    tipo_maquina_id: '',
    fecha_compra: '',
    horas_trabajadas: '',
    estado: 'Operativa',
    ubicacion_planta: '',
    foto: null,
});

const previewUrl = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.foto = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post('/maquinas');
};
</script>
