<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Carousel from '../Components/Carrusel.vue';

const images = [
  '/imagenes/pintura.webp',
  '/imagenes/principal.webp',
  '/imagenes/user.webp',
]

</script>

<template>
    <AppLayout title="Inicio">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ESCUELA MARÍA ELENA MONTES DE OCA
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Carousel :images="images" />
                    
                </div>
            </div>
        </div>

    </AppLayout>

</template>