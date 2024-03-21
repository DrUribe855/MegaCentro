<template>
  <div class="bg-white" style="height: 100vh; overflow-y: scroll"> 
    <div v-if="!showFilterTower">
      <div v-if="!showFilterClinic">
        <div v-if="!showInvoice">
          <v-app v-if="!showEdit">
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
                  <v-card-title>
                    {{ title }}
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
                  <v-data-table
                    :headers="headers"
                    :items="desserts"
                    :search="search"
                  >
                    <template v-slot:item.actions="{ item }">
                      <v-btn
                        class="mx-1"
                        x-small
                        fab
                        dark
                        color="red"
                        @click="addClinic(item)"
                      >
                        <v-icon dark>
                          mdi-plus
                        </v-icon>
                      </v-btn>
                      <v-btn
                        v-if="!showBtn"
                        x-small
                        class="mx-1"
                        fab
                        dark
                        color="primary"
                        @click="showInfo(item)"
                      >
                        <v-icon dark>
                          mdi-format-list-bulleted-square
                        </v-icon>
                      </v-btn>
                      <v-btn 
                        v-if="!showBtn"
                        class="mx-1"
                        x-small
                        fab
                        dark
                        color="green"
                        @click="showViewInvoice(item)"
                      >
                        <v-icon dark>
                          mdi-currency-usd
                        </v-icon>
                      </v-btn>
                    </template>
                  </v-data-table>
                  <!-- Registrar consultorio -->
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
                              md="4">
                              <v-text-field
                                v-model="registerClinic.clinic_number"
                                label="Número consultorio o local"
                              ></v-text-field>
                            </v-col>
                            <v-col
                              cols="12"
                              sm="6"
                              md="4">
                              <v-text-field
                                type="number"
                                v-model="registerClinic.floor"
                                label="Numero Piso"
                              ></v-text-field>
                            </v-col>
                            <v-col
                              cols="12"
                              sm="6"
                              md="4">
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
                              md="4">
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
  
                  <!-- Añadir consultorio a un responsable -->
                  <v-dialog
                    v-model="dialog"
                    max-width="630px"
                  >
                    <v-card>
                      <v-card-title>
                        <span class="text-h5">Agregar consultorio a  {{ infoResponsible.name }}</span>
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
                                v-model="infoResponsible.document"
                                label="Numero Documento"
                                readonly
                              ></v-text-field>
                            </v-col>
                            <v-col
                              cols="12"
                              sm="6"
                              md="4"
                            >
                              <v-text-field
                                v-model="infoResponsible.name"
                                label="Nombre"
                                readonly
                              ></v-text-field>
                            </v-col>
                            <v-col
                              cols="12"
                              sm="6"
                              md="4"
                            >
                              <v-autocomplete
                                v-model="selectedTower"
                                :items="itemsTower"
                                label="Seleccione la torre"
                                @change="selectedOptionTower"
                              ></v-autocomplete>
                            </v-col>
                            <v-autocomplete
                              v-model="selectedClinic"
                              :items="clinics"
                              :menu-props="{ top: false, offsetY: true }"
                              label="Agregar Consultorio"
                              item-text="clinic_number"
                              item-value="id"
                            ></v-autocomplete>
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
                          Agregar
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-card>
              </template>
            </v-main>
          </v-app>
        </div>
      </div>
    </div>
    <div>
      <clinic-edit v-if="showEdit" :clinic="dataInfo"></clinic-edit>
      <clinic-show v-if="showFilterClinic"></clinic-show>
      <clinic-tower v-if="showFilterTower"></clinic-tower>
      <clinic-invoice v-if="showInvoice" :responsible="dataInvoice"></clinic-invoice>
    </div>
  </div>
</template>

