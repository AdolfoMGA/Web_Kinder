<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';  // Asegúrate de importar Link para el enlace
import Swal from 'sweetalert2';  // Importa SweetAlert2

const props = defineProps({
  user: Object,
  roles: Array
});

const form = useForm({
  name: props.user.name,
  ap: props.user.ap,
  am: props.user.am,
  email: props.user.email,
  password: '',
  password_confirmation: '',
  rol_id: props.user.rol_id
});

const submit = () => {
  form.put(route('usuarios.update', props.user.id), {
    onSuccess: () => {
      // Alerta de éxito
      Swal.fire({
        title: '¡Éxito!',
        text: 'El usuario ha sido actualizado correctamente.',
        icon: 'success',
        confirmButtonText: 'Ok',
      });
    },
    onError: () => {
      // Alerta de error en caso de fallo
      Swal.fire({
        title: 'Error',
        text: 'Hubo un error al actualizar el usuario. Verifique los datos.',
        icon: 'error',
        confirmButtonText: 'Cerrar',
      });
    }
  });
};
</script>

<template>
  <AppLayout title="Editar Usuario">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Editando Usuario: {{ user.name }} {{ user.ap}} {{ user.am}}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <InputLabel for="name" value="Nombre:" />
                  <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Nombre"
                  />
                  <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div>
                  <InputLabel for="ap" value="Apellido Paterno:" />
                  <TextInput
                    id="ap"
                    v-model="form.ap"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Apellido Paterno"
                  />
                  <InputError :message="form.errors.ap" class="mt-2" />
                </div>

                <div>
                  <InputLabel for="am" value="Apellido Materno:" />
                  <TextInput
                    id="am"
                    v-model="form.am"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Apellido Materno"
                  />
                  <InputError :message="form.errors.am" class="mt-2" />
                </div>

                <div>
                  <InputLabel for="email" value="Email:" />
                  <TextInput
                    id="email"
                    v-model.trim="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    placeholder="Correo electrónico"
                  />
                  <InputError :message="form.errors.email" class="mt-2" />
                </div>

                <div>
                  <InputLabel for="password" value="Contraseña:" />
                  <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="Dejar en blanco para no cambiar"
                  />
                  <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div>
                  <InputLabel for="password_confirmation" value="Confirmar Contraseña:" />
                  <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="Confirmar contraseña"
                  />
                  <InputError :message="form.errors.password_confirmation" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                  <InputLabel for="rol_id" value="Rol:" />
                  <select
                    id="rol_id"
                    v-model="form.rol_id"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  >
                    <option value="" disabled>Seleccione un rol</option>
                    <option v-for="role in roles" :key="role.id" :value="role.id">
                      {{ role.descripcion }}
                    </option>
                  </select>
                  <InputError :message="form.errors.rol_id" class="mt-2" />
                </div>
              </div>

              <div class="flex justify-end mt-6 space-x-3">
                <!-- Enlace para cancelar -->
                <Link :href="route('usuarios.index')">
                  <SecondaryButton type="button">
                    Cancelar
                  </SecondaryButton>
                </Link>

                <!-- Botón de guardar -->
                <PrimaryButton type="submit" :disabled="form.processing">
                  <i class="fa-solid fa-save mr-2"></i>
                  Actualizar Usuario
                </PrimaryButton>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
