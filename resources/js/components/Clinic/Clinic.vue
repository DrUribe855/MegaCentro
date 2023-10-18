<template>
    <div>
        <div class="row m-3 mt-2">
            <div class="col-md-7 row mt-2">
                <div class="col-9">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#register">
                        Registrar Clínica
                    </button>
                </div>
                <div v-if="showButton == true" class="col-3">
                    <button type="button" :class="buttonClass" @click="optionButton()">
                        {{ buttonText }}
                    </button>
                </div>
            </div>
            <div class="col-md-5 mt-2">
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-select" aria-label="Default select example" v-model="optionSearch">
                            <option value="status">Estado</option>
                            <option value="documentNumber">Número de Documento</option>
                            <option value="clinicNumber">Número de Consultorio</option>
                        </select>
                        <div id="emailHelp" class="form-text">Debe elegir la opción de búsqueda.</div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" v-model="search" @input="searchProducts" placeholder="Buscar por Documento...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Div de loading -->
        <div v-if="spinner == false" style="padding-top:100.000%;position:relative; margin-left: 30%;">
            <img width="50%" height="30%" style='position:absolute;top:0;left:0;' frameBorder="0" allowFullScreen src="https://i.gifer.com/7kRE.gif" alt="">
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"  @click="clear()"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="clinicRegister()">
                            <div class="form-row">
                                <label for="">Numero Del Consultorio</label>
                                <input type="number" class="form-control" name="" id="" v-model="clinic_number">
                            </div>
                            <div class="form-row">
                                <label for="" style="margin-top: 10px;">Numero de documento</label>
                                <input type="number" class="form-control" name="" id="" v-model="document_user">
                                <div id="emailHelp" class="form-text">Ingrese el numero de documento del responsable del consultorio.</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clear()">Cancelar</button>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="clearEdit()"></button>
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
                                <select class="form-select" aria-label="Default select example" v-model="clinicEdit.status">
                                    <option selected>{{ clinicEdit.status }}</option>
                                    <option :value="(clinicEdit.status=='ACTIVO')? 'INACTIVO' : 'ACTIVO'">{{ (clinicEdit.status=='ACTIVO')? 'INACTIVO' : 'ACTIVO' }}</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearEdit()">Cancelar</button>
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
import Swal from 'sweetalert2';


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
                optionSearch: '',
                buttonText: '',
                buttonClass: '',
                showButton: false,
                // Varible de estado del modal edit
                status: '',
                // Variable del Spinner
                spinner: true,
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
                    this.clear();
                    $('#register').modal('hide');
                    this.sweetAlert('Se registro con exito el nuevo consultorio');
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                    this.sweetAlertError('Parece que estas intentando registrar un consultorio existente o algunos campos estan vacíos!');
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
                    this.sweetAlert('Se modifico la informacion del consultorio con exito');
                }).catch(error => {
                    console.log("Error del servidor");
                    console.log(error);
                    console.log(error.response);
                    this.sweetAlertError('Parece que estas intentando modificar un consultorio, es posible que algunos campos esten vacíos o intentas modificar el numero del consultorio pero este ya exite!');
                });
            },

            // Buscador
            searchProducts(){ 
                if (this.search != '' && this.optionSearch != '') {
                    axios.get('/clinic/search', {params: {search: this.search, optionSearch: this.optionSearch}}).then(res => {
                        console.log("Respuesta del servidor");
                        console.log(res.data);
                        this.clinic_list = res.data;
                        this.changeButton();
                    }).catch(error => {
                        console.log("Error del servidor");
                        console.log(error);
                        console.log(error.response);
                        this.sweetAlertError('Parece que algo salio mal con la busqueda!');
                    });
                    this.showButton = true;
                }else if (this.search == ''){
                    this.showButton = false;
                    this.spinner = true;
                    this.getClinic();
                }else if (this.optionSearch == '') {
                    this.sweetAlertError('El campo de opcion de busqueda esta vacío!');
                }
            },

            // Lispiar los input y ocultar botones y spinner
            clear(){
                this.clinic_number = "";
                this.document_user = "";
                this.search = "";
                this.showButton = false;
                this.spinner = true;
            },

            // Cambia los estilos de los boton y oculta o muestra el spinner
            changeButton(){
                if (this.clinic_list.length == 0) {
                    this.spinner = false;
                }else{
                    this.spinner = true;
                }

                if (this.spinner == true) {
                    this.buttonText = 'Regresar';
                    this.buttonClass = 'btn btn-primary';
                }else{
                    this.buttonText = 'Cancelar busqueda';
                    this.buttonClass = 'btn btn-danger';
                }
            },

            // oculta botones y spinner y muestra la lista de consultorios
            optionButton(){
                this.search = '';
                this.showButton = false;
                this.spinner = true;
                this.getClinic();
            },

            // Alerta de error
            sweetAlertError(textSwetAlert){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: textSwetAlert,
                })
            },

            // Alerta de exito
            sweetAlert(textSwetAlert){
                Swal.fire(
                    'OK!',
                    textSwetAlert,
                    'success'
                )
            },

            // Limpia los input del editar y muestra lista de clinicas original
            clearEdit(){
                this.clinic_number = "";
                this.document_user = "";
                this.getClinic();
            }
		},
    };
</script>