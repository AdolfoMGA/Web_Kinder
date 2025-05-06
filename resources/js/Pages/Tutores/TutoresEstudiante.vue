<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    tutores: Array
});

const form = useForm({});

const deleteTutor = (id, nombre) => {
    Swal.fire({
        title: `¿Seguro que quieres eliminar a ${nombre}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        buttonsStyling: true,
    }).then(result => {
        if (result.isConfirmed) {
            form.delete(route('asignaparentescos.destroy', id));
        }
    });
};

const showModal = ref(false);
const selectedImage = ref('');

const openImage = (foto) => {
    selectedImage.value = `/storage/${foto}`;
    showModal.value = true;
};

const closeImage = () => {
    showModal.value = false;
    selectedImage.value = '';
};
</script>

<template>
    <AppLayout title="Tutores del Estudiante">
        <template #header>
            <h2 class="text-2xl font-bold text-white text-center py-4 bg-gradient-to-r from-cyan-500 to-blue-500 shadow-md rounded-b-lg">
                TUTORES ASIGNADOS
            </h2>
        </template>

        <div class="py-10 px-4 bg-gradient-to-br from-cyan-100 via-blue-50 to-white min-h-screen">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-end mb-4">
                    <Link
                        :href="route('estudiantes.index')"
                        class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition"
                    >
                        <i class="fa-solid fa-arrow-left mr-1"></i> Volver
                    </Link>
                </div>

                <div class="overflow-x-auto bg-white shadow-md rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-medium">
                            <tr>
                                <th class="px-4 py-3 text-left">Nombre</th>
                                <th class="px-4 py-3 text-left">Apellidos</th>
                                <th class="px-4 py-3 text-left">Teléfono</th>
                                <th class="px-4 py-3 text-left">Correo</th>
                                <th class="px-4 py-3 text-left">Domicilio</th>
                                <th class="px-4 py-3 text-left">Foto</th>
                                <th class="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="tutor in tutores" :key="tutor.id" class="hover:bg-blue-50">
                                <td class="px-4 py-3">{{ tutor.nombre }}</td>
                                <td class="px-4 py-3">{{ tutor.apellido_p }} {{ tutor.apellido_m }}</td>
                                <td class="px-4 py-3">{{ tutor.telefono }}</td>
                                <td class="px-4 py-3">{{ tutor.correo }}</td>
                                <td class="px-4 py-3">{{ tutor.domicilio }}</td>
                                <td class="px-4 py-3">
                                    <template v-if="tutor.foto">
                                        <img
                                            :src="`/storage/${tutor.foto}`"
                                            class="w-16 h-16 rounded border shadow cursor-pointer hover:scale-105 transition"
                                            @click="openImage(tutor.foto)"
                                        />
                                    </template>
                                    <span v-else class="text-gray-400 italic">Sin foto</span>
                                </td>
                                <td class="px-4 py-3 text-center flex justify-center space-x-2">
                                    <Link
                                        :href="route('asignaparentescos.edit', tutor.id)"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs flex items-center"
                                    >
                                        <i class="fa-solid fa-pen mr-1"></i> Editar
                                    </Link>
                                    <button
                                        @click="deleteTutor(tutor.id, tutor.nombre)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal de imagen -->
                <div 
                    v-if="showModal"
                    class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50"
                    @click.self="closeImage"
                >
                    <img :src="selectedImage" class="max-h-[90vh] max-w-[90vw] rounded-xl shadow-lg border-4 border-white" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
