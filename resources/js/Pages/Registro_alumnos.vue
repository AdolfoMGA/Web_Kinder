<template>
    <AppLayout title="Registro de Alumnos">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Datos de Alumno</h2>
            <form @submit.prevent="submit" class="space-y-4">
                <!-- Campo: Nombre -->
                <div>
                    <label class="block text-gray-700">Nombre</label>
                    <input v-model="form.nombre" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Campo: Apellido Paterno -->
                <div>
                    <label class="block text-gray-700">Apellido Paterno</label>
                    <input v-model="form.apellido_paterno" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Campo: Apellido Materno -->
                <div>
                    <label class="block text-gray-700">Apellido Materno</label>
                    <input v-model="form.apellido_materno" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Campo: Edad -->
                <div>
                    <label class="block text-gray-700">Edad</label>
                    <input v-model="form.edad" type="number" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Campo: Grado -->
                <div>
                    <label class="block text-gray-700">Grado</label>
                    <select v-model="form.grado_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option value="">Seleccionar</option>
                        <option v-for="grado in grados" :key="grado.id" :value="grado.id">{{ grado.nombre }}</option>
                    </select>
                </div>

                <!-- Campo: Grupo -->
                <div>
                    <label class="block text-gray-700">Grupo</label>
                    <select v-model="form.grupo_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option value="">Seleccionar</option>
                        <option v-for="grupo in grupos" :key="grupo.id" :value="grupo.id">{{ grupo.nombre }}</option>
                    </select>
                </div>

                <!-- Campo: Docente -->
                <div>
                    <label class="block text-gray-700">Docente</label>
                    <select v-model="form.docente_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option value="">Seleccionar</option>
                        <option v-for="docente in docentes" :key="docente.id" :value="docente.id">{{ docente.nombre }}</option>
                    </select>
                </div>

                <!-- Botón de envío -->
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Registrar</button>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Obtener los datos de grados, grupos y docentes desde el backend
const { grados, grupos, docentes } = usePage().props;

// Definir el formulario
const form = useForm({
    nombre: '',
    apellido_paterno: '',
    apellido_materno: '',
    edad: '',
    grado_id: null,
    grupo_id: null,
    docente_id: null,
});

// Enviar el formulario al backend
const submit = () => {
    form.post(route('registro.estudiante.store'));
};
</script>

<style scoped>
/* Aquí puedes agregar estilos específicos para esta vista si es necesario */
</style>
