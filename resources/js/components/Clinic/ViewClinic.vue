<template>
    <div class="bg-white">
        <v-app>
            <v-main>
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
                    <v-card-title>
                        {{ tittle }}
                        <!-- <v-spacer></v-spacer> -->
                        <v-card-actions class="ml-5">
                            <v-select
                                v-model="selectedFilter"
                                :items="filters"
                                menu-props="auto"
                                label="Ver por"
                                hide-details
                                prepend-icon="mdi-filter"
                                single-line
                                :onchange="changeFilter()"
                                x-small
                            ></v-select>
                        </v-card-actions>
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
                                            label="Numero consultorio o local"
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

                    <!-- Registra Consultorios -->
                    <v-dialog
                        v-model="dialogRegister"
                        max-width="600px"
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
                                    label="Numero consultorio o local"
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                    >
                                    <v-text-field
                                    type="number"
                                    v-model="registerClinic.floor"
                                    label="Numero Piso"
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

                    <!-- Tabla sin responsables -->
                    <v-data-table v-if="!showTables"
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
                    <!-- Tabla de los consultorios con responsables -->
                    <v-data-table v-if="showTables"
                        :headers="headersClinic"
                        :items="dessertsClinic"
                        :search="search"
                        :items-per-page="5"
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
                </v-card>
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
    import swal from 'sweetalert';
    export default {
    data () {
      return {
        itemsTower: ['1','2'],
        items: ['OCUPADO', 'DESOCUPADO'],
        filters: ['RESPONSABLES', 'TORRES','CONSULTORIOS SIN RESPONSABLES', 'CONSULTORIOS CON RESPONSABLES'],
        tittle: 'Consultorios Sin Responsables',
        selectedFilter: '',
        dialogEdit: false,
        showAdd: false,
        showTables: false,
        dialogRegister: false,
        search: '',
        editedIndex: -1,
        editedItem: {
            clinic_number: '',
            floor: '',
            tower_id: '',
            status: '',
        },
        // Datos de la tabla con consultorios sin responsables
        headers: [
          { text: 'N Consultorio', value: 'clinic_number' },
          { text: 'N Torre', value: 'tower_id' },
          { text: 'N Piso', value: 'floor' },
          { text: 'Estado', value: 'status' },
          { text: 'Opciones', value: 'actions', sortable: false },
        ],
        desserts: [],
        // Datos de la tabla con consultorios con responsables
        headersClinic: [
          { text: 'N Consultorio', value: 'clinic.clinic_number' },
          { text: 'N Torre', value: 'clinic.tower_id' },
          { text: 'N Piso', value: 'clinic.floor' },
          { text: 'Estado', value: 'clinic.status' },
          { text: 'Nombre', value: 'user.name' },
          { text: 'Documento', value: 'user.document' },
          { text: 'Opciones', value: 'actions', sortable: false },
        ],
        dessertsClinic: [],

        titlePersonnel: '',

        // Variables del personal
        dialog: false,
        searchPersonnel: '',
        infoClinic: [
            { text: 'Documento', value: 'user.document' },
            { text: 'Nombre', value: 'user.name' },
            { text: 'Telefono', value: 'user.phone' },
            { text: 'Correo', value: 'user.email' },
            { text: 'Opciones', value: 'actions', sortable: false },
        ],
        ownerDesserts: [],

        clinicPersonner: [],

        dataClinic: {
            clinic_number: ''
        },

        title: '',
        textLable: '',

        user: [],
        selectedUser: null,

        registerClinic: {
            clinic_number: '',
            floor: '',
            tower_id: '',
            status: '',
        },

        typeShowInfo: true,
      }
    },

    created () {
        this.clinicResponsible()
        this.initialize()
    },

    methods: {
        initialize () {
            axios.get('/clinic/generalShowClinic').then(res => {
                this.desserts = res.data.clinics;
                if (this.desserts.length == 0) {
                    this.showTables = true
                }
            }).catch(error => {
            });
        },

        clinicResponsible (){
            axios.get('/clinic/showClinicResponsible').then(res => {
                this.dessertsClinic = res.data.clinic
            }).catch(error => {
            });
        },

        changeFilter(){
            if (this.selectedFilter == 'RESPONSABLES') {
                this.$parent.goToBack()
            }else if(this.selectedFilter == 'CONSULTORIOS CON RESPONSABLES'){
                this.showTables = true
                this.tittle = 'Consultorios Con Responsables'
            }else if(this.selectedFilter == 'CONSULTORIOS SIN RESPONSABLES'){
                this.showTables = false
                this.tittle = 'Consultorios Sin Responsables'
            }else if (this.selectedFilter == 'TORRES') {
                this.$parent.showTower('TORRES')
            }
        },

        showInfoEdit(item){
            if (item.clinic != null) {
                this.editedIndex = this.desserts.indexOf(item.clinic);
                this.editedItem = Object.assign({}, item.clinic);
                this.typeShowInfo = false;
            }else{
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.typeShowInfo = true;
            }
            this.dialogEdit = true
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
                            if (this.typeShowInfo) {
                                this.initialize();
                            }else{
                                this.clinicResponsible();
                            }
                            this.dialogEdit = false
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

        close () {
            this.dialogEdit = false
            this.dialog = false
            this.dialogRegister = false
            this.registerClinic = {}
            this.clinicPersonner = []
        },

        infoPersonnel(item, option){
            axios.get(`/clinic/consultation/${item.clinic_id}/${option}`).then(res => {
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
                'clinic_id': this.dataClinic.clinic_id,
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

        showRegister(){
            this.dialogRegister = true
        },

        saveRegister(){
            var validate = true;
            if (this.registerClinic.clinic_number == "") {
                this.alertFalse('Parece que el campo numero consultorio esta vacío');
                validate = false 
            }else if(this.registerClinic.tower_id == ""){
                this.alertFalse('Parece que el campo numero torre esta vacío');
                validate = false
            }else if (this.registerClinic.status == "") {
                this.alertFalse('Parece que el campo estado esta vacío');
                validate = false
            }else if (this.registerClinic.floor == "") {
                this.alertFalse('Parece que el campo piso esta vacío');
                validate = false
            }
            if (validate) {
                axios.post('/clinic/register', this.registerClinic).then(res => {
                    this.alertTrue(`Se registro el consultorio ${res.data.clinic.clinic_number} correctamente!`);
                    this.registerClinic = {}
                    this.dialogRegister = false
                    this.initialize()
                    if (this.selectedFilter = 'CONSULTORIOS CON RESPONSABLES') {
                        this.selectedFilter = 'CONSULTORIOS SIN RESPONSABLES'
                        this.changeFilter()
                    }
                }).catch(error => {
                    if (error.response.status == 422) {
                        this.alertFalse('Parece que algunos campos estan vaíos'); 
                    }else{
                        this.alertFalse('Parece que algo salio mal'); 
                    }
                }); 
            }
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
    },
  }
</script>