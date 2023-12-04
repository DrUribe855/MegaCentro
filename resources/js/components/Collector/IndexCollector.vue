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
                >
                  <v-text-field
                    v-model="general_data.month"
                    label="Ingrese el mes"
                    type="number"
                    required
                  ></v-text-field>
                </v-col>

                <v-col
                  cols="12"
                  md="3"
                >
                  <v-text-field
                    v-model="general_data.year"
                    label="Ingrese el año"
                    type="number"
                    required
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
                  md="2"
                >
                  <v-btn
                    color="primary"
                    @click="save()"
                  >Registrar recolección</v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-form>
        </div>
        <div>
          <div v-for="(clinic, index) in clinics" class="mt-3">
            <v-expansion-panels>
              <v-expansion-panel>
                <v-expansion-panel-header>Consultorio: {{clinic.clinic.clinic_number  }}  / Torre: {{clinic.clinic.tower_id}}</v-expansion-panel-header>
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
                                v-model="datos[index].data[i].weight"
                              >adasda</v-text-field>
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
                              v-model="datos[index].data[i].bags"
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
      items: ['Diurno', 'Nocturno', 'Extra'],
      clinics: [], 
      residues: [],
      datos: [],
      general_data: {
        month: '',
        year: '',
        schedule: '',
      }
    }),

    computed: {
    },
    created () {
      this.getClinics();
    },

    methods: {
      getClinics(){
        axios.get('/collector/clinics').then(res => {
            this.clinics = res.data.clinics;
            this.residues = res.data.residues;
            res.data.clinics.forEach(clinic => {
            let aux = {
                clinic_id: clinic.clinic_id,
                data: []
              };
              res.data.residues.forEach(residue => {
                aux.data.push({
                    residue_id: residue.id,
                    weight: 0,
                    bags: 0,
                });
              }); 
              this.datos.push(aux);
            });
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
        console.log("request: ",request);
        axios.post('/collector/saveCollection', request).then(resp => {
          console.log("request: ", resp);
          if(resp.data.message == 'Año incorrecto' && resp.data.status == false){
            this.showAlert('Error', 'Ingrese un año válido', 'error');
          }else if(resp.data.message == 'Mes invalido' && resp.data.status == false){
            this.showAlert('Error', 'Ingrese un mes válido', 'error');
          }else if(resp.data.message == 'Horario no ingresado' && resp.data.status == false){
            this.showAlert('Error', 'Ingrese un horario', 'error');
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
    },
  }
</script>