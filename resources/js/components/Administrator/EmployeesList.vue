<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<template>
    <div >
        <div class="row justify-content-end">
            <button type="button" class="btn btn-primary m-3">Registrar personal</button>
        </div>
        <div v-if="visibilityEmployees">
            <table class="table table-striped">
                <thead>
                    <th class="text-center">Documento</th>
                    <th class="text-center">Nombres</th>
                    <th class="text-center">Apellidos</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Teléfono</th>
                    <th class="text-center">Cargo</th>
                    <th class="text-center">Estado</th>
                </thead>
                <tbody>
                    <tr v-for="employee in employeesList">
                        <td class="text-center p-1">{{ employee.document }}</td>
                        <td class="text-center p-1">{{ employee.name }}</td>
                        <td class="text-center p-1">{{ employee.last_name }}</td>
                        <td class="text-center p-1">{{ employee.email }}</td>
                        <td class="text-center p-1">{{ employee.phone }}</td>
                        <td class="text-center p-1">{{ employee.role }}</td>
                        <td class="text-center p-1">{{ employee.status }}</td>
                        <td class="p-1">
                            <button class="btn m-1 p-1 bg-primary" title="Editar" @click="employeeDetail(employee)">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                        <td class="p-1"> 
                            <button class="btn m-1 p-1 bg-danger" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
           <modify-employee v-if="!visibilityEmployees" :employee="selectedEmployee"></modify-employee>    
    </div>
</template>
<script>

    import ModifyEmployee from './ModifyEmployee.vue';

    export default {
        props:[],
        components: {
            'modify-employee' : ModifyEmployee,
        },
        data(){
            return {
                employeesList: [],
                visibilityEmployees : true,
                selectedEmployee : [],
            }
        },
        created(){
            this.getEmployees();
        },
        methods:{
            getEmployees(){
                axios.get('administrator/generalShow').then(res => {
                    console.log('Respuesta del servidor');
                    console.log(res);
                    this.employeesList = res.data.employees;
                }).catch(error => {
                    console.log('Error en axios: ');
                    console.log(error);
                    console.log(error.response);
                })
            },
            employeeDetail(employee){
                console.log("Prueba de boton");
                this.selectedEmployee = employee;
                this.visibilityEmployees = false;
            },
            goToBack(){
                this.visibilityEmployees = true;
                this.selectedEmployee = {};
            }
        },
    }
</script>