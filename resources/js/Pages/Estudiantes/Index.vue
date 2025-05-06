<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import WarningButton from '@/Components/WarningButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import Swal from 'sweetalert2';
import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';

const nameInput = ref(null);
const modal = ref(false);
const modalParentesco = ref(false);
const title = ref('');
const titleParentesco = ref('');
const operation = ref(1);
const id = ref('');
const fotoInput = ref(null);

const props = defineProps({
  estudiantes: { type: Object },
  grupos: { type: Object },
  grados: { type: Object },
  parentescos: { type: Object }
});

const form = useForm({
  nombre: '', 
  apellido_paterno: '', 
  apellido_materno: '', 
  edad: '', 
  grado_id: '', 
  grupo_id: ''
});

const formParentesco = useForm({
  estudiante_id: '',
  parentesco_id: '',
  nombre: '',
  apellido_p: '',
  apellido_m: '',
  telefono: '',
  domicilio: '',
  correo: '',
  foto: null,
  foto_preview: null
});

const formPage = useForm({});

const onPageClick = (event) => {
  formPage.get(route('estudiantes.index', { page: event }));
};

const openModal = (op, nombre, apellido_paterno, apellido_materno, edad, grado, grupo, estudiante) => {
  modal.value = true;
  nextTick(() => nameInput.value.focus());
  operation.value = op;
  id.value = estudiante;
  if (op == 1) {
    title.value = 'Create Estudiante';
  } else {
    title.value = 'Edit Estudiante';
    form.nombre = nombre;
    form.apellido_paterno = apellido_paterno;
    form.apellido_materno = apellido_materno;
    form.edad = edad;
    form.grado_id = grado;
    form.grupo_id = grupo;
  }
};

const openModalParentesco = (estudiante_id) => {
  modalParentesco.value = true;
  formParentesco.estudiante_id = estudiante_id;
  titleParentesco.value = 'Asignar Parentesco';
};

const closeModal = () => {
  modal.value = false;
  form.reset();
};

const closeModalParentesco = () => {
  modalParentesco.value = false;
  formParentesco.reset();
  formParentesco.foto = null;
  formParentesco.foto_preview = null;
};

const save = () => {
  if (operation.value == 1) {
    form.post(route('estudiantes.store'), {
      onSuccess: () => { ok('Estudiantes Created') }
    });
  } else {
    form.put(route('estudiantes.update', id.value), {
      onSuccess: () => { ok('Estudiantes Updated') }
    });
  }
};

