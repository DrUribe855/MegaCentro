<template>
	<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="document">Documento</label>
                                    <input type="number" class="form-control" name="document" v-model="selectedEmployee.document" id="document" placeholder="Numero de documento">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" name="name" v-model="selectedEmployee.name" id="name" placeholder="Nombre" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="lastname">Apellido</label>
                                    <input type="text" class="form-control" v-model="selectedEmployee.last_name" id="lastname" placeholder="Apellido" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Teléfono</label>
                                    <input type="number" class="form-control" v-model="selectedEmployee.phone" id="phone" placeholder="Teléfono" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" v-model="selectedEmployee.email" id="email" placeholder="E-mail">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="role">Cargo</label>
                                    <select name="role" class="custom-select" required>
                                    	<option value="administrator"> Administrador</option>
                                    	<option value="assistant">Auxiliar contable</option>
                                    	<option value="collector">Recolector</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="status">Estado</label>
                                    <select name="status"  class="custom-select" required>
                                    	<option :value="selectedEmployee.status"> {{ selectedEmployee.status }} </option>
                                    	<option :value="oppositeStatusText"> {{ oppositeStatusText }} </option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pass">Contraseña</label>
                                    <input type="password" class="form-control" v-model="selectedEmployee.password" id="pass" placeholder="Contraseña">
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" @click="updateEmployee(); $parent.goToBack();">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:[ 'employee' ],
        data(){
            return {
                selectedEmployee : '',
                selectedStatus : null,
                selectedOppositeStatus: '',

                //Data de datos a modificar
               
                
            }
        },
        created(){
           this.selectedEmployee = this.employee;
           this.selectedStatus = this.employee.status;
        },
        computed: {
        	oppositeStatusText(){
        		return this.selectedStatus == 'Activo' ? 'Inactivo' : 'Activo';
        	}
        },
        methods:{
            statusValueOption(){
            	this.selectedOppositeStatus = this.selectedStatus === 'Activo' ? 'Inactivo' : 'Activo';
            },
            updateEmployee(){
                const id = this.selectedEmployee.id;
                console.log(id);
            	axios.put(`administrator/updateEmployees/${id}`,{
                    name: this.selectedEmployee.name,
                    last_name: this.selectedEmployee.last_name,
                    phone: this.selectedEmployee.phone,
                    email: this.selectedEmployee.email,
                    status: this.selectedStatus,
                    role: this.selectedEmployee.role,
                }).then(res => {
                    console.log('Respuesta del servidor - Edicion de usuarios');
                    console.log(res.data);
                }).catch(error => {
                    console.log('Error en axios: ');
                    console.log(error);
                    console.log(error.response);
                })
            }
        }
    }
</script>