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
import { Head,useForm} from '@inertiajs/vue3';
import {nextTick,ref} from 'vue';
import Swal from 'sweetalert2';
import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';

const nameInput=ref(null);
const modal=ref(false);
const title=ref('');
const operation=ref(1);
const id=ref('');

const props = defineProps({
    estudiantes: {type:Object},
    grupos:{type:Object},
    grados:{type:Object},
    docentes:{type:Object}
});

const form = useForm({
    nombre:'',apellido_paterno:'',apellido_materno:'',edad:'',grado_id:'',grupo_id:'',docente_id:''
});

const formPage=useForm({});

const onPageClick =(event)=>{
    formPage.get(route('estudiantes.index',{page:event}));
}

const openModal =(op,nombre,apellido_paterno,apellido_materno,edad,grado,grupo,docente,estudiante)=>{
    modal.value=true;
    nextTick(()=>nameInput.value.focus());
    operation.value=op;
    id.value=estudiante;
    if(op==1){
        title.value='Create Estudiante';
    }
    else{
        title.value='Edit Estudiante';
        form.nombre=nombre;
        form.apellido_paterno=apellido_paterno;
        form.apellido_materno=apellido_materno;
        form.edad=edad;
        form.grado_id=grado;
        form.grupo_id=grupo;
        form.docente_id=docente;
    }
}
const closeModal=()=>{
    modal.value=false;
    form.reset();
}
const save=()=>{
    if(operation.value==1){
        form.post(route('estudiantes.store'),{
            onSuccess:()=>{ok('Estudiantes Created')}
        });
    }
    else{
        form.put(route('estudiantes.update',id.value),{
            onSuccess:()=>{ok('Estudiantes Updated')}
        });
    }
}
const ok=(msj)=>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'success'});
}

const deleteEstudiantes = (id,name) =>{
    const alerta = Swal.mixin({
        buttonsStyling:true
    });
    alerta.fire({
        title:'Are you sure delete'+ name + '?',
        icon:'question',showCancelButton:true,
        confirmButtonText:'<i class="fa-solid fa-check"></i> Yes,delete',
        cancelButtonText:'<i class="fa-solid fa-ban"></i> Cancel'
    }).then((result) =>{
        if(result.isConfirmed){
            form.delete(route('estudiantes.destroy',id),{
                onSuccess:()=>{ok('Estudiantes deleted')}
            });
        }
    });
}
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
                            <th class="px-2 py-2">Docente</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="est,i in estudiantes.data" :key="est.id">
                            <td class="border border-gray-400 px-2 py-2">{{ i+1 }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.nombre }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.apellido_paterno }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.apellido_materno }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.edad }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.grado }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.grupo }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.docente }}</td>
                            <td class="border border-gray-400 px-2 py-2">
                               <WarningButton @click="$event => openModal(2,est.nombre,est.apellido_paterno,est.apellido_materno,est.edad,est.grado_id,est.grupo_id,est.docente_id,est.id)">
                                <i class="fa-solid fa-edit">

                                </i>
                               </WarningButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <DangerButton @click="$event =>deleteEstudiantes(est.id,est.nombre)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="bg-white grid v-screen place-items-center">
                <VueTailwindPagination
                    :current="estudiantes.currentPage" :total="estudiantes.total"
                    :per-page="estudiantes.perPage"
                    @page-changed="$event=> onPageClick($event)"
                ></VueTailwindPagination>
            </div>
        </div>
        <Modal :show="modal" @close="closeModal">
            <h2 class="p-3 text-lg font-medium text-hray-900">{{ title }}</h2>
            
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
                v-model="form.grado_id" class="mt-1 block w-3/4"
                ></SelectInput>
                <InputError :message="form.errors.grado_id" class="mt-2"></InputError>
            </div>

            <div class="p-3">
                <InputLabel for="grupo_id" value="Grupo:"></InputLabel>
                <SelectInput id="grupo_id" :options="grupos"
                v-model="form.grupo_id"  class="mt-1 block w-3/4"
                ></SelectInput>
                <InputError :message="form.errors.grupo_id" class="mt-2"></InputError>
            </div>

            <div class="p-3">
                <InputLabel for="docente_id" value="Docente:"></InputLabel>
                <SelectInput id="docente_id" :options="docentes"
                v-model="form.docente_id"  class="mt-1 block w-3/4"
                ></SelectInput>
                <InputError :message="form.errors.docente_id" class="mt-2"></InputError>
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
    </AppLayout>
</template>
