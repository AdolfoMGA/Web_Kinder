<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head,Link,useForm} from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    estudiantes: {type:Object}
});
const form = useForm({
    id:''
});
constdeleteEstudiante = (id,name) =>{
    const alerta = Swal.mixin({
        buttonsStyling:true
    });
    alerta.fire({
        title:'Are you sure delete'+ name + '?',
        icon:'question',showCancelButton:true,
        confirmButtonText:'<i class="fa-solid fa-check"></i> Yes,delete',
        confirmButtonText:'<i class="fa-solid fa-ban"></i> Cancel'
    }).then((result) =>{
        if(result.isConfirmed){
            form.delete(route('registro.estudiante.create',id));
        }
    });
}
</script>

<template>
    <AppLayout title="Registrar Estudiante">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ESCUELA MARÍA ELENA MONTES DE OCA
            </h2>
        </template>
        
        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <Link :href="route('registro.estudiante.create')" 
                    class="px-4 py-2 bg-gray-800 text-white border rounded-md font-semibold text-xs flex items-center">
                    <i class="fa-solid fa-plus-circle mr-2"></i> Add
                    </Link>
                </div>
            </div>
        </div>

    </AppLayout>

</template>
