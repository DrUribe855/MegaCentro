<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<template>
    <div >
        <div v-if="visibilityEmployees">
            <div class="row justify-content-end">
                <button  class="btn btn-primary m-3" @click="openRegisterModal()">Registrarasd personal</button>
            </div>
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
        <register-modal v-if="visibilityRegister"></register-modal>
        </div>
           <modify-employee v-if="!visibilityEmployees" :employee="selectedEmployee"></modify-employee>    
           
    </div>
</template>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    import 'bootstrap/dist/css/bootstrap.css';
    import 'bootstrap/dist/js/bootstrap.bundle';

</script>

<script>

    import ModifyEmployee from './ModifyEmployee.vue';
    import UserRegisterModal from './UserRegisterModal';

    export default {
        props:[],
        components: {
            'modify-employee' : ModifyEmployee,
            'register-modal' : UserRegisterModal,
        },
        data(){
            return {
                employeesList: [],
                visibilityEmployees : true,
                selectedEmployee : [],
                visibilityRegister: false,
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
            },
            openRegisterModal(){
                console.log('Prueba de boton de registro');
                this.visibilityRegister = true;
                console.log(this.visibilityRegister);
                setTimeout(() => {
                    this.myModal = new bootstrap.Modal(document.getElementById('userRegisterModal'), {
                      keyboard: false,
                      backdrop: 'static',
                    });
                    this.myModal.show();
                },100);
            },
            closeRegisterModal(){
                this.myModal.hide();
                setTimeout(() => {
                    this.visibilityRegister = false;
                },200);
            },
        },
    }
</script>