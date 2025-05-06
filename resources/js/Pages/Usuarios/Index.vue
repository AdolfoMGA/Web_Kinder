<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import WarningButton from '@/Components/WarningButton.vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';

const props = defineProps({
  users: Object,
  roles: Array
});

const form = useForm({}); // Creas una única instancia del formulario

const deleteUser = (id, name) => {
  Swal.fire({
    title: `¿Eliminar a ${name}?`,
    text: "Esta acción no se puede deshacer",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(route('usuarios.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire('¡Eliminado!', 'El usuario ha sido eliminado.', 'success');
          // No necesitas modificar manualmente los datos, Inertia se encargará
        },
        onError: () => {
          Swal.fire('Error', 'Hubo un problema al eliminar el usuario.', 'error');
        }
      });
    }
  });
};
</script>

<template>
  <AppLayout title="Usuarios">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Gestión de Usuarios
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white">
            <div class="mb-4 flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Listado de Usuarios</h3>
              
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="!users.data.length">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No hay usuarios registrados
                    </td>
                  </tr>
                  <tr v-for="(user, index) in users.data" :key="user.id">
                    <td class="px-6 py-4 text-sm text-gray-500">
                      {{ (users.current_page - 1) * users.per_page + index + 1 }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                      {{ user.name }} {{ user.ap }} {{ user.am }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                      {{ user.email }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                      {{ user.rol?.descripcion || 'Sin rol' }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                      <div class="flex space-x-2">
                        <Link :href="route('usuarios.edit', user.id)">
                          <WarningButton>
                            <i class="fa-solid fa-pencil"></i>
                          </WarningButton>
                        </Link>
                        <DangerButton @click="deleteUser(user.id, user.name)" :disabled="form.processing">
                          <i class="fa-solid fa-trash"></i>
                        </DangerButton>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mt-4" v-if="users.last_page > 1">
              <VueTailwindPagination
                :current="users.current_page"
                :total="users.total"
                :per-page="users.per_page"
                @page-changed="(page) => $inertia.get(route('usuarios.index', { page }))"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>