<template>
  <v-app>
    <v-main>
      <div style="height: 100vh; overflow-y: scroll;">
        <div class="shadow-sm p-3 mb-5 bg-body-tertiary rounded">
          <h1 class="ml-3">Consultorios asignados</h1>
          <v-form class="ml-3">
            <v-container>
              <v-row>
                <v-col
                  cols="12"
                  md="3"
                  sm="6"
                >
                  <v-text-field
                    v-model="general_data.month"
                    label="Ingrese el mes"
                    type="number"
                    readonly
                    required
                  ></v-text-field>
                </v-col>

                <v-col
                  cols="12"
                  md="3"
                  sm="6"
                >
                  <v-text-field
                    v-model="general_data.year"
                    label="Ingrese el año"
                    type="number"
                    required
                    readonly
                  ></v-text-field>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="3"
                >
                  <v-select
                    label = "Horario de recolección"
                    :items="items"
                    v-model="general_data.schedule"
                  ></v-select>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="3"

                >
                  <v-btn
                    color="primary"
                    @click="save()"
                  >Registrar recolecciones</v-btn>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="3"

                >
                  <v-btn
                    color="primary"
                    @click="update()"
                  >Modificar recolecciones</v-btn>
                </v-col>
                <v-col
                  cols="12"
                  md="12"
                  sm="12"
                  lg="12"
                >
                  <p>Filtro de búsqueda</p>
                </v-col>
                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="clinicNumber"
                    label="Ingrese el consultorio"
                    type="number"
                    @keyup="filterClinics()"
                  ></v-text-field>
                </v-col>
                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="towerNumber"
                    label="Ingrese la torre"
                    type="number"
                    @keyup="filterClinics()"
                  ></v-text-field>
                </v-col>
                 <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="floorNumber"
                    label="Ingrese el piso"
                    type="number"
                    @keyup="filterClinics()"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-form>
        </div>
        <div>
          <div v-for="(panel, indexLocalStorage) in filteredPanels"  :key="indexLocalStorage" class="mt-3">
            <v-expansion-panels>
              <v-expansion-panel v-if="datos[indexLocalStorage2+indexLocalStorage].show">
                <v-expansion-panel-header >Consultorio: {{panel.clinic_number  }}  / Torre: {{panel.tower_id}} / Piso: {{ panel.floor }}</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-form>
                    <v-container>
                      <div>
                        <h5> Kilogramos </h5>
                          <v-row>
                            <v-col
                              v-for="(residue,i) in residues" :key="residue.id"
                              cols="12"
                              md="6"
                            >
                              <v-text-field
                                :label="residue.residue_name"
                                v-model="datos[indexLocalStorage2+indexLocalStorage].data[i].weight"
                                @change="changeValue()"
                              ></v-text-field>
                            </v-col>
                          </v-row>
                      </div>
                    </v-container>
                  </v-form>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels> 
          </div>
          <v-pagination v-model="currentPage" :length="Math.ceil(clinics.length / itemsPerPage)"></v-pagination>
        </div>
      </div>
    </v-main>
  </v-app>
