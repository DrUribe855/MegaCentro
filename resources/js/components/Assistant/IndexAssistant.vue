<template>
  <div style="height: 100vh; overflow-y: scroll">
    <v-app>
      <v-main>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 my-6">
              <v-container class="col-6 v-alert-container">
                <v-alert v-if="!showAlert && !alert" shaped type="info" transition="scale-transition">
                  Ahora puede buscar el consultorio que desea consultar
                </v-alert>
                <v-alert v-if="!showAlert && alert" shaped type="info" transition="scale-transition">
                  Ahora esta en el formualario general
                </v-alert>
              </v-container>
              <v-toolbar flat>
                <div class="row">
                  <div>
                    <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
                      Mes actual
                    </v-btn>
                  </div>
                  <div>
                    <v-btn fab text small color="grey darken-2" @click="prev">
                      <v-icon small>
                        mdi-chevron-left
                      </v-icon>
                    </v-btn>
                    <v-btn fab text small color="grey darken-2" @click="next">
                      <v-icon small>
                        mdi-chevron-right
                      </v-icon>
                    </v-btn>
                  </div>
                  <div>
                    <v-toolbar-title>
                      {{ date.toUpperCase() }}
                    </v-toolbar-title>
                  </div>
                </div>
                <div class="ml-2">
                  <!-- <v-btn
                    :loading="loading3"
                    :disabled="loading3"
                    color="green"
                    class="ma-2 white--text"
                    @click="loader = 'loading3'">
                    EXCEL
                    <v-icon
                        right
                        dark>
                        mdi-cloud-upload
                    </v-icon>
                  </v-btn> -->
                  <v-btn :loading="loadingPdf" :disabled="loadingPdf" color="red" class="ma-2 white--text" @click="pdf">
                    PDF
                    <v-icon right dark>
                      mdi-content-save
                    </v-icon>
                  </v-btn>
                </div>
              </v-toolbar>
              <div class="m-6" style="width: 200px !important;">
                <v-autocomplete v-if="!alert" v-model="clinic" :items="items" label="Consultorio"
                  @input="clinicSelected()" item-text="clinic_number" item-value='id'></v-autocomplete>
                <v-btn v-if="!alert" class="ma-2" color="primary" dark
                  @click="mounted(), alert = true, changeData(), showAlert = false">
                  <v-icon dark left>
                    mdi-arrow-left
                  </v-icon>
                  volver
                </v-btn>
                <v-btn v-if="alert" color="primary"
                  @click="mounted(), alert = !alert, showAlert = !showAlert, changeData()">
                  <v-icon left>
                    fas fa-search
                  </v-icon>
                  Consultorios
                </v-btn>
              </div>
            </div>
            <div id="element-to-pdf" class="col-12 row">
              <div class="col-sm-12 my-6">
                <div class="row flex justify-content-center mb-2">
                  <div class="text-center">
                    <h1>FORMULARIO RH {{ !alert && clinic != null ? 'CONSULTORIO ' + clinic_number : '' }}</h1>
                  </div>
                  <img src="../img/Imagen1.png" alt="Logo Megacentro" width="110em" class="ml-6 img-fluid">
                </div>
                <div class="text-center my-6">
                  <h3>FUENTES DE GENERACIÓN Y CLASES DE RESIDUOS</h3>
                </div>
              </div>
              <div class="col-sm-12 row mx-sm-4 mx-2 mt-4">
                <div class="row col-12 m-0 p-0">
                  <div class="col-8">
                    <label class="form-label">NOMBRE DE LA INSTITUCIÓN</label>
                    <input type="text" class="form-control" value="MEGACENTRO PINARES PROPIEDAD HORIZONTAL">
                  </div>
                  <div class="col-4">
                    <label class="form-label">FECHA</label>
                    <input type="text" class="form-control" :value="date.toUpperCase()">
                  </div>
                </div>
                <div class="row col-12 m-0 p-0">
                  <div class="col-4">
                    <div>
                      <label class="form-label">DIRECCIÓN</label>
                      <input type="text" class="form-control" value="CARRERA 18 # 12-75 PINARES SAN MARTIN">
                    </div>
                    <div class="mt-3">
                      <label class="form-label">NIVEL DE ATENCIÓN</label>
                      <input type="text" class="form-control">
                    </div>
                  </div>
                  <div class="col-4">
                    <div>
                      <label class="form-label">CIUDAD</label>
                      <input type="text" class="form-control" value="PEREIRA">
                    </div>
                    <div class="mt-3">
                      <label class="form-label">TELÉFONO</label>
                      <input type="text" class="form-control" :value="user.phone">
                    </div>
                  </div>
                  <div class="col-4">
                    <div>
                      <label class="form-label">PROFESIONAL RESPOSABLE</label>
                      <input type="text" class="form-control"
                        :value="user.name && user.last_name != undefined ? user.name.toUpperCase() + ' ' + user.last_name.toUpperCase() : ''">
                    </div>
                    <div class="mt-3">
                      <label class="form-label">CARGO</label>
                      <input type="text" class="form-control" :value="role.toUpperCase()">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 container">
                <div class="table-responsive">
                  <div class="text-center mb-6">
                    <h4>TIPO DE RESIDUOS</h4>
                  </div>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th rowspan="3" class="text-center px-0"
                          style="padding-bottom: 5em; padding-left: 1em; width: 0.2%">DÍA</th>
                        <th colspan="4" class="text-center px-0" style="width: 10%;">RESIDUOS NO
                          PELIGROSOS</th>
                        <th colspan="12" class="text-center px-0" style="width: 20%;">RESIDUOS
                          PELIGROSOS</th>
                      </tr>
                      <tr>
                        <th class="text-center px-0" colspan="4"></th>
                        <th class="text-center px-0" colspan="4">INFECCIOSOS O RIESGO BIOLOGICO</th>
                        <th class="text-center px-0" colspan="6">QUIMICOS</th>
                        <th class="text-center px-0" colspan="2">RADIACTIVOS</th>
                      </tr>
                      <tr>
                        <th class="text-center px-0" style="font-size: 10px;">BIODEGRADABLES (Kg)
                        </th>
                        <th class="text-center px-0" style="font-size: 10px;">RECICLABES (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">INERTES (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">ORDINARIOS-COMUNES
                          (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">BIOSANITARIOS (Kg)
                        </th>
                        <th class="text-center px-0" style="font-size: 10px;">ANATOMOPATOLOGICOS
                          (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">CORTOPUNZANTES (Kg)
                        </th>
                        <th class="text-center px-0" style="font-size: 10px;">ANIMALES (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">FARMACOS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">CITOTÓXICOS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">METALES PESADOS (Kg)
                        </th>
                        <th class="text-center px-0" style="font-size: 10px;">REACTIVOS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">CONTENEDORES
                          PRESURIZADOS</th>
                        <th class="text-center px-0" style="font-size: 10px;">ACEITES USADOS (kg)
                        </th>
                        <th class="text-center px-0" style="font-size: 10px;">FUENTES ABIERTAS</th>
                        <th class="text-center px-0" style="font-size: 10px;">FUENTES CERRADAS</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="i in index">
                        <td class="text-center px-0">{{ i <= 9 ? '0' + i : i }}</td>
                        <td class="text-center px-0" v-for="residueId in residueIds">
                          {{ revalidateResidue(residueId, i) }}
                        </td>
                      </tr>
                      <tr>
                        <td class="text-center px-0">TOTAL</td>
                        <td class="text-center px-0" v-for="residueId in residueIds">
                          {{ revalidateTotal(residueId) }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </v-main>
    </v-app>
  </div>
</template>
<style>
  .v-alert-container {
    position: fixed;
    left: 50%;
    top: 8%;
    width: 500px !important;
    z-index: 999;
  }
</style>
<script>
  import html2pdf from "html2pdf.js";
  import accounting from 'accounting'
  export default {
    data() {
      return {
        search: '',
        clinic: '',
        role: '',
        clinic_number: '',
        text: '',
        date: '',
        dateAxios: '',
        type: 'month',
        loader: null,
        loaderPdf: null,
        loading3: false,
        loadingPdf: false,
        alert: true,
        showAlert: true,
        list_residues: [],
        list_residues_clinic: [],
        list_residues_temp: [],
        total_weight: [],
        total_weight_clinic: [],
        total_weight_temp: [],
        data_total: [],
        items: [],
        data_residues: [[], []],
        residueIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
        user: {},
        typeToLabel: {
          month: 'Month',
          week: 'Week',
          day: 'Day',
          '4day': '4 Days',
        },
        index: 31,
        position: 0,
        focus: new Date(),
      }
    },
    watch: {
      loader() {
        const l = this.loader
        this[l] = !this[l]

        setTimeout(() => (this[l] = false), 2000)

        this.loader = null
      },

      loaderPdf() {
        const l = this.loaderPdf
        this[l] = !this[l]

        setTimeout(() => (this[l] = false), 1000)

        this.loaderPdf = null
      },
    },
    created() {
      this.setToday();
      this.clinicNumber();
    },

    methods: {
      clinicNumber() {
        axios.get('/residue/clinicNumber').then(res => {
          this.items = res.data.clinic;
        }).catch(error => {
        });
      },

      pdf() {
        this.loaderPdf = 'loadingPdf'
        var element = document.getElementById('element-to-pdf');
        if (this.alert) {
          var opt = {
            margin: 0.5,
            filename: `Reporte RH mensual ${this.date}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 3 },
            jsPDF: {
              unit: 'mm',
              format: 'a4',
              orientation: 'landscape',
              width: 500,
              height: 297
            }
          };
        }else if(this.clinic != ''){
          var opt = {
            margin: 0.5,
            filename: `Reporte RH consultorio ${this.clinic_number} ${this.date}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 3 },
            jsPDF: {
              unit: 'mm',
              format: 'a4',
              orientation: 'landscape',
              width: 500,
              height: 297
            }
          };
        }else{
          this.alertFalse('La descarga no se puede hacer');
          return false;
        }
        html2pdf().from(element).set(opt).save();
      },

      initialize(date){
        if (date != '') {
          axios.get(`/residue/generalShow/${date}`).then(res =>{
            this.total_weight = res.data.total;
            this.total_weight_temp = this.total_weight;
            this.list_residues = res.data.residues;
            this.list_residues_temp = this.list_residues;
            this.user = res.data.user;
            this.role = res.data.role;
            this.index = res.data.date;
            this.changeData();
          }).catch(error => {
          });
        }
      },

      clinicInitialize(id) {
        if (this.dateAxios != '' && id != '') {
          axios.get(`/residue/clinicSelected/${this.dateAxios}/${id}`).then(res => {
            this.list_residues_clinic = res.data.clinicResidue;
            this.total_weight_clinic = res.data.totalClinic;
            this.changeData();
          }).catch(error => {
          });
        }
      },

      changeData() {
        if (this.alert) {
          this.list_residues = this.list_residues_temp;
          this.total_weight = this.total_weight_temp;
        } else {
          this.list_residues = this.list_residues_clinic;
          this.total_weight = this.total_weight_clinic;
        }
        this.getResidueValue();
      },

      revalidateResidue(residueId, index) {
        if (this.data_residues[residueId] && this.data_residues[residueId][index] !== undefined) {
          return this.data_residues[residueId][index];
        }
        return '0';
      },

      revalidateTotal(residueId) {
        if (this.data_total[residueId] !== undefined) {
          return this.data_total[residueId];
        }
        return '0';
      },

      formater(total) {
        total = accounting.formatMoney(total, {
          symbol: '',
          precision: '',
          thousand: ',',
          decimal: '.'
        });
        return total;
      },

      getResidueValue() {
        this.data_total = [];
        for (let i = 0; i < this.residueIds.length; i++) {
          if (!this.data_total[i]) {
            this.$set(this.data_total, i);
          }
          for (let j = 1; j <= this.index; j++) {
            if (!this.data_residues[i]) {
              this.$set(this.data_residues, i, []);
            }
            this.$set(this.data_residues[i], j, 0);
          }
        }
        if (this.list_residues.length != 0) {
          for (let i = 0; i < this.residueIds.length; i++) {
            for (let p = 0; p < this.total_weight.length; p++) {
              if (this.total_weight[p].id_residue == i) {
                this.data_total[i] = this.total_weight[p].weight;
                this.data_total[i] = this.formater(this.data_total[i]);
              }
            }
            for (let j = 1; j <= this.index; j++) {
              for (let l = 0; l < this.list_residues.length; l++) {
                if (this.list_residues[l].day == j && this.list_residues[l].id_residue == i) {
                  this.data_residues[i][j] = this.list_residues[l].total_weight;
                  this.data_residues[i][j] = this.formater(this.data_residues[i][j]);
                }
              }
            }
          }
        }
      },

      setToday() {
        this.focus = new Date();
        const options = { month: 'long' };
        const month = this.focus.toLocaleDateString('es-ES', options);
        const year = this.focus.getFullYear();
        const monthNumber = this.focus.getMonth() + 1;
        this.position = monthNumber <= 9 ? this.position = '0'+ monthNumber : this.position = monthNumber;
        this.date = month+ ' '+ year;
        this.dateAxios = year+'-'+monthNumber;
        this.initialize(this.dateAxios);
        this.clinicInitialize(this.clinic);
      },

      prev () {
        const newFocus = new Date(this.focus);
        newFocus.setMonth(newFocus.getMonth() - 1);
        const options = { month: 'long' };
        const monthName = newFocus.toLocaleDateString('es-ES', options); 
        const year = newFocus.getFullYear();
        this.focus = newFocus;
        this.date = monthName+ ' '+ year
        this.position--;
        if (this.position == 0) {
          this.position = 12;
        }
        if (this.position <= 9 ) {
          this.position = '0'+this.position--;
        }
        this.dateAxios = year+'-'+this.position;
        this.initialize(this.dateAxios);
        this.clinicInitialize(this.clinic);
      },

      next () {
        const newFocus = new Date(this.focus);
        newFocus.setMonth(newFocus.getMonth() + 1);
        const options = { month: 'long' };
        const monthName = newFocus.toLocaleDateString('es-ES', options);
        const year = newFocus.getFullYear();
        this.focus = newFocus;
        this.date = monthName+ ' '+ year
        this.position++;
        if (this.position > 12) {
          this.position = 1;
        }
        if (this.position <= 9 ) {
          this.position = '0'+this.position++;
        }
        this.dateAxios = year+'-'+this.position;
        this.initialize(this.dateAxios);
        this.clinicInitialize(this.clinic);
      },

      mounted() {
        setTimeout(() => {
          this.showAlert = true;
        }, 5000);
      },

      clinicSelected() {
        for (let i = 0; i < this.items.length; i++) {
          if (this.items[i].id == this.clinic) {
            this.clinic_number = this.items[i].clinic_number;
            break;
          }
        }
        this.clinicInitialize(this.clinic);
      },

      alertFalse(text){
        swal({
          title: "ERROR!",
          text: text,
          icon: "error",
        });
      },
    }
  }
</script>