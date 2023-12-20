<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<template>
  <div style="height: 100vh; overflow-y: scroll">
    <v-app >
            <v-main>
                    <v-data-table
                        :headers="headers"
                        :items="desserts"
                        :options="paginationOptions"
                        class="elevation-12"
                        :search="search"
                    >
                        <template v-slot:top>
                          <v-toolbar
                            flat
                          >
                            <v-toolbar-title>Lista de empleados</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-col
                              cols="12"
                              md="4"
                              class="mt-2"
                            >
                              <v-text-field
                                v-model="search"
                                placeholder="Filtro de búsqueda"
                                type="text"
                                append-icon="mdi-magnify"
                              ></v-text-field>
                            </v-col>
                            <v-spacer></v-spacer>
                            <v-dialog
                              v-model="dialog"
                              max-width="500px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                  color="primary"
                                  dark
                                  class="mb-2"
                                  v-bind="attrs"
                                  v-on="on"
                                >
                                  Registrar empleado
                                </v-btn>
                              </template>
                              <v-card>
                                <v-card-title>
                                  <span class="text-h5">{{ formTitle }}</span>
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
                                            v-model="editedItem.document"
                                            label="Documento"
                                            type="number"
                                          ></v-text-field>
                                        </v-col>
                                        <v-col
                                          cols="12"
                                          sm="6"
                                          md="4"
                                        >
                                          <v-text-field
                                            v-model="editedItem.name"
                                            label="Nombre"
                                          ></v-text-field>
                                        </v-col>
                                        <v-col
                                          cols="12"
                                          sm="6"
                                          md="4"
                                        >
                                          <v-text-field
                                            v-model="editedItem.last_name"
                                            label="Apellido"
                                          ></v-text-field>
                                        </v-col>
                                        <v-col
                                          cols="12"
                                          sm="6"
                                          md="4"
                                        >
                                          <v-text-field
                                            v-model="editedItem.phone"
                                            label="Télefono"
                                            type="number"
                                          ></v-text-field>

                                        </v-col>
                                        <v-col
                                          cols="12"
                                          sm="6"
                                          md="4"
                                        >
                                          <v-text-field
                                            v-model="editedItem.email"
                                            label="Correo"
                                            type="email"
                                          ></v-text-field>
                                          
                                        </v-col>
                                        <v-col
                                          cols="12"
                                          sm="6"
                                          md="4"
                                        >
                                          <v-text-field
                                            v-model="editedItem.password"
                                            label="Contraseña"
                                            type="password"
                                          ></v-text-field>
                                          
                                        </v-col>
                                        <v-col
                                          cols="12"
                                          sm="6"
                                          md="4"
                                        >
                                        <v-select
                                            :items="roles"
                                            v-model="editedItem.role_name"
                                            placeholder="Cargo"
                                        ></v-select>
                                        </v-col>
                                        <v-col
                                          cols="12"
                                          sm="6"
                                          md="4"
                                        >
                                        <v-select
                                            
                                            :items="items"
                                            v-model="editedItem.status"
                                            
                                            value="Activo"
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
                                    Registrar
                                  </v-btn>
                                </v-card-actions>
                              </v-card>
                            </v-dialog>
                            
                          </v-toolbar>
                        </template>
                        <template v-slot:item.actions="{ item }">
                          <v-icon
                            small
                            class="mr-2"
                            @click="editItem(item)"
                          >
                            mdi-pencil
                          </v-icon>
                        </template>
                        <template v-slot:no-data>
                          <v-btn
                            color="primary"
                            @click="getEmployees"
                          >
                            Reiniciar
                          </v-btn>
                        </template>
                    </v-data-table>
            </v-main>
        </v-app>
  </div>
       
</template>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle';
</script>

 

