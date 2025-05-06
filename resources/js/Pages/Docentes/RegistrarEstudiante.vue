<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
  grados: Array,
  grupos: Array,
  estudiantesFiltrados: {
    type: Array,
    default: () => []
  },
  filtros: Object,
  errors: Object,
  isAdmin: {
    type: Boolean,
    default: false
  }
});

const grupoAbierto = ref(null);
const error = ref(props.errors?.error || null);
const cargando = ref(false);

function toggleMenu(id) {
  grupoAbierto.value = grupoAbierto.value === id ? null : id;
}

function obtenerEstudiantes(grado_id, grupo_id) {
  router.get(route('estudiantes.byGradeGroup', {
    grado: grado_id,
    grupo: grupo_id
  }), {}, {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      error.value = null;
    },
    onError: (errors) => {
      error.value = errors.error || 'Error al cargar estudiantes';
    }
  });
}

async function manejarRecogida(estudiante) {
  try {
    const result = await Swal.fire({
      title: `¿Recoger a ${estudiante.nombre}?`,
      text: "Confirma que deseas iniciar el proceso de recogida",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, recoger',
      cancelButtonText: 'Cancelar',
      allowOutsideClick: false
    });

    if (result.isConfirmed) {
      cargando.value = true;
      
      const response = await axios.post(route('facial.run'), {
        nombre: estudiante.nombre,
        estudiante_id: estudiante.id 
      }, {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });
      
      if (response.data.success) {
        await Swal.fire({
          title: '¡Proceso iniciado!',
          text: response.data.message || 'El sistema está procesando la solicitud',
          icon: 'success',
          timer: 3000,
          timerProgressBar: true,
          showConfirmButton: false
        });
      } else {
        throw new Error(response.data.message || 'Error en el servidor');
      }
    }
  } catch (error) {
    console.error('Error al recoger estudiante:', error);
    
    let errorMessage = 'Ocurrió un error al procesar la solicitud';
    if (error.response) {
      // El servidor respondió con un código de estado fuera del rango 2xx
      errorMessage = error.response.data?.message || 
                    error.response.statusText || 
                    `Error ${error.response.status}`;
    } else if (error.request) {
      // La petición fue hecha pero no se recibió respuesta
      errorMessage = 'No se recibió respuesta del servidor';
    }
    
    await Swal.fire({
      title: 'Error',
      text: errorMessage,
      icon: 'error',
      confirmButtonText: 'Entendido'
    });
  } finally {
    cargando.value = false;
  }
}
</script>

<template>
  <AppLayout title="Registrar Estudiante">
    <template #header>
      <h2 class="font-bold text-2xl text-white leading-tight text-center py-4 bg-gradient-to-r from-cyan-500 to-blue-500 shadow-md rounded-b-lg">
        ESCUELA MARÍA ELENA MONTES DE OCA
      </h2>
    </template>

    <div class="py-12 min-h-screen bg-gradient-to-br from-cyan-100 via-blue-100 to-white relative overflow-hidden">
      <div class="max-w-6xl mx-auto px-4 relative z-10">
        <!-- Mostrar error si existe -->
        <div v-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
          <p>{{ error }}</p>
        </div>

        <!-- Si hay grupos -->
        <div v-if="grupos.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="grado in grados" 
            :key="grado.id"
            class="bg-white border border-blue-200 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300 cursor-pointer"
          >
            <div @click="toggleMenu(grado.id)" class="flex justify-between items-center">
              <h2 class="text-2xl font-bold text-blue-700">Grado {{ grado.nombre }}</h2>
              <svg 
                :class="{'rotate-180': grupoAbierto === grado.id}" 
                class="w-5 h-5 text-blue-500 transition-transform duration-200" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>

            <!-- Lista de grupos -->
            <div v-show="grupoAbierto === grado.id" class="mt-4 space-y-2">
              <div
                v-for="grupo in grupos"
                :key="grupo.id"
                @click.stop="obtenerEstudiantes(grado.id, grupo.id)"
                class="px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200 cursor-pointer flex items-center"
              >
                <span class="font-medium text-gray-800">Grupo {{ grupo.nombre }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Si no hay grupos -->
        <div v-else class="text-center py-12 text-gray-500 text-lg">
          No tienes grupos asignados.
        </div>

        <!-- Sección de estudiantes -->
        <div v-if="filtros" class="mt-12 bg-white rounded-xl shadow-md p-6">
          <h3 class="text-xl font-bold text-blue-700 mb-4 border-b pb-2">
            Estudiantes del Grado {{ filtros.grado_nombre }} - Grupo {{ filtros.grupo_nombre }}
            <span v-if="estudiantesFiltrados.length > 0" class="text-sm font-normal text-gray-500 ml-2">
              ({{ estudiantesFiltrados.length }} estudiantes)
            </span>
          </h3>

          <!-- Lista vacía -->
          <div v-if="estudiantesFiltrados.length === 0" class="text-center py-8 text-gray-500">
            No hay estudiantes registrados para este grado y grupo
          </div>

          <!-- Tabla de estudiantes -->
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellidos</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edad</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                  v-for="estudiante in estudiantesFiltrados" 
                  :key="estudiante.id" 
                  class="hover:bg-gray-50 transition-colors duration-150"
                  :class="{'cursor-pointer': !cargando, 'opacity-70': cargando}"
                  @click="!cargando && manejarRecogida(estudiante)"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ estudiante.nombre }}
                    <span v-if="cargando" class="ml-2 inline-block animate-spin">↻</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ estudiante.apellido_paterno }} {{ estudiante.apellido_materno }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ estudiante.edad }} años
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    <Link
                      :href="route('tutores.byEstudiante', estudiante.id)"
                      @click.stop
                      class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-xs"
                    >
                      Ver Tutores
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>