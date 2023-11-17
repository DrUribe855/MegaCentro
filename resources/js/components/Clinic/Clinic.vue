<template>
    <div>
        <v-app>
            <v-main>
                <v-card>
                    <v-card-title>
                        {{ infoUser.document }}
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Buscar"
                            single-line
                            hide-details
                        ></v-text-field>
                    </v-card-title>
                    <!-- Tabla de personal del consultorio -->
                    <v-dialog
                        v-model="dialog"
                        max-width="650px"
                    >
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">{{ titlePersonnel }}</span>
                                <v-spacer></v-spacer>
                                <v-btn
                                    class="mx-1"
                                    x-small
                                    fab
                                    dark
                                    color="primary"
                                    @click="showUser(titlePersonnel)"
                                    >
                                    <v-icon dark>
                                        mdi-plus
                                    </v-icon>
                                </v-btn>
                            </v-card-title>

                            <v-card-text>
                                <v-card 
                                >
                                    <v-card-title>
                                        <v-text-field
                                            v-model="searchPersonnel"
                                            append-icon="mdi-magnify"
                                            label="Search"
                                            single-line
                                            hide-details
                                        ></v-text-field>
                                    </v-card-title>
                                    <v-data-table
                                        :headers="infoClinic"
                                        :items="clinicPersonner"
                                        :search="searchPersonnel"
                                    >
                                        <template v-slot:item.actions="{ item }">
                                            <v-btn
                                                class="mx-1"
                                                x-small
                                                fab
                                                dark
                                                color="red"
                                                @click="remove(item)"
                                                >
                                                <v-icon dark>
                                                    mdi-minus
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-card-text>
                
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="close"
                                    >
                                    Cerrar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <!-- Agregar personal -->
                    <v-dialog
                      v-model="showAdd"
                      max-width="630px"
                    >
                      <v-card>
                        <v-card-title>
                          <span class="text-h5">Agregar {{ title }} al consultorio  {{ dataClinic.clinic_number }}</span>
                        </v-card-title>
                        <v-card-text>
                          <v-container>
                            <v-row>
                              <v-col
                                cols="12"
                                sm="6"
                                md="4"
                              >
                              <v-select
                                v-model="selectedUser"
                                :items="user"
                                :menu-props="{ top: false, offsetY: true }"
                                :label="textLable"
                                item-text="document"
                                item-value="id"
                              ></v-select>
                              </v-col>
                            </v-row>
                          </v-container>
                        </v-card-text>

                        <v-card-actions>
                        <v-spacer></v-spacer>
                          <v-btn
                            color="blue darken-1"
                            text
                            @click="closeUser()"
                          >
                            Cancelar
                          </v-btn>
                          <v-btn
                            color="blue darken-1"
                            text
                            @click="saveUser()"
                          >
                            Agregar
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>

                    <!-- Editar -->
                    <v-dialog
                        v-model="dialogEdit"
                        max-width="550px"
                        >
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">Editar</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                type="number"
                                                v-model="editedItem.clinic_number"
                                                label="Numero Clinica"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-select 
                                                v-model="editedItem.tower_id"
                                                :items="itemsTower"
                                                :label="`Numero Torre ${editedItem.tower_id}`"
                                                outline
                                                border="false"
                                            ></v-select>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-select
                                                v-model="editedItem.status"
                                                :items="items"
                                                :label="editedItem.status"
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="close"
                            >
                                Cancelar
                            </v-btn>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="save"
                            >
                                Guardar
                            </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Tabla principal -->
                    <v-data-table
                        :headers="headers"
                        :items="desserts"
                        :search="search"
                        >
                        <template v-slot:item.actions="{ item }">
                            <v-btn
                                color="success"
                                fab
                                x-small
                                dark
                                @click="infoPersonnel(item, 1)">
                                <v-icon>mdi-domain</v-icon>
                            </v-btn>
                            <v-btn
                                color="warning"
                                fab
                                x-small
                                dark
                                @click="infoPersonnel(item, 2)">
                                <v-icon>mdi-account-circle</v-icon>
                            </v-btn>
                            <v-btn
                                class="mx-2"
                                fab
                                dark
                                x-small
                                color="primary"
                                @click="showInfoEdit(item)"
                                >
                                <v-icon dark>
                                    mdi-pencil
                                </v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>
                    <v-card-actions class="d-flex justify-left align-left">
                        <v-btn
                            depressed
                            color="primary"
                            @click="$parent.goToBack()"
                        >
                            volver
                        </v-btn> 
                    </v-card-actions> 
                </v-card>
            </v-main>
        </v-app>
    </div>
