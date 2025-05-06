<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import WarningButton from '@/Components/WarningButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import Swal from 'sweetalert2';
import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';

const nameInput = ref(null);
const modal = ref(false);
const title = ref('');
const operation = ref(1);
const id = ref('');

const props = defineProps({
    docentes: Object,
});

const form = useForm({
    nombre: '',
    apellido_p: '',
    apellido_m: '',
});

const formPage = useForm({});

const onPageClick = (event) => {
    formPage.get(route('docentes.index', { page: event }));
};

const openModal = (op, nombre, apellido_p, apellido_m, docente) => {
    modal.value = true;
    nextTick(() => nameInput.value.focus());
    operation.value = op;
    id.value = docente;
    if (op == 1) {
        title.value = 'Crear Docente';
    } else {
        title.value = 'Editar Docente';
        form.nombre = nombre;
        form.apellido_p = apellido_p;
        form.apellido_m = apellido_m;
    }
};

const closeModal = () => {
    modal.value = false;
    form.reset();
};

const save = () => {
    if (operation.value == 1) {
        form.post(route('docentes.store'), {
            onSuccess: () => { ok('Docente creado'); }
        });
    } else {
        form.put(route('docentes.update', id.value), {
            onSuccess: () => { ok('Docente actualizado'); }
        });
    }
};

const deleteDocente = (id, name) => {
    Swal.fire({
        title: `¿Eliminar a ${name}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('docentes.destroy', id), {
                onSuccess: () => { ok('Docente eliminado'); }
            });
        }
    });
};

const ok = (msj) => {
    form.reset();
    closeModal();
    Swal.fire({ title: msj, icon: 'success' });
};
</script>

<template>
    <AppLayout title="Docentes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Docentes
            </h2>
        </template>

        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                
            </div>

            <div class="bg-white grid v-screen place-items-center overflow-x-auto">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Apellido Paterno</th>
                            <th class="px-2 py-2">Apellido Materno</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="docente, i in docentes.data" :key="docente.id">
                            <td class="border border-gray-400 px-2 py-2">{{ i+1 }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ docente.nombre }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ docente.apellido_p }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ docente.apellido_m }}</td>
                            <td class="border border-gray-400 px-2 py-2">
                               <WarningButton @click="$event => openModal(2, docente.nombre, docente.apellido_p, docente.apellido_m, docente.id)">
                                <i class="fa-solid fa-edit"></i>
                               </WarningButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <DangerButton @click="$event => deleteDocente(docente.id, docente.nombre)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <Link :href="route('salones.index', { docente_id: docente.id })" class="inline-block">
                                    <PrimaryButton>
                                        <i class="fa-solid fa-chalkboard-teacher"></i> Asignar
                                    </PrimaryButton>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-white grid v-screen place-items-center">
                <VueTailwindPagination
                    :current="docentes.currentPage" :total="docentes.total"
                    :per-page="docentes.perPage"
                    @page-changed="$event=> onPageClick($event)"
                />
            </div>
        </div>

        <!-- Modal Crear/Editar Docente -->
        <Modal :show="modal" @close="closeModal">
            <h2 class="p-3 text-lg font-medium text-gray-900">{{ title }}</h2>

            <div class="p-3">
                <InputLabel for="nombre" value="Nombre:" />
                <TextInput id="nombre" ref="nameInput"
                    v-model="form.nombre" type="text" class="mt-1 block w-3/4"
                    placeholder="Nombre" />
                <InputError :message="form.errors.nombre" class="mt-2" />
            </div>

            <div class="p-3">
                <InputLabel for="apellido_p" value="Apellido Paterno:" />
                <TextInput id="apellido_p"
                    v-model="form.apellido_p" type="text" class="mt-1 block w-3/4"
                    placeholder="Apellido Paterno" />
                <InputError :message="form.errors.apellido_p" class="mt-2" />
            </div>

            <div class="p-3">
                <InputLabel for="apellido_m" value="Apellido Materno:" />
                <TextInput id="apellido_m"
                    v-model="form.apellido_m" type="text" class="mt-1 block w-3/4"
                    placeholder="Apellido Materno" />
                <InputError :message="form.errors.apellido_m" class="mt-2" />
            </div>

            <div class="p-3 mt-6">
                <PrimaryButton :disabled="form.processing" @click="save">
                    <i class="fa-solid fa-save"></i> Guardar
                </PrimaryButton>
            </div>

            <div class="p-3 mt-6 flex justify-end">
                <SecondaryButton class="ml-3" :disabled="form.processing"
                    @click="closeModal">
                    Cancelar
                </SecondaryButton>
            </div>
        </Modal>
    </AppLayout>
</template>