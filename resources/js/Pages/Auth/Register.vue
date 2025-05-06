<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const roles = ref([]);  // Definir roles como un ref
const form = useForm({
    name: '',
    ap: '',
    am: '',
    rol: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

// Realiza la solicitud para obtener los roles
onMounted(async () => {
    try {
        const response = await axios.get('/roles');  // Hacemos la solicitud GET
        roles.value = response.data;  // Asignamos los roles a la variable `roles`
    } catch (error) {
        console.error("Error al obtener los roles:", error);
    }
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nombre" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="Nombre"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="ap" value="Apellido Paterno" />
                <TextInput
                    id="ap"
                    v-model="form.ap"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="ap"
                />
                <InputError class="mt-2" :message="form.errors.ap" />
            </div>

            <div>
                <InputLabel for="am" value="Apellido Materno" />
                <TextInput
                    id="am"
                    v-model="form.am"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="am"
                />
                <InputError class="mt-2" :message="form.errors.am" />
            </div>

            <!-- Selector de rol -->
            <div class="mt-4">
                <InputLabel for="rol" value="Rol" />
                <select v-model="form.rol" id="rol" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="" disabled>Selecciona un rol</option>
                    <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                        {{ rol.descripcion }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.rol" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Correo" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Contraseña" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
                        <div class="ml-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Ya estas registrado?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registrar
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
