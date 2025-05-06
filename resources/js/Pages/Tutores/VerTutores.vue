<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const props = defineProps({
    tutores: Array,
    rol: {
        type: String,
        default: 'administrador' // o cualquier valor por defecto
    },
    estudiante: {
        type: Object,
        default: () => ({ nombre: '', apellido_paterno: '', apellido_materno: '' })
    }
});

const form = useForm({ id: '' });

const deleteTutor = (id, nombre) => {
    const alerta = Swal.mixin({ buttonsStyling: true });

    alerta.fire({
        title: `¿Seguro que quieres eliminar a ${nombre}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Sí, eliminar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('asignaparentescos.destroy', id));
        }
    });
};

// Modal de imagen
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tutores 
                <template v-if="estudiante.nombre">
                    de {{ estudiante.nombre }} {{ estudiante.apellido_paterno }} {{ estudiante.apellido_materno }}
                </template>
            </h2>
        </template>

        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex justify-between w-full max-w-6xl">
                    <SecondaryButton 
                        @click="$inertia.visit(rol === 'docente' 
                            ? route('docente.registrar.estudiante') 
                            : route('estudiantes.index'))">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </SecondaryButton>

                </div>
            </div>

            <div class="bg-white grid v-screen place-items-center">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2">Apellido P</th>
                            <th class="px-4 py-2">Apellido M</th>
                            <th class="px-4 py-2">Teléfono</th>
                            <th class="px-4 py-2">Correo</th>
                            <th class="px-4 py-2">Domicilio</th>
                            <th class="px-4 py-2">Foto</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="tutor in tutores" :key="tutor.id">
                            <td class="border px-4 py-2">{{ tutor.nombre }}</td>
                            <td class="border px-4 py-2">{{ tutor.apellido_p }}</td>
                            <td class="border px-4 py-2">{{ tutor.apellido_m }}</td>
                            <td class="border px-4 py-2">{{ tutor.telefono }}</td>
                            <td class="border px-4 py-2">{{ tutor.correo }}</td>
                            <td class="border px-4 py-2">{{ tutor.domicilio }}</td>
                            <td class="border px-4 py-2">
                                <template v-if="tutor.foto">
                                    <img 
                                        :src="`/storage/${tutor.foto}`" 
                                        alt="Foto del tutor" 
                                        class="h-16 w-16 object-cover rounded border cursor-pointer hover:scale-105 transition"
                                        @click="openImage(tutor.foto)"
                                    />
                                </template>
                                <span v-else class="text-gray-400 italic">Sin foto</span>
                            </td>
                            <td class="border px-4 py-2 flex space-x-2">
                                <Link :href="route('asignaparentescos.edit', tutor.id)"
                                    class="px-2 py-1 bg-yellow-400 text-white rounded text-xs flex items-center">
                                    <i class="fa-solid fa-edit mr-1"></i> Editar
                                </Link>
                                <DangerButton @click="$event => deleteTutor(tutor.id, tutor.nombre)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal de imagen -->
        <div 
            v-if="showModal" 
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50"
            @click.self="closeImage"
        >
            <img :src="selectedImage" class="max-h-[90vh] max-w-[90vw] rounded shadow-lg border-4 border-white" />
        </div>
    </AppLayout>
</template>
