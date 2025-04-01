<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';

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

                    <div v-for="(est, key) in estudiantes">
                            Nombre: {{ est.nombre }}
                            Apellido Paterno: {{ est.apellido_paterno }}
                            Apellido Materno: {{ est.apellido_materno }}
                            Edad: {{ est.edad }}
                            Grado: {{ est.grado_id }}
                            Grupo: {{ est.grupo_id }}
                            Docente: {{ est.docente_id }}

                            <button @click="editarestudiante(key)">Editar</button>
                            <button @click="eliminarestudiante(est.id)">Eliminar</button>
                    </div>

                    <div>
                        <input type="text" v-model="form.nombre">
                        <input type="text" v-model="form.apellido_paterno">
                        <input type="text" v-model="form.apellido_materno">
                        <input type="number" v-model="form.edad">
                        <input type="number" v-model="form.grado_id">
                        <input type="number" v-model="form.grupo_id">
                        <input type="number" v-model="form.docente_id">

                        <button @click="guardarestudiante">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>

</template>

<script>
export default {
    props: {
        estudiantes: {
            type: Array,  
            required: true  
        }
    },

    mounted() {
        console.log(this.estudiantes); 
    },
    data(){
        return{
            form:{
                id:'',
                nombre:'',
                apellido_paterno:'',
                apellido_materno:'',
                edad: '',
                grado_id:'',
                grupo_id:'',
                docente_id:'',
            }
        }
    },
    methods:{
        guardarestudiante()
        {
            this.$inertia.post('/guardarestudiante',this.form);
        },
        eliminarestudiante(id)
        {
            this.$inertia.post('/eliminarestudiante',{id:id});
        },
        editarestudiante(key)
        {
            const est = this.estudiantes[key]; 
            this.form.id = est.id;
            this.form.nombre = est.nombre;
            this.form.apellido_paterno = est.apellido_paterno;
            this.form.apellido_materno = est.apellido_materno;
            this.form.edad = est.edad;
            this.form.grado_id = est.grado_id;
            this.form.grupo_id = est.grupo_id;
            this.form.docente_id = est.docente_id;

            this.$inertia.post('/guardarestudiante', this.form);
        },

        agregar()
        {
            this.form.id='';
            this.form.nombre='';
            this.form.apellido_paterno='',
            this.form.apellido_materno='',
            this.form.edad='',
            this.form.grado_id='';
            this.form.grupo_id='';
            this.form.docente_id='';

        }
    }
}
</script>