<script>
  import ClinicEdit from "./Clinic.vue";
  import ClinicShow from "./ViewClinic.vue";
  import ClinicTower from "./Tower.vue";
  import ClinicInvoice from "./Invoice.vue";
  import accounting, { parse } from 'accounting';

  export default {

    components: {
      'clinic-edit': ClinicEdit,
      'clinic-show': ClinicShow,
      'clinic-tower': ClinicTower,
      'clinic-invoice': ClinicInvoice,
    },

    data: () => ({
      title: '',
      search: '',
      selectedFilter: '',
      selectedTower: '',
      itemsTower: ['1','2'],
      filters: ['CONSULTORIOS', 'TORRES', 'RESPONSABLES CON CONSULTORIOS', 'RESPONSABLES SIN CONSULTORIOS'],
      showFilterTower: false,
      showFilterClinic: false,
      showEdit: false,
      dialog: false,
      dialogRegister: false,
      showBtn: false,
      showInvoice: false,
      selectedClinic: null,
      clinics: [],
      clinicsTower1: [],
      clinicsTower2: [],
      dataInfo: [],
      dataInvoice: [],
      user: [],
      dataBill: [],
      dataBillTemp: [],
      totalBill: [],
      desserts: [],
      dessertsTemp: [],
      headers: [
        { text: 'Numero de documento', value: 'document' },
        { text: 'Nombre', value: 'name' },
        { text: 'Cantidad Consultorios', value: 'clinic_user.length' },
        { text: 'Total a pagar', value: 'invoice' },
        { text: 'Opciones', value: 'actions', sortable: false },
      ],
      items: ['OCUPADO', 'DESOCUPADO'],
      infoResponsible: {
        document: '',
        name: '',
      },
      registerClinic: {
        clinic_number: '',
        floor: '',
        tower_id: '',
        status: '',
      },
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
      this.initialize(0)
    },

    methods: {
      changeFilter(){
        if (this.selectedFilter == 'CONSULTORIOS') {
          this.showFilterClinic = true
        }else if(this.selectedFilter == 'TORRES'){
          this.showFilterTower = true
        }else if(this.selectedFilter == 'RESPONSABLES CON CONSULTORIOS'){
          this.initialize(0);
          this.showBtn = false;
          this.showFilterClinic = false 
          this.selectedFilter = ''
        }else if (this.selectedFilter == 'RESPONSABLES SIN CONSULTORIOS'){
          this.initialize(2);
          this.showBtn = true
          this.showFilterClinic = false
          this.selectedFilter = ''
        }
      },

      showTower(textFilter){
        if((textFilter == 'TORRES')){
          this.showFilterClinic = false
          this.showFilterTower = true
        }
      },

      showClinic(textFilter){
        if((textFilter == 'CONSULTORIOS')){
          this.showFilterTower = false
          this.showFilterClinic = true
        }
      },

      goToBack(){
        this.showEdit = false;
        this.showFilterClinic = false;
        this.showFilterTower = false;
        this.dataInfo = [];
        this.selectedFilter = '';
      },

      showInfo(item){
        this.dataInfo = item;
        this.showEdit = true;
      },

      addClinic(item){
        axios.get('/clinic/generalShowClinic').then(res => {
          this.clinicsTower1 = res.data.clinicsTower1;;
          this.clinicsTower2 = res.data.clinicsTower2;
        }).catch(error => {
        });
        this.infoResponsible = item
        this.dialog = true
      },

      initialize (optionFilter) {
        axios.get('/clinic/generalShow').then(res => {
          if (optionFilter == 0) {
            this.dataBill = res.data.responsible.filter(item => item.clinic_user.length > 0);
            this.desserts = res.data.responsible.map(item => ({ ...item, invoice: 0, position: -1 })).filter(item => item.clinic_user.length > 0);              
            if (this.desserts.length == 0) {
              this.title = 'Responsables sin consultorio';
              this.desserts = res.data.responsible.filter(item => item.clinic_user.length == 0);
              this.showBtn = true
              this.showFilterClinic = false
            }else{
              this.title = 'Responsables con consultorio';
              this.showBtn = false;
              this.showFilterClinic = false 
            }
            this.invoices();
          }else if (optionFilter == 2) {
            this.title = 'Responsables sin consultorio';
            this.desserts = res.data.responsible.filter(item => item.clinic_user.length == 0);
            this.showBtn = true
            this.showFilterClinic = false
          }else{
            this.dataBill = res.data.responsible.filter(item => item.clinic_user.length > 0);
            this.desserts = res.data.responsible.map(item => ({ ...item, invoice: 0, position: -1 })).filter(item => item.clinic_user.length > 0);
            this.title = 'Responsables con consultorio';
            this.showBtn = false;
            this.showFilterClinic = false 
          }
        }).catch(error => {
        });
      },

      invoices(){// Factura
        for (let i = 0; i < this.dataBill.length; i++) {
          let total = 0;
          for (let j = 0; j < this.dataBill[i].clinic_user.length; j++) {
            for (let l = 0; l < this.dataBill[i].clinic_user[j].clinic.collection_log.length; l++) {
              if (this.dataBill[i].clinic_user[j].clinic.collection_log[l].invoice_status == 'Debe') {
                for (let p = 0; p < this.dataBill[i].clinic_user[j].clinic.collection_log[l].waste_collection.length; p++) {
                  total+= (this.dataBill[i].clinic_user[j].clinic.collection_log[l].waste_collection[p].residues.price * this.dataBill[i].clinic_user[j].clinic.collection_log[l].waste_collection[p].weight);
                }
              }
            }
          }
          this.desserts[i].invoice = this.format(total);
          this.desserts[i].position = i;
        }
      },

      format(number){
        if (number < 1000) {
          number = accounting.formatMoney(number, {
            symbol: '$',
            precision: '3',
            thousand: ',',
            decimal: ','
          });
        }else{
          number = accounting.formatMoney(number, {
            symbol: '$',
            precision: '',
            thousand: ',',
            decimal: ','
          });
        }
        return number;
      },

      editItem (item) {
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      close () {
        this.dialogRegister = false
        this.dialog = false
        this.registerClinic = {}
      },

      save () {
        var data = {
          'clinic_id': this.selectedClinic,
          'user_id': this.infoResponsible.id
        }
        axios.post('/clinic/addClinic', data).then(res => {
          this.desserts = res.data.responsible.filter(item => item.clinic_user.length > 0);
          this.alertTrue(`El consultorio se agrego correctamente al responsable ${this.infoResponsible.document}`);
          this.selectedFilter = 'Ver por';
          this.showBtn = false;
          if (!this.showBtn) {
            this.initialize(0);
          }else if(this.showBtn){
            this.initialize(2);
          }
        }).catch(error => {
          if (error.response.status == 422) {
            this.alertFalse('Parece que el campo agregar cosultorio esta vaío'); 
          }else{
            this.alertFalse('Parece que algo salio mal'); 
          }
        });
        this.dialog = false;
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
        }
        if (validate == true) {
          axios.post('/clinic/register', this.registerClinic).then(res => {
            this.alertTrue(`Se registro el consultorio ${res.data.clinic.clinic_number} correctamente!`);
            this.dialogRegister = false
            this.showFilterClinic = true
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

      showViewInvoice(item){
        this.showInvoice = true;
        this.dataInvoice = item;
      },

      goToBackInvoice(price, position){
        this.desserts[position].invoice = price;
        this.showInvoice = false;
      },

      selectedOptionTower(){
        if (this.selectedTower == '1') {
          this.clinics = this.clinicsTower1;
        }else{
          this.clinics = this.clinicsTower2;
        }
      },
    },
  }
</script>