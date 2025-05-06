<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Swal from 'sweetalert2';
import { ref, watch } from 'vue';

const props = defineProps({
    tutor: Object
});

const form = useForm({
    nombre: props.tutor.nombre,
    apellido_p: props.tutor.apellido_p,
    apellido_m: props.tutor.apellido_m,
    telefono: props.tutor.telefono,
    correo: props.tutor.correo,
    domicilio: props.tutor.domicilio,
    foto: null
});

const previewImage = ref(props.tutor.foto ? `/storage/${props.tutor.foto}` : null);

watch(() => form.foto, (file) => {
    if (file && file instanceof File) {
        const reader = new FileReader();
        reader.onload = e => previewImage.value = e.target.result;
        reader.readAsDataURL(file);
    }
});

const submit = () => {
    form.put(route('asignaparentescos.update', props.tutor.id), {
    preserveScroll: true,
    onSuccess: () => {
        Swal.fire({
            title: 'Tutor actualizado',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
            });
        }
    });

};
</script>

<template>
    <AppLayout title="Editar Tutor">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Tutor
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow-md">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium">Nombre</label>
                            <input v-model="form.nombre" type="text" class="mt-1 w-full border rounded px-3 py-2" />
                            <div v-if="form.errors.nombre" class="text-red-500 text-sm">{{ form.errors.nombre }}</div>
                        </div>

                        <div>
                            <label class="block font-medium">Apellido Paterno</label>
                            <input v-model="form.apellido_p" type="text" class="mt-1 w-full border rounded px-3 py-2" />
                            <div v-if="form.errors.apellido_p" class="text-red-500 text-sm">{{ form.errors.apellido_p }}</div>
                        </div>

                        <div>
                            <label class="block font-medium">Apellido Materno</label>
                            <input v-model="form.apellido_m" type="text" class="mt-1 w-full border rounded px-3 py-2" />
                            <div v-if="form.errors.apellido_m" class="text-red-500 text-sm">{{ form.errors.apellido_m }}</div>
                        </div>

                        <div>
                            <label class="block font-medium">Teléfono</label>
                            <input v-model="form.telefono" type="text" class="mt-1 w-full border rounded px-3 py-2" />
                            <div v-if="form.errors.telefono" class="text-red-500 text-sm">{{ form.errors.telefono }}</div>
                        </div>

                        <div>
                            <label class="block font-medium">Correo</label>
                            <input v-model="form.correo" type="email" class="mt-1 w-full border rounded px-3 py-2" />
                            <div v-if="form.errors.correo" class="text-red-500 text-sm">{{ form.errors.correo }}</div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-medium">Domicilio</label>
                            <textarea v-model="form.domicilio" rows="2" class="mt-1 w-full border rounded px-3 py-2"></textarea>
                            <div v-if="form.errors.domicilio" class="text-red-500 text-sm">{{ form.errors.domicilio }}</div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-medium">Foto</label>
                            <input type="file" @change="e => form.foto = e.target.files[0]" class="mt-1 w-full" />
                            <div v-if="form.errors.foto" class="text-red-500 text-sm">{{ form.errors.foto }}</div>
                        </div>

                        <div class="md:col-span-2" v-if="previewImage">
                            <label class="block font-medium">Vista previa:</label>
                            <img :src="previewImage" class="mt-2 h-40 rounded border shadow-md" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <SecondaryButton @click="$inertia.visit(route('estudiantes.index'))">
                            <i class="fa-solid fa-arrow-left"></i> Cancelar
                        </SecondaryButton>

                        <PrimaryButton :disabled="form.processing">
                            <i class="fa-solid fa-save mr-2"></i> Guardar Cambios
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
