<template>
    <div>
        <v-app>
            <v-main>
                <template>
                    <v-card 
                        align="end">
                        <v-btn 
                            class="mx-4 mt-2" 
                            depressed
                            color="primary"
                            @click="showRegister()"
                        >
                            Nuevo Consultorio
                        </v-btn>
                        <v-card-title >
                            Torres
                            <v-card-actions
                                class="ml-5"
                            >
                                <v-select
                                    v-model="selectedFilter"
                                    :items="filters"
                                    menu-props="auto"
                                    :label="label"
                                    hide-details
                                    prepend-icon="mdi-filter"
                                    single-line
                                    :onchange="changeFilter()"
                                    small
                                ></v-select>
                            </v-card-actions>
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-card-title>
                        <v-data-table
                            :headers="headers"
                            :items="desserts"
                            :search="search"
                            :items-per-page="5"
                        >
                            <template v-slot:item.actions="{ item }">
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
                                                    v-model="editedItem.clinic_number"
                                                    label="Numero Consultorio"
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
                                        @click="close()"
                                    >
                                        Cancelar
                                    </v-btn>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="save()"
                                    >
                                        Guardar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>

                        <!-- Registrar consultorio -->
                        <v-dialog
                            v-model="dialogRegister"
                            max-width="500px"
                            >
                            <v-card>
                                <v-card-title>
                                <span class="text-h5">Registrar Consultorio</span>
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
                                        v-model="registerClinic.clinic_number"
                                        label="Numero Consultorio"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                        <v-select 
                                        v-model="registerClinic.tower_id"
                                        :items="itemsTower"
                                        label="Seleccione la Torre"
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
                                        v-model="registerClinic.status"
                                        :items="items"
                                        label="Seleccione el estado"
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
                                    @click="close()"
                                >
                                    Cancelar
                                </v-btn>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="saveRegister()"
                                >
                                    Guardar
                                </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-card>
                </template>
                <v-card-actions class="d-flex justify-left align-left">
                    <v-btn
                        depressed
                        color="primary"
                        @click="$parent.goToBack()"
                    >
                        volver
                    </v-btn> 
                </v-card-actions> 
            </v-main>
        </v-app>
    </div>
</template>

<script>
    import Swal from 'sweetalert2';
    export default {
        data () {
          return {
            search: '',
            selectedFilter: '',
            dialogEdit: false,
            dialogRegister: false,
            label: 'Ver por',
            itemsTower: ['1','2','3'],
            items: ['OCUPADO', 'DESOCUPADO'],
            filters: ['RESPONSABLES', 'CONSULTORIOS', 'TORRE #1', 'TORRE #2', 'TORRE #3'],
            headers: [
              { text: 'N Torre', value: 'tower_id' },
              { text: 'N Conuslta', value: 'clinic_number' },
              { text: 'Estado', value: 'status' },
              { text: 'Opciones', value: 'actions', sortable: false },
            ],
            desserts: [],

            editedIndex: -1,
            editedItem: {
                clinic_number: '',
                tower_id: '',
                status: '',
            },

            registerClinic: {
                clinic_number: '',
                tower_id: '',
                status: '',
            },
          }
        },

        created () {
            // console.log("05",this.selectedFilter);
            this.initialize(1)
        },

        methods: {
            initialize (tower) {
                axios.get(`/clinic/showTower/${tower}`).then(res => {
                    // console.log("Respuesta del servidor");
                    // console.log(res.data);
                    this.desserts = res.data.tower
                    this.selectedFilter = ''
                }).catch(error => {
                    // console.log("Error en servidor");
                    // console.log(error);
                    // console.log(error.response);
                });
            },

            changeFilter(){
                if (this.selectedFilter == 'RESPONSABLES') {
                    this.$parent.goToBack()
                }else if (this.selectedFilter == 'CONSULTORIOS') {
                    this.$parent.showClinic('CONSULTORIOS')
                }else if (this.selectedFilter == 'TORRE #1') {
                    this.initialize(1)
                }else if (this.selectedFilter == 'TORRE #2') {
                    this.initialize(2)
                }else if (this.selectedFilter == 'TORRE #3') {
                    this.initialize(3)
                }
                // console.log("Filtro seleccionado ",this.selectedFilter);
            },

            showInfoEdit(item){
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
                // console.log("03 ",this.editedItem);
                this.dialogEdit = true
            },

            save () {
                // console.log(this.editedItem)
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
                    // console.log(this.editedItem);
                    Swal.fire({
                        title: 'Quiere editar este consultorio?',
                        text: "Los cambios se aplicaran permanentemente!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Editar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire(
                                'Cambio Exitoso!',
                                'Se edito correctamente',
                                'success'
                            )
                            axios.put(`/clinic/update/${this.editedItem.id}`, {data: this.editedItem}).then(res => {
                                // console.log("Respuesta del servidor");
                                // console.log(res.data);
                                // console.log(this.editedItem);
                                this.initialize(this.editedItem.tower_id);
                                this.dialogEdit = false
                            }).catch(error => {
                                // console.log("Error en servidor");
                                // console.log(error);
                                // console.log(error.response);
                            });
                        }
                    })
                    this.close()   
                }
            },

            close(){
                this.dialogEdit = false
                this.dialogRegister = false
                this.registerClinic = {}
            },
            
            showRegister(){
                this.dialogRegister = true
            },

            saveRegister(){
                // console.log("Registro", this.registerClinic);
                var validate = true;
                // console.log("registrar consultorio ",this.registerClinic);
                if (this.registerClinic.clinic_number == "") {
                  this.alertFalse('Parece que el campo numero consultorio esta vacío');
                  validate = false 
                }else if(this.registerClinic.tower_id == ""){
                  this.alertFalse('Parece que el campo numero torre esta vacío');
                  validate = false
                }else if (this.registerClinic.status == "") {
                  this.alertFalse('Parece que el campo estado esta vacío');
                  validate = false
                }
                if (validate == true) {
                  axios.post('/clinic/register', this.registerClinic).then(res => {
                    // console.log("Respuesta del servidor");
                    // console.log("Datos de registrar consultorio ",res.data.clinic);
                    this.alertTrue(`Se registro el consultorio ${res.data.clinic.clinic_number} correctamente!`);
                    this.dialogRegister = false
                    this.showFilterClinic = true
                  }).catch(error => {
                    // console.log("Error en servidor");
                    // console.log(error);
                    // console.log(error.response);
                    if (error.response.status == 422) {
                      this.alertFalse('Parece que algunos campos estan vaíos'); 
                    }else{
                      this.alertFalse('Parece que algo salio mal'); 
                    }
                  }); 
                }
            },

            alertTrue(text){
                Swal.fire(
                  'Cambio Exitoso!',
                  text,
                  'success'
                )
            },

            alertFalse(text){
                Swal.fire({
                      icon: 'error',
                      title: 'ERROR',
                      text: text,
                    })
                },
            },
    }
</script>