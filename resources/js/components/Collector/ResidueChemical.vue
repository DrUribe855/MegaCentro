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
                  >Registrar recolección</v-btn>
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
              </v-row>
            </v-container>
          </v-form>
        </div>
        <div>
          <div v-for="(panel, indexLocalStorage) in filteredPanels"  :key="indexLocalStorage" class="mt-3">
            <v-expansion-panels>
              <v-expansion-panel v-if="datos[indexLocalStorage2+indexLocalStorage].show">
                <v-expansion-panel-header >Consultorio: {{panel.clinic_number  }}  / Torre: {{panel.tower_id}}</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-form>
                    <v-container>
                      <div>
                        <h5> Kilogramos </h5>
                          <v-row>
                            <v-col
                              v-for="(residue,i) in residues" :key="residue.id"
                              cols="12"
                              md="3"
                            >
                              <v-text-field
                                :label="residue.residue_name"
                                v-model="datos[indexLocalStorage2+indexLocalStorage].data[i].weight"
                                @change="changeValue()"
                              ></v-text-field>
                            </v-col>
                          </v-row>
                      </div>
                      <div>
                        <h5 class="mt-3">Bolsas</h5>
                        <v-row>
                          <v-col
                            v-for="(residue, i) in residues" :key="residue.id"
                            cols="12"
                            md="3"
                          >
                            <v-text-field
                              :label="residue.residue_name"
                              v-model="datos[indexLocalStorage2+indexLocalStorage].data[i].bags"
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
            this.residues = res.data.residueChemical;
            this.general_data.month = res.data.month;
            this.general_data.year = res.data.year;
            res.data.clinics.forEach(clinic => {
              let aux = {
                clinic_id: clinic.id,
                data: [],
                show: true,
                towerNumber: clinic.tower_id,
                clinicNumber: clinic.clinic_number,

              };
              res.data.residueChemical.forEach(residue => {
                aux.data.push({
                    residue_id: residue.id,
                    weight: 0,
                    bags: 0,
                });
              });
              this.datos.push(aux);
            });

            if(localStorage.getItem("residueChemicalCollection")){
              let localData = JSON.parse(localStorage.getItem("residueChemicalCollection"));
              if(localData.length < this.datos.length){
                for (let i = 0; i < this.datos.length; i++) {
                  if(localData[i] == undefined){
                    localData.push(this.datos[i]);
                  }
                }
                this.datos = localData;
                localStorage.setItem("residueChemicalCollection", JSON.stringify(localData));
              }else if(localData.length > this.datos.length){

                //Filtro para encontrar el o los objetos faltantes en el arreglo datos.
                const objetosFaltantes = localData.filter((localDato) => !this.datos.some((dato) => dato.clinicNumber === localDato.clinicNumber));


                objetosFaltantes.forEach((objetoFaltante) => {
                  localData.splice(this.datos.indexOf(objetoFaltante), 1);
                });

                this.datos = localData;
                localStorage.setItem("residueChemicalCollection", JSON.stringify(localData));
              }else{
                this.datos = localData;
              }

            }else{
              localStorage.setItem("residueChemicalCollection", JSON.stringify(this.datos));
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
        console.log("request asdas: ",request);
        axios.post('/collector/saveCollection', request).then(resp => {
          console.log("request: ", resp);
          if(resp.data.message == "Recolección registrada"){
            this.cleanInputs();
            this.showAlert('Validado', 'Se han registrado las recolecciones con éxito', 'success');
          }else if(resp.data.message == "Datos incompletos"){
            this.collectionValidation(resp.data.collectionData.clinicNumber, resp.data.collectionData.residue_id);
          }else if(resp.data.message == 'Datos incorrectos en la fecha'){
            this.showAlert('Error', 'Falta diligenciar el horario de recolección', 'error');
          }
        }).catch(error => {
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
        localStorage.setItem("residueChemicalCollection", JSON.stringify(this.datos));
        const localData = JSON.parse(localStorage.getItem("residueChemicalCollection"));
      },
      filterClinics() {

        if(this.clinicNumber != '' && this.towerNumber == ''){
         for (let i = 0; i < this.datos.length; i++) {
            if(this.datos[i].clinicNumber.includes(this.clinicNumber)){
              this.datos[i].show = true;
            }else{
              this.datos[i].show = false;
            }
          }
        }

        if(this.towerNumber != '' &&  this.clinicNumber == ''){
          for (let i = 0; i < this.datos.length; i++) {
            if(this.datos[i].towerNumber == this.towerNumber){
              this.datos[i].show = true;
            }else{
              this.datos[i].show = false;
            }
          }
        }

        if(this.towerNumber != '' && this.clinicNumber != ''){
          for (let i = 0; i < this.datos.length; i++) {
            if(this.datos[i].towerNumber == this.towerNumber && this.datos[i].clinicNumber.includes(this.clinicNumber)){
              this.datos[i].show = true;
            }else{
              this.datos[i].show = false;
            }
          }
        }

        if(this.towerNumber == '' && this.clinicNumber == ''){
          for (let i = 0; i < this.datos.length; i++) {
            this.datos[i].show = true;
          }
        }
      },
      collectionValidation(clinicNumber, residueId){
        let clinicNumberValidation;
        let residueNameValidation;
        for (let i = 0; i < this.residues.length; i++) {
          if(this.residues[i].id == residueId){
            residueNameValidation = this.residues[i].residue_name;
            console.log("El residuo que se encuentra sin llenar es el: ", residueNameValidation);
            break;
          }
        }

        for(let j = 0; j < this.clinics.length; j++){
          if(this.clinics[j].clinic_id == clinicNumber){
            clinicNumberValidation = this.clinics[j].clinic.clinic_number;
            console.log("En el consultorio numero: ", clinicNumberValidation);
            break;
          }
        }

        this.showAlert("Aviso",  `Falta diligenciar información del residuo ${residueNameValidation} en el consultorio ${clinicNumberValidation}`, "warning");
      },
      cleanInputs(){
        this.general_data.schedule = '';
        console.log(this.general_data);
        for (let i = 0; i < this.datos.length; i++) {
          for (let j = 0; j < this.residues.length; j++) {
            this.datos[i].data[j].bags = 0;
            this.datos[i].data[j].weight = 0;
            localStorage.setItem("residueChemicalCollection", JSON.stringify(this.datos));
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




array_push()