<template>
  <v-app>
    <v-main>
      <div style="height: 100vh; overflow-y: scroll; overflow-x: scroll;">
        <div class="shadow-sm p-3 mb-5 bg-body-tertiary rounded">
          <h1 class="ml-3">Consultorios asignados</h1>
          <v-form class="ml-3">
            <v-container>
              <v-row>
                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="month"
                    label="Ingrese el mes"
                    required
                  ></v-text-field>
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="year"
                    label="Ingrese el año"
                    required
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-form>
        </div>
        <div>
          <div v-for="clinic in clinics" class="shadow-sm p-3 mb-5 bg-body-tertiary rounded border border-black fs-4">
            <p>Numero de consultorio: {{clinic.clinic.clinic_number}}</p>
            <p>Numero de torre: {{clinic.clinic.tower_id}}</p>
            <div>
                <v-form class="ml-3">
                  <v-container style="overflow-x: scroll;">
                    <v-row>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos biodegradables"
                          required
                        ></v-text-field>
                      </v-col>

                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos reciclables"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos inertes"
                          required
                        ></v-text-field>
                      </v-col>
                      <!-- <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos ordinarios"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos biosanitarios"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos anatomopatologicos"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos cortopunzantes"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos animales"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos fármacos"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos citotóxicos"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Kilos de residuos metales pesados"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Kilos de residuos reactivos"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Kilos de contenedores presurizados"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Kilos de aceites usados"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Fuentes abiertas"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Fuentes cerradas"
                          required
                        ></v-text-field>
                      </v-col> -->
                    </v-row>
                </v-container>
              </v-form>
            </div>
            <div>
              <v-form class="ml-3">
                  <v-container style="overflow-x: scroll;">
                    <v-row>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Cantidad de bolsas"
                          required
                        ></v-text-field>
                      </v-col>

                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos reciclables"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos inertes"
                          required
                        ></v-text-field>
                      </v-col>
                      <!-- <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos ordinarios"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos biosanitarios"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos anatomopatologicos"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos cortopunzantes"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos animales"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos fármacos"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        md="3"
                      >
                        <v-text-field
                          label="Kilos de residuos citotóxicos"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Kilos de residuos metales pesados"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Kilos de residuos reactivos"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Kilos de contenedores presurizados"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Kilos de aceites usados"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Fuentes abiertas"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                      >
                        <v-text-field
                          label="Fuentes cerradas"
                          required
                        ></v-text-field>
                      </v-col> -->
                    </v-row>
                </v-container>
              </v-form>
            </div>
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
      clinics: [],
      month: "",
      year: "", 

    }),

    computed: {
      
    },
    created () {
      this.getClinics();
    },

    methods: {
      getClinics(){
        axios.get('/collector/clinics').then(res => {
            console.log('Respuesta del servidor');
            console.log(res.data);
            this.clinics = res.data.clinics;
            console.log(this.clinics);
        }).catch(error => {
            console.log('Error en axios: ');
            console.log(error);
            console.log(error.response);
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