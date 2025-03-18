<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
    nombre: '',
    apellido_paterno: '',
    apellido_materno: '',
    parentesco: ''
});

// Variables para la cámara
const videoRef = ref(null);
const canvasRef = ref(null);
const showCameraModal = ref(false);
const capturedImage = ref(null);
let stream = null;

// Función para abrir la cámara
const openCamera = async () => {
    showCameraModal.value = true;
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        if (videoRef.value) {
            videoRef.value.srcObject = stream;
        }
    } catch (error) {
        console.error("Error al acceder a la cámara:", error);
    }
};

// Función para capturar la imagen
const capturePhoto = () => {
    if (canvasRef.value && videoRef.value) {
        const context = canvasRef.value.getContext('2d');
        canvasRef.value.width = videoRef.value.videoWidth;
        canvasRef.value.height = videoRef.value.videoHeight;
        context.drawImage(videoRef.value, 0, 0, canvasRef.value.width, canvasRef.value.height);
        capturedImage.value = canvasRef.value.toDataURL("image/png");

        // Detener la cámara después de la captura
        stopCamera();
    }
};

// Función para detener la cámara
const stopCamera = () => {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    showCameraModal.value = false;
};
</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Datos de Padre o Tutor</h2>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Nombre</label>
                    <input v-model="form.nombre" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-gray-700">Apellido Paterno</label>
                    <input v-model="form.apellido_paterno" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-gray-700">Apellido Materno</label>
                    <input v-model="form.apellido_materno" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-gray-700">Parentesco</label>
                    <select v-model="form.parentesco" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option value="">Seleccionar</option>
                        <option value="Padre">Padre</option>
                        <option value="Madre">Madre</option>
                        <option value="Tutor">Tutor</option>
                    </select>
                </div>

                <!-- Botón para abrir la cámara -->
                <div class="mt-4">
                    <button 
                        type="button" 
                        @click="openCamera"
                        class="w-full py-2 px-4 bg-gray-200 rounded-lg flex justify-center items-center space-x-2 hover:bg-gray-300 transition"
                    >
                        <img src="/imagenes/user.webp" alt="Imagen" class="h-6 w-6">
                        <span class="text-gray-700">Escanear Rostro</span>
                    </button>
                </div>

                <!-- Vista previa de la foto capturada -->
                <div v-if="capturedImage" class="mt-4">
                    <p class="text-gray-700">Foto capturada:</p>
                    <img :src="capturedImage" class="w-full max-h-64 object-cover rounded-lg border">
                </div>

                <!-- Botones de Regresar y Guardar -->
                <div class="flex space-x-4 mt-4">
                    <a href="registro_alumnos') }}" class="w-full"> 
                        <button type="button" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">Regresar</button>
                    </a>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Guardar</button>
                </div>
            </form>
        </div>

        <!-- Modal para la cámara -->
        <div v-if="showCameraModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-4 rounded-lg shadow-lg max-w-lg w-full">
                <h3 class="text-lg font-bold text-center text-gray-700">Capturar Rostro</h3>
                <video ref="videoRef" autoplay class="w-full mt-2 rounded-lg"></video>
                <canvas ref="canvasRef" class="hidden"></canvas>
                <div class="flex justify-around mt-4">
                    <button 
                        @click="capturePhoto"
                        class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition"
                    >
                        Capturar
                    </button>
                    <button 
                        @click="stopCamera"
                        class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
