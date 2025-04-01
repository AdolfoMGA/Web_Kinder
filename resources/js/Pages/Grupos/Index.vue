<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    grupos: { type: Object }
});
const form = useForm({
    id: ''
});

const deleteGrupo = (id, nombre) => {
    const alerta = Swal.mixin({
        buttonsStyling: true,
    });

    alerta.fire({
        title: 'Are you sure delete ' + nombre + '?',  
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Yes, delete',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('grupos.destroy', id));
        }
    });
}
</script>

<template>
    <AppLayout title="Grupos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Grupos
            </h2>
        </template>
        
        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <Link :href="route('grupos.create')" 
                    class="px-4 py-2 bg-gray-800 text-white border rounded-md font-semibold text-xs flex items-center">
                    <i class="fa-solid fa-plus-circle"></i> Add
                    </Link>
                </div>
            </div>

            <div class="bg-white grid v-screen place-items-center">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-4">#</th>
                            <th class="px-4 py-4">Nombre</th>
                            <th class="px-4 py-4"></th>
                            <th class="px-4 py-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="grupo, i in grupos" :key="grupo.id">
                            <td class="border border-gray-400 px-4 py-4">{{ i+1 }}</td>
                            <td class="border border-gray-400 px-4 py-4">{{ grupo.nombre }}</td>
                            <td class="border border-gray-400 px-4 py-4">
                                <Link :href="route('grupos.edit', grupo.id)" 
                                class="px-4 py-2 bg-yellow-400 text-white border rounded-md font-semibold text-xs flex items-center">
                                <i class="fa-solid fa-edit"></i> Edit
                                </Link>
                            </td>
                            <td class="border border-gray-400 px-4 py-4">
                                <DangerButton @click="$event =>deleteGrupo(grupo.id, grupo.nombre)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