</template>

<script>
    // import Swal from 'sweetalert2';
    export default {
        props: ['clinic'],
        data () {
            return {
                title: '',
                items: ['OCUPADO', 'DESOCUPADO'],
                itemsTower: ['1','2'],
                dialog: false,
                dialogEdit: false,
                showAdd: false,
                search: '',
                searchPersonnel: '',
                // Tabla inicio
                headers: [
                    { text: 'N Consultorio', value: 'clinic_number' },
                    { text: 'N Torre', value: 'tower_id' },
                    { text: 'Estado', value: 'status' },
                    { text: '', value: 'actions', sortable: false },
                ],
                desserts: [
                    
                ],
                editedIndex: -1,
                editedItem: {
                    clinic_number: '',
                    tower_id: '',
                    status: '',
                },
                defaultItem: {
                    clinic_number: '',
                    tower_id: '',
                    status: '',
                },
                infoUser: '',
                // Tabla dueños
                infoClinic: [
                    { text: 'Documento', value: 'document' },
                    { text: 'Nombre', value: 'name' },
                    { text: 'Telefono', value: 'phone' },
                    { text: 'Correo', value: 'email' },
                    { text: '', value: 'actions', sortable: false },
                ],
                clinicPersonner: [],

                dataClinic: {
                    clinic_number: ''
                },

                user: [],
                selectedUser: null,
                textLable: '',

                titlePersonnel: '',

            } 
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
        },

        created(){
            this.infoUser = this.clinic
            console.log("infoUser ",this.infoUser);
            this.initialize()
        },

        methods: {
            initialize(){
                axios.post(`/clinic/infoClinic/${this.infoUser.id}`).then(res => {
                    console.log("Respuesta del servidor");
                    console.log("Datos de ventana ",res.data);
                    this.desserts = res.data.clinic;
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            },

            infoPersonnel(item, option){
                axios.get(`/clinic/consultation/${item.id}/${option}`).then(res => {
                    console.log("Respuesta del servidor");
                    console.log("Datos de tabla ",res.data);
                    console.log(res.data.infoClinic.filter(item => item.role === 'Dueno'));
                    if (option == 1) {
                        this.titlePersonnel = 'Dueño'
                        this.clinicPersonner = res.data.infoClinic.filter(item => item.role_id === 5)   
                    }else{
                        this.titlePersonnel = 'Recolector'
                        this.clinicPersonner = res.data.infoClinic.filter(item => item.role_id === 3)
                    }
                    this.dialog = true
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
                this.dataClinic = item
            },

            showInfoEdit(item){
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
                console.log("03 ",item);
                this.dialogEdit = true
            },

            close () {
                this.dialog = false
                this.dialogEdit = false
            },

            save () {
                var validate = true;
                if (this.editedItem.clinic_number == "") {
                    this.alertFalse('Parece que el campo numero consultorio esta vacío');
                    validate = false 
                }else if(this.editedItem.tower_id == ""){
                    this.alertFalse('Parece que el campo numero torre esta vacío');
                    validate = false
                }else if (this.editedItem.status == "") {
                    this.alertFalse('Parece que el campo estado esta vacío');
                    validate = false
                }
                if (validate) {
                    console.log(this.editedItem);
                    swal({
                        title: "Quiere editar este consultorio?",
                        text: "Los cambios se aplicaran permanentemente!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            axios.put(`/clinic/update/${this.editedItem.id}`, {data: this.editedItem}).then(res => {
                                console.log("Respuesta del servidor");
                                console.log(res.data);
                                this.initialize();
                                this.dialogEdit= false
                                swal(
                                    'Cambio Exitoso!',
                                    'Se edito correctamente',
                                    'success'
                                )
                            }).catch(error => {
                                console.log("Error en servidor");
                                console.log(error);
                                console.log(error.response);
                            });
                        }
                    })
                    this.close()   
                }
            },

            showUser(title){
                axios.get('/clinic/consultationUser').then(res => {
                    console.log("Respuesta del servidor");
                    console.log("datos ",res.data);
                    if (title == 'Dueño') {
                        this.title = 'dueño'
                        this.textLable = 'Agregar Dueño'
                        this.user = res.data.infoClinicOwner   
                    }else if (title == 'Recolector') {
                        this.title = 'recolector'
                        this.textLable = 'Agregar Recolector'
                        this.user = res.data.infoClinicCollector   
                    }
                    console.log("Dueños ",this.user);
                    this.showAdd = true
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            },

            closeUser(){
                this.showAdd = false
                this.selectedUser = ''
            },
            
            saveUser(){
                console.log(this.selectedUser);
                console.log(this.dataClinic.id);
                var status = 0;
                if (this.titlePersonnel == 'Dueño') {
                    status = 1;
                }else{
                    status = 2;
                }
                var data = {
                    'clinic': this.dataClinic.id,
                    'user': this.selectedUser,
                    'status': status,
                }
                axios.post('/clinic/addUser', data).then(res => {
                    console.log("Respuesta del servidor");
                    console.log("Datos de agregar consultorio ",res.data);
                    if (res.data.status == false) {
                        if (this.titlePersonnel == 'Dueño') {
                            this.alertFalse('Parece que el dueño ya tiene este consultorio');
                        }else if(this.titlePersonnel == 'Recolector'){
                            this.alertFalse('Parece que el recolector ya tiene este consultorio');
                        }
                    }else if (res.data.status == true) {
                        if (this.titlePersonnel == 'Dueño') {
                            this.alertTrue('El dueño se agrego correctamente');
                            this.clinicPersonner = res.data.user.filter(item => item.role_id === 5)
                        }else if(this.titlePersonnel == 'Recolector'){
                            this.alertTrue('El recolector se agrego correctamente');
                            this.clinicPersonner = res.data.user.filter(item => item.role_id === 3)
                        }
                        this.showAdd = false   
                    }
                    this.selectedUser = ''
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                    if (error.response.status == 422) {
                        if (this.titlePersonnel == 'Dueño') {
                            this.alertFalse('Parece que el campo agregar dueño estan vacío');
                        }else if(this.titlePersonnel == 'Recolector'){
                            this.alertFalse('Parece que el campo agregar recolector estan vacío');
                        }
                    }else{
                        this.alertFalse('Parece que algo salio mal');
                    }
                    this.selectedUser = ''
                });
            },

            remove(item){
                var message = ''
                var role = ''
                if (this.titlePersonnel == 'Dueño') {
                    message = 'dueño'
                    role = 'dueños'
                }else if(this.titlePersonnel == 'Recolector'){
                    message = 'recolector'
                    role = 'recolectores'
                }
                swal({
                    title: `Seguro que quiere quitar este ${message} ${item.document} de la lista de ${role}`,
                    text: "Los cambios se aplicaran permanentemente!",
                    icon: "warning",
                    buttons: true,
                    succesMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.post(`/clinic/deleteUser/${role}`, item).then(res => {
                            console.log("Respuesta del servidor");
                            console.log("Datos de delete ",res.data);
                            if (this.titlePersonnel == 'Dueño') {
                                this.clinicPersonner = res.data.users.filter(item => item.role_id === 5)
                            }else if(this.titlePersonnel == 'Recolector'){
                                this.clinicPersonner = res.data.users.filter(item => item.role_id === 3)
                            }
                            this.searchPersonnel = ''
                            swal(
                                'Cambio Exitoso!',
                                `Se quito el ${message} del la lista de ${role}`,
                                'success'
                            )
                            console.log("filtro ",this.titlePersonnel);
                        }).catch(error => {
                            console.log("Error en servidor");
                            console.log(error);
                            console.log(error.response);
                            this.alertFalse('Parece que algo salio mal')
                        });
                        console.log("item ",item);
                    }
                })
            },

            alertTrue(text){
                swal({
                    title: "Cambio Exitoso!",
                    text: text,
                    icon: "success",
                });
            },

            alertFalse(text){
                swal({
                    title: "ERROR!",
                    text: text,
                    icon: "error",
                });
            },
        }
    }
</script>

