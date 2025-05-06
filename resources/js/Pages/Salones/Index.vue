<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import WarningButton from '@/Components/WarningButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    asignaciones: {
        type: Array,
        required: true,
        default: () => []
    },
    docente: {  // Nuevo prop para el docente seleccionado
        type: Object,
        default: null
    },
    grados: {
        type: Array,
        default: () => []
    },
    grupos: {
        type: Array,
        default: () => []
    }
});

const form = useForm({
    docente_id: props.docente?.id || '',
    grado_id: '',
    grupo_id: ''
});

const editMode = ref(false);
const currentId = ref(null);

// Computed para saber si hay asignaciones
const hasAssignments = computed(() => props.asignaciones.length > 0);

const submit = () => {
    if (editMode.value) {
        form.put(route('salones.update', currentId.value), {
            onSuccess: () => {
                form.reset();
                editMode.value = false;
                currentId.value = null;
            }
        });
    } else {
        form.post(route('salones.store'), {
            onSuccess: () => form.reset()
        });
    }
};

const editAsignacion = (asignacion) => {
    editMode.value = true;
    currentId.value = asignacion.id;
    form.docente_id = asignacion.docente_id;
    form.grado_id = asignacion.grado_id;
    form.grupo_id = asignacion.grupo_id;
};

const cancelEdit = () => {
    editMode.value = false;
    currentId.value = null;
    form.reset();
    if (props.docente) {
        form.docente_id = props.docente.id;
    }
};

const deleteAsignacion = (id) => {
    if (confirm('¿Estás seguro de eliminar esta asignación?')) {
        useForm({}).delete(route('salones.destroy', id));
    }
};
</script>

<template>
    <AppLayout :title="docente ? `Asignaciones de ${docente.nombre}` : 'Gestión de Salones'">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ docente ? `Asignaciones de ${docente.nombre} ${docente.apellido_p} ${docente.apellido_m}` : 'Gestión de Salones' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Mostrar formulario solo si no hay asignaciones o no hay docente específico -->
                        <form v-if="!hasAssignments && docente" @submit.prevent="submit" class="mb-6">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <!-- Mostrar nombre del docente en lugar de selector -->
                                <div v-if="docente">
                                    <label class="block text-sm font-medium text-gray-700">Docente</label>
                                    <div class="mt-1 p-2 border border-gray-300 rounded-md bg-gray-50">
                                        {{ docente.nombre }} {{ docente.apellido_p }} {{ docente.apellido_m }}
                                    </div>
                                    <input type="hidden" v-model="form.docente_id" />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grado</label>
                                    <select v-model="form.grado_id" required 
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Seleccione un grado</option>
                                        <option v-for="grado in grados" :key="grado.id" :value="grado.id">
                                            {{ grado.nombre }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grupo</label>
                                    <select v-model="form.grupo_id" required 
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Seleccione un grupo</option>
                                        <option v-for="grupo in grupos" :key="grupo.id" :value="grupo.id">
                                            {{ grupo.nombre }}
                                        </option>
                                    </select>
                                </div>

                                <div class="flex items-end">
                                    <button type="submit" 
                                        class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        {{ editMode ? 'Actualizar' : 'Guardar' }}
                                    </button>
                                    <button v-if="editMode" @click="cancelEdit" type="button" 
                                        class="px-4 py-2 ml-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Tabla de asignaciones -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">#</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Docente</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Grado</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Grupo</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(asignacion, index) in asignaciones" :key="asignacion.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ asignacion.docente_nombre || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ asignacion.grado_nombre || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ asignacion.grupo_nombre || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <WarningButton @click="editAsignacion(asignacion)" class="mr-2">
                                                <i class="fas fa-edit"></i>
                                            </WarningButton>
                                            <DangerButton @click="deleteAsignacion(asignacion.id)">
                                                <i class="fas fa-trash"></i>
                                            </DangerButton>
                                        </td>
                                    </tr>
                                    <tr v-if="asignaciones.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No hay asignaciones registradas
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>