</template>
<script>
  import CollectionForm from './CollectionForm.vue';
  import swal from 'sweetalert';

  export default {
    components: {
      'collection-form' : CollectionForm,
    },
    data: () => ({
      currentPage: 1,
      itemsPerPage: 15,
      items: ['Extra - 6:00 AM','Diurno', 'Tarde', 'Extra'],
      indexLocalStorage: 0,
      clinics: [], 
      searchTimer: '',
      clinicNumber: '',
      towerNumber: '',
      floorNumber: '',
      residues: [],
      datos: [],
      general_data: {
        month: '',
        year: '',
        schedule: '',
      }
    }),

    computed: {
      paginationRange() {
      const startIndex = (this.currentPage - 1) * this.itemsPerPage;
      const endIndex = startIndex + this.itemsPerPage - 1;
      return { startIndex, endIndex };
      },
      filteredPanels() {
        let datos = this.clinics.slice(this.paginationRange.startIndex, this.paginationRange.endIndex + 1);
        this.indexLocalStorage = this.paginationRange.startIndex;
        console.log(this.datos);
        return datos;
      },
      indexLocalStorage2() {
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage - 1;
        return startIndex;
      },
    },
    created () {
      this.getClinics();
      this.filterClinics();
    },

    methods: {
      getClinics(){
        axios.get('/collector/clinics').then(res => {
            this.clinics = res.data.clinics;
            this.residues = res.data.residues;
            this.general_data.month = res.data.month;
            this.general_data.year = res.data.year;
            console.log("Esta es la impresión de consultorios: ",this.clinics);
            console.log("Esta es la impresión de residuos: ", this.residues);
            res.data.clinics.forEach(clinic => {
              let aux = {
                clinic_id: clinic.id,
                data: [],
                show: true,
                towerNumber: clinic.tower_id,
                clinicNumber: clinic.clinic_number,
                floorNumber: clinic.floor,
                
              };
              res.data.residues.forEach(residue => {
                aux.data.push({
                    residue_id: residue.id,
                    weight: 0,
                });
              }); 
              this.datos.push(aux);
            });

            if(localStorage.getItem("collectionData")){
              let localData = JSON.parse(localStorage.getItem("collectionData"));
              if(localData.length < this.datos.length){
                for (let i = 0; i < this.datos.length; i++) {
                  if(localData[i] == undefined){
                    localData.push(this.datos[i]);
                  }
                }
                this.datos = localData;
                localStorage.setItem("collectionData", JSON.stringify(localData)); 
              }else if(localData.length > this.datos.length){

                //Filtro para encontrar el o los objetos faltantes en el arreglo datos.
                const objetosFaltantes = localData.filter((localDato) => !this.datos.some((dato) => dato.clinicNumber === localDato.clinicNumber));

                
                objetosFaltantes.forEach((objetoFaltante) => {
                  localData.splice(this.datos.indexOf(objetoFaltante), 1);
                });

                this.datos = localData;
                localStorage.setItem("collectionData", JSON.stringify(localData));
              }else{
                this.datos = localData;
              }
              
            }else{
              localStorage.setItem("collectionData", JSON.stringify(this.datos));    
            }
        }).catch(error => {
            console.log('Error en axios: ');
            console.log(error);
            console.log(error.response);
        });
      },
      save () {
        let request = {
          datos: this.datos,
          data_general: this.general_data,
        }
        axios.post('/collector/saveCollection', request).then(resp => {
          if(resp.data.message == "Recolección registrada"){
            this.cleanInputs();
            this.showAlert('Validado', 'Se han registrado las recolecciones con éxito', 'success');
          }else if(resp.data.message == 'Datos incorrectos en la fecha'){
            this.showAlert('Error', 'Falta diligenciar el horario de recolección', 'error');
          }
        }).catch(error => {
          console.log(error.response);
        });
        
      },
      update(){
        let request = {
          datos: this.datos,
          data_general: this.general_data,
        }
        console.log('Click a update');
        axios.post('/collector/updateCollection', request).then(resp => {
          if(resp.data.message == "Modificacion registrada"){
            this.cleanInputs();
            this.showAlert('Validado', 'Se ha registado con exitos la(s) modificacion(es)','success');
          }else if(resp.data.message == 'Informacion de modificacion incompleta'){
            this.showAlert('Error', 'Falta diligenciar el horario de recolección', 'error');
          }
        }).catch(error => {
            console.log('Error en axios: ');
            console.log(error);
            console.log(error.response);
        });
      },
      showAlert(title, text, icon){
        swal({
          title: title,
          text: text,
          icon: icon,
        });
      },
      changeValue(){
        localStorage.setItem("collectionData", JSON.stringify(this.datos));
        const localData = JSON.parse(localStorage.getItem("collectionData"));
      },
      filterClinics() {

        let filtro = this.datos;

        // Filtro para cuando el numero de la clinica sea diligenciada y los demas datos no

        if(this.clinicNumber != '' && this.towerNumber == '' && this.floorNumber == ''){
         for (let i = 0; i < this.datos.length; i++) {
            if(this.datos[i].clinicNumber.includes(this.clinicNumber)){
              this.datos[i].show = true;
            }else{
              this.datos[i].show = false;
            }
          }
        }

        // Filtro para cuando el numero de torre sea diligenciado y los demás no

        if(this.towerNumber != '' &&  this.clinicNumber == '' && this.floorNumber == ''){
          for (let i = 0; i < this.datos.length; i++) {
            if(this.datos[i].towerNumber == this.towerNumber){
              this.datos[i].show = true;
            }else{
              this.datos[i].show = false;
            }
          }
        }

        //Filtro para cuando el numero de piso sea diligenciado y los demás no

        if(this.floorNumber != '' && this.towerNumber == '' && this.clinicNumber == ''){
          for (let i = 0; i < this.datos.length; i++) {
            if(this.datos[i].floorNumber == this.floorNumber){
              this.datos[i].show = true;
            }else{
              this.datos[i].show = false;
            }
          }
        }

        //Filtro para cuando el numero de torre y el numero de piso sean diligenciados a la vez, pero numero de clinica no.

        if(this.towerNumber != '' && this.floorNumber != ''){
          for (let i = 0; i < this.datos.length; i++) {
            if(this.datos[i].towerNumber == this.towerNumber && this.datos[i].floorNumber.includes(this.floorNumber)){
              this.datos[i].show = true;
            }else{
              this.datos[i].show = false;
            }
          }
        }

        // Filtro para cuando el numero de clinica y el numero de piso sean diligenciados a la vez, pero numero de torre no.

        if(this.clinicNumber != '' && this.floorNumber != ''){
          for (let i = 0; i < this.datos.length; i++) {
            if( this.datos[i].floorNumber == this.floorNumber && this.datos[i].clinicNumber.includes(this.clinicNumber)){
              this.datos[i].show = true;
            }else{
              this.datos[i].show = false;
            }
          }
        }

        // Filtro para cuando el numero de clinica y el numero de torre sean diligenciados a la vez, pero numero de piso no.

        if(this.clinicNumber != '' && this.towerNumber != ''){
          for (let i = 0; i < this.datos.length; i++) {
            if( this.datos[i].towerNumber == this.towerNumber && this.datos[i].clinicNumber.includes(this.clinicNumber)){
              this.datos[i].show = true;
            }else{
              this.datos[i].show = false;
            }
          }
        }

        //Filtro para cuando los 3 campos se encuentren sin diligenciar

        if(this.towerNumber == '' && this.clinicNumber == '' && this.floorNumber == ''){
          for (let i = 0; i < this.datos.length; i++) {
            this.datos[i].show = true;
          }
        }
      },
      cleanInputs(){
        this.general_data.schedule = '';
        console.log(this.general_data);
        for (let i = 0; i < this.datos.length; i++) {
          for (let j = 0; j < this.residues.length; j++) {
            this.datos[i].data[j].bags = 0;
            this.datos[i].data[j].weight = 0;
            localStorage.setItem("collectionData", JSON.stringify(this.datos));
          }
        }
      },
      nextPage() {
        this.currentPage++;
      },
      previousPage() {
        this.currentPage--;
      }
    },
  }
</script>