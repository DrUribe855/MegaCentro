<template>
    <v-app>
        <v-main>
            
              <v-data-table
                :headers="headers"
                :items="desserts"
                sort-by="calories"
                class="elevation-1"
              >
                <template v-slot:top>
                  <v-toolbar
                    flat
                  >
                    <v-toolbar-title>Consultorios asignados</v-toolbar-title>
                    <v-divider
                      class="mx-4"
                      inset
                      vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog
                      v-model="dialog"
                      max-width="500px"
                    >
                      <v-card>
                        <v-card-title>
                          <span class="text-h5"> Registro de recolección </span>
                        </v-card-title>

                        <v-card-text>
                          <v-container>
                            <v-row>
                              <v-col
                                cols="12"
                                sm="6"
                                md="6"
                              >
                                <v-text-field
                                  name="clinicNumber"
                                  v-model="editedItem.clinic.clinic_number"
                                  label="Numero de consultorio"
                                  readonly
                                ></v-text-field>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="6"
                                md="6"
                              >
                                <v-select
                                  id="select1"
                                  v-model="subCategory"
                                  :items="['Peligrosos', 'No peligrosos']"
                                  label="Categoría de residuo"
                                ></v-select>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="6"
                                md="6"
                              >
                                <v-select
                                  id="select2"
                                  v-model="type"
                                  v-if="subCategory === 'Peligrosos'"
                                  :items="opcionesPeligrosos.map(opcion => opcion.label)"
                                  label="Tipo de residuo peligroso"
                                ></v-select>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="6"
                                md="6"
                              >
                                <v-select
                                  id="select3"
                                  v-model="editedItem.clinic.residue_id"
                                  v-if="subCategory === 'Peligrosos'"
                                  :items="getOpcionesResiduo(type)"
                                  label="Tipo de residuo peligroso"
                                ></v-select>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="6"
                                md="6"
                              >
                                <v-select
                                  id="select4"
                                  v-model="editedItem.clinic.residue_id"
                                  v-if="subCategory === 'No peligrosos'"
                                  :items="opcionesNoPeligrosos.map(opcion => opcion.label)"
                                  label="Tipo de residuo no peligroso"
                                ></v-select>
                              </v-col>
                              <v-col
                                cols="12"
                                sm="6"
                                md="6"
                              >
                                <v-text-field
                                  id="select5"
                                  type="number"
                                  label="Cantidad recolectada"
                                  v-model="editedItem.clinic.weight"
                                ></v-text-field>
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
                    <v-dialog v-model="dialogDelete" max-width="500px">
                      <v-card>
                        <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                          <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                          <v-spacer></v-spacer>
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
                    color="green"
                  >
                    mdi-plus
                  </v-icon>
                </template>
                <template v-slot:no-data>
                  <v-btn
                    color="primary"
                    @click="getClinics"
                  >
                    Reset
                  </v-btn>
                </template>
              </v-data-table>
        </v-main>
    </v-app>
</template>
<script>

  import swal from 'sweetalert';

  export default {
    data: () => ({
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: 'Numero de consultorio',
          align: 'center',
          sortable: false,
          value: 'clinic.clinic_number',
        },
        { text: 'N° Torre', value: 'clinic.tower_id', align: 'center' },
        { text: 'Estado de recolección', value: 'clinic.collection_status', align: 'center' },
        { text: 'Registrar recolección', value: 'actions', sortable: false, align: 'center' },
      ],
      desserts: [],
      editedIndex: -1,
      editedItem: {
        id: '',
        clinic: {
          clinic_number: '',
          residue_id: '',
          weight: '',
        }
      },
      clinicNumber: {

      },
      subCategory: "",
      type: "",
      opcionesPeligrosos: [
        {
          value: "infecciosos o riesgo biologico",
          label: "Infecciosos o riesgo biologico"
        },
        {
          value: "quimicos",
          label: "Quimicos"
        },
        {
          value: "radioactivos",
          label: "Radioactivos"
        }
      ],
      opcionesNoPeligrosos: [
        {
          value: "biodegradables",
          label: "Biodegradables"
        },
        {
          value: "reciclables",
          label: "Reciclables"
        },
        {
          value: "inertes",
          label: "Inertes"
        },
        {
          value: "ordinarios_comunes",
          label: "Ordinarios - Comunes"
        }
      ]
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
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
      this.getClinics();
    },

    methods: {
      getClinics(){
        axios.get('/collector/clinics').then(res => {
            console.log('Respuesta del servidor');
            console.log(res.data);
            this.desserts = res.data.clinics;
        }).catch(error => {
            console.log('Error en axios: ');
            console.log(error);
            console.log(error.response);
        })
      },

      editItem (item) {
        console.log(item);
        this.editedItem.clinic.clinic_number = item.clinic.clinic_number;  
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
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
        axios.post('/collector/saveCollection', this.editedItem.clinic).then(resp => {
          console.log('Recolección registrada exitosamente: ', resp.data);
          if(resp.data == 'Todos los datos deben ser validados'){
            this.showAlert('Error', 'Todos los datos deben ser validados', 'error');
          }else{
            this.showAlert('Registrado', 'La recolección se ha registrado con éxito', 'success');
            this.close();
          }
          this.getClinics();
        }).catch(error => {
          console.log(error.response);
        });
        
      },
      getOpcionesResiduo(tipoPeligroso) {
        switch (tipoPeligroso) {
          case "Infecciosos o riesgo biologico":
            return ["Biosanitarios", "Anatomopatologicos", "Cortopunzantes", "Animales"];
          case "Quimicos":
            return ["Farmacos", "Citotóxicos", "Metales pesados", "Reactivos", "Contenedores presurizados", "Aceites usados"];
          case "Radioactivos":
            return ["Fuentes abiertas", "Fuentes cerradas"];
          default:
            return [];
        }
      },
      showAlert(title, text, icon){
        swal({
          title: title,
          text: text,
          icon: icon,
        });
      },
    },
  }
</script>