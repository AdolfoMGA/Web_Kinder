<template>
    <AppLayout title="Registrar Estudiante">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          ESCUELA MARÍA ELENA MONTES DE OCA
        </h2>
      </template>
  
      <div class="py-12">
        <div class="bg-white grid place-items-center">
          <div class="mt-3 mb-3 flex">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 p-6">
              <!-- Repite la card usando un loop -->
              <div
                v-for="grupo in grupos"
                :key="grupo.id"
                class="relative bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md p-4"
              >
                <h2 class="text-xl font-semibold text-gray-700 dark:text-white">
                  {{ grupo.nombre }}
                </h2>
                <button
                  @click="toggleMenu(grupo.id)"
                  class="mt-4 text-blue-600 hover:underline"
                >
                  Ver opciones
                </button>
  
                <ul
                  v-show="grupoAbierto === grupo.id"
                  class="mt-4 space-y-2 transition-all duration-300"
                >
                  <li
                    v-for="opcion in grupo.opciones"
                    :key="opcion"
                  >
                    <a
                      :href="`/${grupo.id}/${opcion.toLowerCase()}`"
                      class="block text-gray-600 dark:text-gray-300 hover:text-blue-500"
                    >
                      {{ opcion }}
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const grupoAbierto = ref(null)
  
  const grupos = [
    { id: 'grupo1', nombre: 'Grupo 1', opciones: ['A', 'B', 'C', 'D'] },
    { id: 'grupo2', nombre: 'Grupo 2', opciones: ['A', 'B', 'C', 'D'] },
    { id: 'grupo3', nombre: 'Grupo 3', opciones: ['A', 'B', 'C', 'D'] },
  ]
  
  function toggleMenu(id) {
    grupoAbierto.value = grupoAbierto.value === id ? null : id
  }
  </script>
  