const takePhoto = async () => {
  try {
    // Primero intentamos con la API de MediaDevices para mejor experiencia
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      const stream = await navigator.mediaDevices.getUserMedia({
        video: { 
          facingMode: 'user', // 'user' para cámara frontal, 'environment' para trasera
          width: { ideal: 1280 },
          height: { ideal: 720 }
        }
      });
      
      const video = document.createElement('video');
      video.srcObject = stream;
      video.play();
      
      const { value: file } = await Swal.fire({
        title: 'Toma tu foto',
        html: `
          <div class="text-center">
            <video id="camera-preview" autoplay playsinline class="w-full h-auto max-h-96 mx-auto"></video>
            <button id="capture-btn" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-full">
              <i class="fa-solid fa-camera"></i> Capturar Foto
            </button>
          </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Usar esta foto',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
          const preview = document.getElementById('camera-preview');
          preview.srcObject = stream;
          
          document.getElementById('capture-btn').addEventListener('click', () => {
            const canvas = document.createElement('canvas');
            canvas.width = preview.videoWidth;
            canvas.height = preview.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(preview, 0, 0, canvas.width, canvas.height);
            
            canvas.toBlob((blob) => {
              const file = new File([blob], 'foto.png', { type: 'image/png' });
              Swal.close();
              handlePhotoFile(file);
            }, 'image/png', 0.9);
          });
        },
        willClose: () => {
          stream.getTracks().forEach(track => track.stop());
        }
      });
      
      if (file) {
        handlePhotoFile(file);
      }
    } else {
      // Fallback para navegadores que no soportan la API
      const input = document.createElement('input');
      input.type = 'file';
      input.accept = 'image/*';
      input.capture = 'camera';
      input.onchange = (e) => {
        if (e.target.files.length) {
          handlePhotoFile(e.target.files[0]);
        }
      };
      input.click();
    }
  } catch (error) {
    console.error('Error al acceder a la cámara:', error);
    Swal.fire({
      title: 'Error',
      text: 'No se pudo acceder a la cámara. Por favor, sube una imagen manualmente.',
      icon: 'error'
    });
    fotoInput.value.click();
  }
};

const handlePhotoFile = (file) => {
  formParentesco.foto = file;
  const reader = new FileReader();
  reader.onload = (e) => {
    formParentesco.foto_preview = e.target.result;
  };
  reader.readAsDataURL(file);
};

const handleFotoChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    handlePhotoFile(file);
  }
};

const removePhoto = () => {
  formParentesco.foto = null;
  formParentesco.foto_preview = null;
  if (fotoInput.value) {
    fotoInput.value.value = '';
  }
};

const saveParentesco = () => {
  const formData = new FormData();
  
  Object.keys(formParentesco).forEach(key => {
    if (key !== 'errors' && key !== 'hasErrors' && 
        key !== 'processing' && key !== 'recentlySuccessful' &&
        key !== 'foto_preview') {
      formData.append(key, formParentesco[key]);
    }
  });
  
  formParentesco.transform(() => formData)
    .post(route('asignaparentescos.store'), {
      preserveScroll: true,
      onSuccess: () => { 
        okParentesco('Parentesco asignado correctamente');
        closeModalParentesco();
      },
    });
};

const ok = (msj) => {
  form.reset();
  closeModal();
  Swal.fire({ title: msj, icon: 'success' });
};

const okParentesco = (msj) => {
  Swal.fire({ title: msj, icon: 'success' });
};

const deleteEstudiantes = (id, name) => {
  const alerta = Swal.mixin({
    buttonsStyling: true
  });
  alerta.fire({
    title: 'Are you sure delete ' + name + '?',
    icon: 'question', showCancelButton: true,
    confirmButtonText: '<i class="fa-solid fa-check"></i> Yes,delete',
    cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(route('estudiantes.destroy', id), {
        onSuccess: () => { ok('Estudiantes deleted') }
      });
    }
  });
};

const viewTutores = (estudiante_id) => {
  formPage.get(route('tutores.byEstudiante', estudiante_id));
};

</script>

<template>
  <AppLayout title="Estudiantes">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Estudiantes
      </h2>
    </template>
    
    <div class="py-12">
      <div class="bg-white grid v-screen place-items-center">
        <div class="mt-3 mb-3 flex">
          <PrimaryButton @click="$event => openModal(1)">
            <i class="fa-solid fa-plus-circle"></i> Add
          </PrimaryButton>
        </div>
      </div>

      <div class="bg-white grid v-screen place-items-center overflow-x-auto">
        <table class="table-auto border border-gray-400">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-2 py-2">#</th>
              <th class="px-2 py-2">Nombre</th>
              <th class="px-2 py-2">Apellido Paterno</th>
              <th class="px-2 py-2">Apellido Materno</th>
              <th class="px-2 py-2">Edad</th>
              <th class="px-2 py-2">Grado</th>
              <th class="px-2 py-2">Grupo</th>
              <th class="px-2 py-2"></th>
              <th class="px-2 py-2"></th>
              <th class="px-2 py-2"></th>
              <th class="px-2 py-2"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="est, i in estudiantes.data" :key="est.id">
              <td class="border border-gray-400 px-2 py-2">{{ i + 1 }}</td>
              <td class="border border-gray-400 px-2 py-2">{{ est.nombre }}</td>
              <td class="border border-gray-400 px-2 py-2">{{ est.apellido_paterno }}</td>
              <td class="border border-gray-400 px-2 py-2">{{ est.apellido_materno }}</td>
              <td class="border border-gray-400 px-2 py-2">{{ est.edad }}</td>
              <td class="border border-gray-400 px-2 py-2">{{ est.grado }}</td>
              <td class="border border-gray-400 px-2 py-2">{{ est.grupo }}</td>
              <td class="border border-gray-400 px-2 py-2">
                <WarningButton @click="$event => openModal(2, est.nombre, est.apellido_paterno, est.apellido_materno, est.edad, est.grado_id, est.grupo_id, est.id)">
                  <i class="fa-solid fa-edit"></i>
                </WarningButton>
              </td>
              <td class="border border-gray-400 px-2 py-2">
                <DangerButton @click="$event => deleteEstudiantes(est.id, est.nombre)">
                  <i class="fa-solid fa-trash"></i>
                </DangerButton>
              </td>
              <td class="border border-gray-400 px-2 py-2">
                <SecondaryButton @click="$event => openModalParentesco(est.id)">
                  <i class="fa-solid fa-users"></i> Parentesco
                </SecondaryButton>
              </td>

              <td class="border border-gray-400 px-2 py-2">
                <SecondaryButton @click="$event => viewTutores(est.id)">
                  <i class="fa-solid fa-eye"></i> Ver Tutores
                </SecondaryButton>
              </td>

            </tr>
          </tbody>
        </table>
      </div>

      <div class="bg-white grid v-screen place-items-center">
        <VueTailwindPagination
          :current="estudiantes.currentPage" :total="estudiantes.total"
          :per-page="estudiantes.perPage"
          @page-changed="$event => onPageClick($event)"
        ></VueTailwindPagination>
      </div>
    </div>

    <!-- Modal para estudiantes -->
    <Modal :show="modal" @close="closeModal">
      <h2 class="p-3 text-lg font-medium text-gray-900">{{ title }}</h2>
      
      <div class="p-3">
        <InputLabel for="nombre" value="Nombre:"></InputLabel>
        <TextInput id="nombre" ref="nameInput"
          v-model="form.nombre" type="text" class="mt-1 block w-3/4"
          placeholder="Nombre"></TextInput>
        <InputError :message="form.errors.nombre" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="apellido_paterno" value="Apellido Paterno:"></InputLabel>
        <TextInput id="apellido_paterno"
          v-model="form.apellido_paterno" type="text" class="mt-1 block w-3/4"
          placeholder="Apellido Paterno"></TextInput>
        <InputError :message="form.errors.apellido_paterno" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="apellido_materno" value="Apellido Materno:"></InputLabel>
        <TextInput id="apellido_materno"
          v-model="form.apellido_materno" type="text" class="mt-1 block w-3/4"
          placeholder="Apellido Materno"></TextInput>
        <InputError :message="form.errors.apellido_materno" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="edad" value="Edad:"></InputLabel>
        <TextInput id="edad"
          v-model="form.edad" type="text" class="mt-1 block w-3/4"
          placeholder="Edad"></TextInput>
        <InputError :message="form.errors.edad" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="grado_id" value="Grado:"></InputLabel>
        <SelectInput id="grado_id" :options="grados"
          v-model="form.grado_id" class="mt-1 block w-3/4"></SelectInput>
        <InputError :message="form.errors.grado_id" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="grupo_id" value="Grupo:"></InputLabel>
        <SelectInput id="grupo_id" :options="grupos"
          v-model="form.grupo_id" class="mt-1 block w-3/4"></SelectInput>
        <InputError :message="form.errors.grupo_id" class="mt-2"></InputError>
      </div>

      <div class="p-3 mt-6">
        <PrimaryButton :disabled="form.processing" @click="save">
          <i class="fa-solid fa-save"></i> Save
        </PrimaryButton>
      </div>

      <div class="p-3 mt-6 flex justify-end">
        <SecondaryButton class="ml-3" :disabled="form.processing"
          @click="closeModal">
          Cancel
        </SecondaryButton>
      </div>
    </Modal>

    <!-- Modal para parentescos -->
    <Modal :show="modalParentesco" @close="closeModalParentesco">
      <h2 class="p-3 text-lg font-medium text-gray-900">{{ titleParentesco }}</h2>

      <div class="p-3">
        <InputLabel for="parentesco_id" value="Parentesco:"></InputLabel>
        <select 
          id="parentesco_id"
          v-model="formParentesco.parentesco_id" 
          class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        >
          <option value="">Seleccione un parentesco</option>
          <option 
            v-for="parentesco in parentescos" 
            :key="parentesco.value" 
            :value="parentesco.value"
          >
            {{ parentesco.label }}
          </option>
        </select>
        <InputError :message="formParentesco.errors.parentesco_id" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="nombre" value="Nombre:"></InputLabel>
        <TextInput id="nombre"
          v-model="formParentesco.nombre" type="text" class="mt-1 block w-3/4"
          placeholder="Nombre"></TextInput>
        <InputError :message="formParentesco.errors.nombre" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="apellido_p" value="Apellido Paterno:"></InputLabel>
        <TextInput id="apellido_p"
          v-model="formParentesco.apellido_p" type="text" class="mt-1 block w-3/4"
          placeholder="Apellido Paterno"></TextInput>
        <InputError :message="formParentesco.errors.apellido_p" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="apellido_m" value="Apellido Materno:"></InputLabel>
        <TextInput id="apellido_m"
          v-model="formParentesco.apellido_m" type="text" class="mt-1 block w-3/4"
          placeholder="Apellido Materno"></TextInput>
        <InputError :message="formParentesco.errors.apellido_m" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="telefono" value="Teléfono:"></InputLabel>
        <TextInput id="telefono"
          v-model="formParentesco.telefono" type="text" class="mt-1 block w-3/4"
          placeholder="Teléfono"></TextInput>
        <InputError :message="formParentesco.errors.telefono" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="domicilio" value="Domicilio:"></InputLabel>
        <TextInput id="domicilio"
          v-model="formParentesco.domicilio" type="text" class="mt-1 block w-3/4"
          placeholder="Domicilio"></TextInput>
        <InputError :message="formParentesco.errors.domicilio" class="mt-2"></InputError>
      </div>

      <div class="p-3">
        <InputLabel for="correo" value="Correo:"></InputLabel>
        <TextInput id="correo"
          v-model="formParentesco.correo" type="email" class="mt-1 block w-3/4"
          placeholder="Correo electrónico"></TextInput>
        <InputError :message="formParentesco.errors.correo" class="mt-2"></InputError>
      </div>

      <!-- Sección para captura de foto -->
      <div class="p-3">
        <InputLabel value="Fotografía:"></InputLabel>
        <div class="mt-1 flex flex-col items-start">
          <!-- Vista previa de la foto -->
          <div v-if="formParentesco.foto_preview" class="mb-2">
            <img :src="formParentesco.foto_preview" class="h-32 w-32 rounded object-cover border">
          </div>
          
          <!-- Input para subir archivo (oculto) -->
          <input 
            type="file" 
            id="fotoInput"
            ref="fotoInput"
            accept="image/*" 
            class="hidden"
            @change="handleFotoChange"
          >
          
          <!-- Botones para interactuar con la cámara -->
          <div class="flex space-x-2">
            <SecondaryButton @click="takePhoto">
              <i class="fa-solid fa-camera"></i> Tomar Foto
            </SecondaryButton>
            <SecondaryButton v-if="formParentesco.foto_preview" @click="removePhoto" class="bg-red-100 text-red-700">
              <i class="fa-solid fa-trash"></i> Eliminar
            </SecondaryButton>
          </div>
        </div>
        <InputError :message="formParentesco.errors.foto" class="mt-2"></InputError>
      </div>

      <div class="p-3 mt-6">
        <PrimaryButton :disabled="formParentesco.processing" @click="saveParentesco">
          <i class="fa-solid fa-save"></i> Guardar
        </PrimaryButton>
      </div>

      <div class="p-3 mt-6 flex justify-end">
        <SecondaryButton class="ml-3" :disabled="formParentesco.processing"
          @click="closeModalParentesco">
          Cancelar
        </SecondaryButton>
      </div>
    </Modal>
  </AppLayout>
</template>