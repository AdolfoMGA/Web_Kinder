<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head,useForm} from '@inertiajs/vue3';

const props=defineProps({grados:{type:Object}});
const form= useForm({
    nombre:props.grados.nombre
});
</script>

<template>
    <AppLayout title="Edit Grados">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Grados
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-4 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="$event =>form.patch(route('grados.update',grados))"
                    class="mt-6" space-y-6 max-w-xl>
                        <InputLabel for="nombre" value="Grados"></InputLabel>
                        <TextInput id="nombre" v-model="form.nombre" autofocus required 
                        type="text"
                        class="mt-l block w-full"></TextInput>
                        <InputError :message="form.errors.nombre" class="mt-2"></InputError>
                        <PrimaryButton :disabled="form.processing">
                            <i class="fa-solid fa-save"></i> Save
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>

    </AppLayout>

</template>