<script>

  import swal from 'sweetalert';

  export default {
    data: () => ({
      search: '',
      rules: [
        value => !!value || 'Obligatorio',
      ],
      dialog: false,
      dialogDelete: false,
      paginationOptions: {
        customFilter: (itemsPerPage) => [5, 10].includes(itemsPerPage),
      },
      items: ['Activo', 'Inactivo'],
      roles: ['Administrador','Auxiliar Contable', 'Recolector','Responsable','Dueño'],
      headers: [
        {
          text: 'Documento',
          align: 'start',
          sortable: false,
          value: 'document',
        },
        { text: 'Nombre', value: 'name' },
        { text: 'Apellido', value: 'last_name' },
        { text: 'Correo', value: 'email' },
        // { text: 'Télefono', value: 'phone' },
        { text: 'Estado', value: 'status' },
        { text: 'Cargo', value: 'role_name' },
        { text: 'Editar', value: 'actions', sortable: false },
      ],
      search: '',
      desserts: [],
      editedIndex: -1,
      editedItem: {
        id: '',
        document : '',
        name : '',
        last_name : '',
        phone : '',
        email : '',
        role_name : '',
        status: '',
        password : '',
      },
      documentFilter: '',
      
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Registrar usuario' : 'Editar usuario'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },

    created () {
      this.getEmployees()
    },

    methods: {
        getEmployees(){
            axios.get('administrator/generalShow').then(res => {
                console.log('Respuesta del servidor');
                console.log(res);
                this.desserts = res.data.employees;
            }).catch(error => {
                console.log('Error en axios: ');
                console.log(error);
                console.log(error.response);
            })
        },
      editItem (item) {
        // console.log(this.editedItem);
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

      deleteItemConfirm () {
        this.desserts.splice(this.editedIndex, 1)
        this.closeDelete()
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
        if(this.formTitle === 'Registrar usuario'){
          axios.post('administrator/registerEmployees', this.editedItem).then(resp => {
              console.log('Datos enviados correctamente: ', resp.data);
              if(resp.data.status == false && resp.data.message == "Documento registrado"){
                this.showAlert('Error', 'Ya hay un usuario registrado con ese numero de documento', 'error'); 
              }else if(resp.data.message == "Email registrado" && resp.data.status == false){
                this.showAlert('Error', 'Ya hay un usuario registrado con ese email', 'error'); 
              }else if(resp.data.message == 'Registro guardado correctamente'){
                this.showAlert('Correcto', 'El usuario se ha registrado con éxito', 'success');
                this.getEmployees();
                this.close();
              }
          }).catch(error => {
              console.log(error.response);
              if( error.response.status == 422 && error.response.data.message == 'validation.email'){
                this.showAlert('Error', 'El email ingresado no es válido', 'error');
              }else if(error.response.status == 422 && error.response.statusText == 'Unprocessable Content'){
                this.showAlert('Error', 'Todos los campos deben ser validados', 'error');
              }
          });    
        }else{
          let id = this.editedItem.id;
          console.log(id);
          axios.put(`administrator/updateEmployees/${id}`,{
            document: this.editedItem.document,
            name: this.editedItem.name,
            last_name: this.editedItem.last_name,
            phone: this.editedItem.phone,
            email: this.editedItem.email,
            status: this.editedItem.status,
            role_name: this.editedItem.role_name,
            password: this.editedItem.password,
          }).then(res => {
            console.log("Impresión de guardado: ",res.data);
            if(res.data.status == false && res.data.message == "Documento registrado"){
                this.showAlert('Error', 'Ya hay un usuario registrado con ese numero de documento', 'error'); 
              }else if(res.data.message == "Email registrado" && res.data.status == false){
                this.showAlert('Error', 'Ya hay un usuario registrado con ese email', 'error'); 
              }else{
                this.showAlert('Correcto', 'El usuario se ha modificado con éxito', 'success');
                this.getEmployees();
                this.close()
              }
            
          }).catch(error => {
            console.log("Impresión de error: ", error.response);
            if( error.response.status == 422 && error.response.data.message == 'validation.email'){
                this.showAlert('Error', 'El email ingresado no es válido', 'error');
              }else if(error.response.status == 422 && error.response.statusText == 'Unprocessable Content'){
                this.showAlert('Error', 'Todos los campos deben ser validados', 'error');
              }
          });
        }
      },
      showAlert(title, text, icon){
        swal({
          title: title,
          text: text,
          icon: icon,
        });
      }
    },
  }
</script>