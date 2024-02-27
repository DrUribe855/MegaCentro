<template>
    <div>
        <v-app>
            <v-main>
                <v-card>
                    <v-card-title>
                        Responsable: {{ infoUser.document }}
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
                              <v-autocomplete
                                v-model="selectedUser"
                                :items="user"
                                :menu-props="{ top: false, offsetY: true }"
                                :label="textLable"
                                item-text="document"
                                item-value="id"
                              ></v-autocomplete>
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
                                            md="4">
                                            <v-text-field
                                                v-model="editedItem.clinic_number"
                                                label="Numero clinica o local"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4">
                                            <v-text-field
                                                type="number"
                                                v-model="editedItem.floor"
                                                label="Numero Piso"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4">
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
                                            md="4">
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
                    { text: 'N Piso', value: 'floor' },
                    { text: 'Estado', value: 'status' },
                    { text: '', value: 'actions', sortable: false },
                ],
                desserts: [
                    
                ],
                editedIndex: -1,
                editedItem: {
                    clinic_number: '',
                    floor: '',
                    tower_id: '',
                    status: '',
                },
                infoUser: '',
                // Tabla dueños
                infoClinic: [
                    { text: 'Documento', value: 'user.document' },
                    { text: 'Nombre', value: 'user.name' },
                    { text: 'Telefono', value: 'user.phone' },
                    { text: 'Correo', value: 'user.email' },
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
            this.initialize()
        },

        methods: {
            initialize(){
                axios.post(`/clinic/infoClinic/${this.infoUser.id}`).then(res => {
                    this.desserts = res.data.clinic;
                }).catch(error => {
                });
            },

            infoPersonnel(item, option){
                axios.get(`/clinic/consultation/${item.id}/${option}`).then(res => {
                    if (option == 1) {
                        this.titlePersonnel = 'Dueño'
                        this.clinicPersonner = res.data.infoClinic  
                    }else{
                        this.titlePersonnel = 'Recolector'
                        this.clinicPersonner = res.data.infoClinic
                    }
                    this.dialog = true
                }).catch(error => {
                });
                this.dataClinic = item
            },

            showInfoEdit(item){
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
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
                    swal({
                        title: "Quiere editar este consultorio?",
                        text: "Los cambios se aplicaran permanentemente!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            axios.put(`/clinic/update/${this.editedItem.id}`, {data: this.editedItem}).then(res => {
                                this.initialize();
                                this.dialogEdit= false
                                swal(
                                    'Cambio Exitoso!',
                                    'Se edito correctamente',
                                    'success'
                                )
                            }).catch(error => {
                            });
                        }
                    })
                    this.close()   
                }
            },

            showUser(title){
                axios.get('/clinic/consultationUser').then(res => {
                    if (title == 'Dueño') {
                        this.title = 'dueño'
                        this.textLable = 'Agregar Dueño'
                        this.user = res.data.infoClinicOwner   
                    }else if (title == 'Recolector') {
                        this.title = 'recolector'
                        this.textLable = 'Agregar Recolector'
                        this.user = res.data.infoClinicCollector   
                    }
                    this.showAdd = true
                }).catch(error => {
                });
            },

            closeUser(){
                this.showAdd = false
                this.selectedUser = ''
            },
            
            saveUser(){
                var status = 0;
                if (this.titlePersonnel == 'Dueño') {
                    status = 1;
                }else{
                    status = 2;
                }
                var data = {
                    'clinic_id': this.dataClinic.id,
                    'user_id': this.selectedUser,
                    'status': status,
                }
                axios.post('/clinic/addUser', data).then(res => {
                    if (res.data.status == false) {
                        if (this.titlePersonnel == 'Dueño') {
                            this.alertFalse('Parece que el dueño ya tiene este consultorio');
                        }else if(this.titlePersonnel == 'Recolector'){
                            this.alertFalse('Parece que el recolector ya tiene este consultorio');
                        }
                    }else if (res.data.status == true) {
                        if (this.titlePersonnel == 'Dueño') {
                            this.alertTrue('El dueño se agrego correctamente');
                            this.clinicPersonner = res.data.user
                        }else if(this.titlePersonnel == 'Recolector'){
                            this.alertTrue('El recolector se agrego correctamente');
                            this.clinicPersonner = res.data.user
                        }
                        this.showAdd = false   
                    }
                    this.selectedUser = ''
                }).catch(error => {
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
                            if (this.titlePersonnel == 'Dueño') {
                                this.clinicPersonner = res.data.users
                            }else if(this.titlePersonnel == 'Recolector'){
                                this.clinicPersonner = res.data.users
                            }
                            this.searchPersonnel = ''
                            swal(
                                'Cambio Exitoso!',
                                `Se quito el ${message} del la lista de ${role}`,
                                'success'
                            )
                        }).catch(error => {
                            this.alertFalse('Parece que algo salio mal')
                        });
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

