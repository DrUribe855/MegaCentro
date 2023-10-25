<template>
  <div class="bg-white">  
    <v-app v-if="!showEdit">
      <v-main>
        <v-data-table
          :headers="headers"
          :items="desserts"
          class="elevation-1"
        >
          <template v-slot:top>
            <v-toolbar
              flat
            >
              <v-toolbar-title>Consultorios</v-toolbar-title>
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
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    color="primary"
                    dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                  >
                    Nuevo Consultorio
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
                            v-model="editedItem"
                            label="Numero Clinica"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="4"
                        >
                          <v-text-field
                            v-model="editedItem"
                            label="Numero Torre"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="4"
                        >
                          <v-text-field
                            v-model="editedItem"
                            label="Numero Documento"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="4"
                        >
                        <v-select
                        :items="items"
                          filled
                          v-show="formTitle ===  'Editar Consultorio'"
                          v-model="editedItem"
                          label="Estado"
                          dense
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
            </v-toolbar>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-btn
              x-small
              class=""
              outlined
              fab
              color="red"
              @click="showInfo(item)"
            >
              <v-icon>mdi-plus</v-icon>
            </v-btn>
            <v-btn
              x-small
              class=""
              outlined
              fab
              color="primary"
              @click="showInfo(item)"
            >
              <v-icon>mdi-format-list-bulleted-square</v-icon>
            </v-btn>
          </template>
          <template v-slot:no-data>
            <v-btn
              color="primary"
              @click="initialize"
            >
              Reset
            </v-btn>
          </template>
        </v-data-table>
      </v-main>
    </v-app>
    <div>
      <clinic-edit v-if="showEdit" :clinic="dataInfo"></clinic-edit>
    </div>
  </div>
</template>

<script>

import ClinicEdit from "./EditClinic.vue";

export default {

  components: {
    'clinic-edit': ClinicEdit,
  },

  data: () => ({
    showEdit: false,
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: 'Numero de documento', value: 'document' },
      { text: 'Nombre', value: 'name' },
      { text: 'Cantidad Consultorios', value: 'clinic.length' },
      { text: 'Opciones', value: 'actions', sortable: false },
    ],
    desserts: [],
    editedIndex: -1,
    editedItem: {
      
    },
    defaultItem: {
      clinic: {
        clinic_number: '',
        status: '',
        user: {
          document: '',
        }
      },
      tower_id: '',
    },
    items: ['ACTIVO', 'INACTIVO'],

    dataInfo: [],
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Nuevo Consultorio' : 'Editar Consultorio'
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
    this.initialize()
  },

  methods: {
    goToBack(){
      this.showEdit = false
      this.dataInfo = []
    },

    showInfo(item){
      this.dataInfo = item
      this.showEdit = true
      console.log("02",this.dataInfo.clinic[0].clinic_tower[0].tower_id);
    },

    initialize () {
      axios.get('/clinic/generalShow').then(res => {
          console.log("Respuesta del servidor");
          console.log(res.data);
          this.desserts = res.data.responsible;
      }).catch(error => {
          console.log("Error en servidor");
          console.log(error);
          console.log(error.response);
      });
    },

    editItem (item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
      console.log(this.editedItem);
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
      if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem)
      } else {
        this.desserts.push(this.editedItem)
      }
      this.close()
    },
  },
}
</script>