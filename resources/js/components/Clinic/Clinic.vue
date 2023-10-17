<template>

    <div>
        <div class="row m-3">
            <div class="col-9">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#register">
                    Registrar Clinica
                </button>
            </div>
            <div class="col-3">
                <input type="number" class="form-control" style="width: 15em;" v-model="search" @input="searchProducts" placeholder="Buscar Por Documento...">
            </div>
        </div>
        <div class="col-12 m-0 p-0 row">
            <div class="col-12 col-sm-6 col-lg-4 m-0 p-2" v-for="clinic in clinic_list">
                <div class="col card h-100 m-0">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="col-12 card-title mb-0">
                            <h5 class="m-0 me-2 text-dark text-uppercase text-center"><b>{{ clinic.user.document }}</b></h5> 
                                Estado: <small :class="[{ 'text-danger':(clinic.status!='ACTIVO'), 'text-info':(clinic.status=='ACTIVO') } ]" ><b>{{ (clinic.status=='ACTIVO')? 'ACTIVO' : 'INACTIVO' }}</b> </small>
                                <div id="emailHelp" class="form-text">Documento del responsable del consultorio.</div>
                            </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mt-2 mb-3" style="position: relative;">
                            <div class="d-flex flex-column align-items-center">
                                <h2 class="mb-0"> {{ clinic.clinic_number }} </h2>
                                <span>Numero Clinica</span>
                            </div>
                        </div>
                        <div class="col-12 m-0 p-0 text-end">
    						<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#edit" @click="showEdit(clinic)">EDITAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de registrar -->
        <div class="modal fade" id="register" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Registrar Consultorio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="clinicRegister()">
                            <div class="form-row">
                                <label for="numeroConsultorio">Numero Del Consultorio</label>
                                <input type="number" class="form-control" name="numeroConsultorio" id="numeroConsultorio" v-model="clinic_number">
                            </div>
                            <div class="form-row">
                                <label for="numeroConsultorio" style="margin-top: 10px;">Numero de documento</label>
                                <input type="number" class="form-control" name="numeroConsultorio" id="numeroConsultorio" v-model="document_user">
                                <div id="emailHelp" class="form-text">Ingrese el numero de documento del responsable del consultorio.</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de editar -->
        <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Registrar Consultorio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editInfo(clinicEdit)">
                            <div class="form-row">
                                <label for="documentoResponsable">Documento del responsable del consultorio</label>
                                <input type="text" class="form-control" name="documentoResponsable" id="documentoResponsable" readonly v-model="userShow.document">
                            </div>
                            <div class="form-row">
                                <label for="" style="margin-top: 10px;">numero del Consultorio</label>
                                <input type="number" class="form-control" name="" id="" v-model="clinicEdit.clinic_number">
                            </div>
                            <div class="form-row">
                                <label for="" style="margin-top: 10px;">Estado del consultorio</label>
                                <input type="text" class="form-control" name="" id="" v-model="clinicEdit.status">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Editar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

    export default{
		props: [],
		components:{
			
		},
		data(){
			return {
				clinic_list: [],
                clinicNumber: [],

                // Variables de registrar
                clinic_number: null,
                document_user: null,

                // Variables de editar
                clinicEdit: {},
                userShow: {},

                // Variable del buscador
                search: '',
			}
		},
		created(){
			this.getClinic();
		},
		methods: {
			getClinic(){
                axios.get('/clinic/generalShow').then(res => {
                    console.log("Respuesta del servidor");
                    console.log(res.data);
                    this.clinic_list = res.data.clinic;
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            },  

            // Registrar clinica
            clinicRegister(){
                var document_id;
                var clinic_number = this.clinic_number;
                for (let i = 0; i < this.clinic_list.length; i++) {
                    if (this.clinic_list[i].user.document == this.document_user) {
                        document_id = this.clinic_list[i].user_id;
                        break;
                    }
                }
                
                axios.post('/clinic/register', {document_id, clinic_number}).then(res => {
                    console.log("Respuesta del servidor");
                    console.log(res.data);
                    this.clinic_list = res.data.clinic;
                    $('#register').modal('hide');
                    this.clinic_number = "";
                    this.document_user = "";
                    this.search = "";
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            },

            // Traer la informacion para mostrar en el editar
            showEdit(clinicEdit){
                this.clinicEdit = clinicEdit;
                this.userShow = this.clinicEdit.user; 
                console.log(this.userShow);
            },

            // Edtidar informacion de la clinica
            editInfo(clinicEdit){
                console.log(clinicEdit);
                axios.put(`/clinic/update/${clinicEdit.id}`, clinicEdit).then(res => {
                    console.log("Respuesta del servidor");
                    console.log(res.data);
                    this.clinic_list = res.data.clinic;
                    this.search = "";
                    $('#edit').modal('hide');
                }).catch(error => {
                    console.log("Error del servidor");
                    console.log(error);
                    console.log(error.response);
                });
            },

            // Buscador
            searchProducts(){     
                axios.get('/clinic/search', {params: {search: this.search}}).then(res => {
                    console.log("Respuesta del servidor");
                    console.log(res.data);
                    this.clinic_list = res.data;
                }).catch(error => {
                    console.log("Error del servidor");
                    console.log(error);
                    console.log(error.response);
                });
            },
		},
    };
</script>