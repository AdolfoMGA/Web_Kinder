<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <AppLayout title="Registro_alumnos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hola desde Registro alumnos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Mandar traer la vista referente a registro de alumnos -->
                </div>
            </div>
        </div>

    </AppLayout>

</template>
