<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Carousel from '../Components/Carrusel.vue';
import { onMounted } from 'vue';

const images = [
  '/imagenes/pintura.webp',
  '/imagenes/principal.webp',
  '/imagenes/user.webp',
];

// Carga Leaflet dinámicamente
onMounted(() => {
  const leafletCSS = document.createElement('link');
  leafletCSS.rel = 'stylesheet';
  leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
  leafletCSS.crossOrigin = '';
  document.head.appendChild(leafletCSS);

  const leafletJS = document.createElement('script');
  leafletJS.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
  leafletJS.crossOrigin = '';
  leafletJS.onload = initMap;
  document.head.appendChild(leafletJS);
});

function initMap() {
  if (typeof L !== 'undefined') {
    const schoolCoords = [19.195272,-100.133142];
    const map = L.map('map').setView(schoolCoords, 16);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Icono personalizado (opcional)
    const schoolIcon = L.icon({
      iconUrl: '/imagenes/school-marker.png', // Ruta a tu icono
      iconSize: [32, 32],
      iconAnchor: [16, 32],
      popupAnchor: [0, -32]
    });

    L.marker(schoolCoords, { icon: schoolIcon })
      .addTo(map)
      .bindPopup(`
        <div class="text-center">
          <b class="text-lg">Escuela María Elena Montes de Oca</b><br>
          <small class="text-gray-600">Calle Porfirio Díaz Centro</small>
        </div>
      `);
  }
}
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
                    
                    <!-- Contenedor principal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                        <!-- Columna izquierda - Información completa -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h1 class="text-2xl font-bold text-gray-800 mb-4">María Elena Montes de Oca</h1>
                            <span class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded mb-4">
                                Público / Estatal
                            </span>
                            
                            <div class="space-y-3 mb-6">
                                <h3 class="font-semibold text-lg text-gray-700">Niveles educativos:</h3>
                                <ul class="list-disc pl-5 space-y-1">
                                    <li class="text-gray-600">Educación Básica</li>
                                    <li class="text-gray-600">Preescolar</li>
                                    <li class="text-gray-600">Preescolar General</li>
                                </ul>
                            </div>
                            
                            <div class="space-y-2 mb-6">
                                <div class="flex items-start">
                                    <span class="text-gray-500 mr-2">📍</span>
                                    <p class="text-gray-700">
                                        Calle Porfirio Díaz Centro, Valle de Bravo<br>
                                        Valle de Bravo, México. CP. 51200
                                    </p>
                                </div>
                                <p class="italic text-gray-500">Educación Básica en Valle de Bravo</p>
                            </div>
                            
                            <div class="mb-6">
                                <p class="text-gray-700">
                                    <span class="font-semibold">Referencia:</span> A 10 metros del Club de Leones y a cuadra y media de la Presidencia Municipal
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-700"><span class="font-semibold">CCT:</span> 15EJN0293L</p>
                                    <p class="text-gray-700"><span class="font-semibold">Zona:</span> Urbano</p>
                                </div>
                                <div>
                                    <p class="text-gray-700"><span class="font-semibold">Turno:</span> Matutino</p>
                                    <p class="text-gray-700"><span class="font-semibold">Fundación:</span> 11/02/1955</p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Estado: Activo (2024)
                                </span>
                            </div>
                        </div>
                        
                        <!-- Columna derecha - Mapa -->
                        <div class="h-96 rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <div id="map" class="h-full w-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>