<template>
  <div style="height: 100vh; overflow-y: scroll">
    <v-app>
      <v-main>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 my-6">
              <v-toolbar
                flat
              >
                <div class="row">
                  <div>
                    <v-btn
                      outlined
                      class="mr-4"
                      color="grey darken-2"
                      @click="setToday"
                    >
                      Mes actual
                    </v-btn>
                  </div>
                  <div>
                    <v-btn
                      fab
                      text
                      small
                      color="grey darken-2"
                      @click="prev"
                      >
                      <v-icon small>
                        mdi-chevron-left
                      </v-icon>
                    </v-btn>
                    <v-btn
                      fab
                      text
                      small
                      color="grey darken-2"
                      @click="next"
                      >
                      <v-icon small>
                        mdi-chevron-right
                      </v-icon>
                    </v-btn>
                  </div>
                  <div>
                    <v-toolbar-title>
                      {{ date }}
                    </v-toolbar-title>
                  </div>
                </div>
                <div class="ml-2">
                  <v-btn
                    :loading="loading3"
                    :disabled="loading3"
                    color="green"
                    class="ma-2 white--text"
                    @click="loader = 'loading3'"
                  >
                    EXCEL
                    <v-icon
                      right
                      dark
                    >
                      mdi-cloud-upload
                    </v-icon>
                  </v-btn>
                  <v-btn
                    :loading="loadingPdf"
                    :disabled="loadingPdf"
                    color="red"
                    class="ma-2 white--text"
                    @click="pdf"
                  >
                    PDF
                    <v-icon
                      right
                      dark
                    >
                      mdi-cloud-upload
                    </v-icon>
                  </v-btn>
                </div>

                <v-menu
                  bottom
                  right
                >
                </v-menu>
              </v-toolbar>
            </div>
            <div id="element-to-pdf" class="col-12 row">
              <div class="col-sm-12 my-6">
                <div class="row flex justify-content-center mb-2">
                  <div class="text-center">
                    <h1>FORMULARIO RH1</h1>
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
                    <!-- <v-text-field
                      label="NOMBRE DE LA INSTITUCIÓN"
                    ></v-text-field> -->
                  </div>
                  <div class="col-4">
                    <label class="form-label">FECHA</label>
                    <input type="text" class="form-control" v-model="date">
                    <!-- <v-text-field
                      v-model="date"
                      label="FECHA" 
                    ></v-text-field>-->
                  </div>
                </div>
                <div class="row col-12 m-0 p-0">
                  <div class="col-4">
                    <div>
                      <label class="form-label">DIRECCIÓN</label>
                      <input type="text" class="form-control" value="CARRERA 18 # 12-75 PINARES SAN MARTIN">
                      <!-- <v-text-field
                        label="DIRECCIÓN"
                      ></v-text-field> -->
                    </div>
                    <div>
                      <label class="form-label">NIVEL DE ATENCIÓN</label>
                      <input type="text" class="form-control">
                      <!-- <v-text-field
                        label="NIVEL DE ATENCIÓN"
                      ></v-text-field> -->
                    </div>
                  </div>
                  <div class="col-4">
                    <div>
                      <label class="form-label">CIUDAD</label>
                      <input type="text" class="form-control" value="PEREIRA">
                      <!-- <v-text-field
                        label="CIUDAD"
                      ></v-text-field> -->
                    </div>
                    <div>
                      <label class="form-label">TELÉFONO</label>
                      <input type="text" class="form-control" value="3213222">
                      <!-- <v-text-field
                        label="TELÉFONO"
                      ></v-text-field> -->
                    </div>
                  </div>
                  <div class="col-4">
                    <div>
                      <label class="form-label">PROFESIONAL RESPOSABLE</label>
                      <input type="text" class="form-control" value="DANIELA MONTOYA">
                      <!-- <v-text-field
                        label="PROFESIONAL RESPOSABLE"
                      ></v-text-field> -->
                    </div>
                    <div>
                      <label class="form-label">CARGO</label>
                      <input type="text" class="form-control" value="AUXILIAR CONTABLE">
                      <!-- <v-text-field
                        label="CARGO"
                      ></v-text-field> -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 container">
                <div class="table-responsive">
                  <div class="text-center mb-6">
                    <h4>TIPO DE RESIDUOS</h4>
                  </div>
                  </style>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th rowspan="3" style="padding-bottom: 5em; padding-left: 1em; width: 0.2%">DÍA</th>
                        <th colspan="4" class="text-center" style="width: 10%;">RESIDUOS NO PELIGROSOS</th>
                        <th colspan="12" class="text-center" style="width: 20%;">RESIDUOS PELIGROSOS</th>
                      </tr>
                      <tr>
                        <th class="text-center" colspan="4"></th>
                        <th class="text-center" colspan="4">INFECCIOSOS O RIESGO BIOLOGICO</th>
                        <th class="text-center" colspan="6">QUIMICOS</th>
                        <th class="text-center" colspan="2">RADIACTIVOS</th>
                      </tr>
                      <tr>
                        <th class="text-center p-0" style="font-size: 10.5px;">BIODEGRADABLES (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">RECICLABES (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">INERTES (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">ORDINARIOS-COMUNES (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">BIOSANITARIOS (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">ANATOMOPATOLOGICOS (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">CORTOPUNZANTES (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">ANIMALES (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">FARMACOS (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">CITOTÓXICOS (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">METALES PESADOS (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">REACTIVOS (Kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">CONTENEDORES PRESURIZADOS</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">ACEITES USADOS (kg)</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">FUENTES ABIERTAS</th>
                        <th class="text-center p-0" style="font-size: 10.5px;">FUENTES CERRADAS</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="i in index" class="text-center">
                        <td>{{ i <= 9 ? '0'+i : i }}</td>
                        <td v-for="residueId in residueIds">
                          {{ getResidueValue(residueId, i) }}
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

<script>
  import html2pdf from "html2pdf.js";
  import accounting from 'accounting'
  export default {
    data(){
      return {
        loader: null,
        loading3: false,
        loaderPdf: null,
        loadingPdf: false,
        list_residues: [],
        index: 31,
        residueIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
        type: 'month',
        typeToLabel: {
          month: 'Month',
          week: 'Week',
          day: 'Day',
          '4day': '4 Days',
        },
        focus: new Date(),
        date: '',
        dateAxios: '',
        position: 0,
      }
    },
    watch: {
      loader () {
        const l = this.loader
        this[l] = !this[l]

        setTimeout(() => (this[l] = false), 2000)

        this.loader = null
      },

      loaderPdf () {
        const l = this.loaderPdf
        this[l] = !this[l]

        setTimeout(() => (this[l] = false), 1000)

        this.loaderPdf = null
      },
    },
    created(){
      this.setToday();
      this.initialize(this.dateAxios);
    },

    methods: {
      pdf(){
        this.loaderPdf = 'loadingPdf'
        var element = document.getElementById('element-to-pdf');
        var opt = {
          margin:       2.1,
          filename:     'Reporte RH mensual.pdf',
          image:        { type: 'jpeg', quality: 0.98 },
          html2canvas:  { scale: 3 },
          jsPDF:        { 
            unit: 'mm', 
            format: 'a4', 
            orientation: 'landscape', 
            width: 500, 
            height: 297 
          }
        };
        html2pdf().from(element).set(opt).save();
      },

      initialize(date){
        console.log("date ", date);
        if (date != '') {
          axios.get(`/residue/generalShow/${date}`).then(res =>{
            console.log("Respuesta del servidor");
            console.log("Datos de consulta ",res.data);
            this.list_residues = res.data.residues;
          }).catch(error => {
            console.log("Error en servidor");
            console.log(error);
            console.log(error.response);
          }); 
        }
      },

      getResidueValue(residueId, day) {
        var weigth = 0;
        for (let i = 0; i < this.list_residues.length; i++) {
          if (this.list_residues[i].residue_id === residueId && this.list_residues[i].day_of_month === day) {
            weigth += this.list_residues[i].total_weight;
            weigth = accounting.formatMoney(weigth, {
              symbol: '', 
              precision: '',
              thousand: ',',
              decimal: '.'
            });
          }
        }
        return weigth;
      },

      setToday () {
        this.focus = new Date();
        const options = { month: 'long' };
        const month = this.focus.toLocaleDateString('es-ES', options);
        const year = this.focus.getFullYear();
        const monthNumber = this.focus.getMonth() + 1;
        this.position = monthNumber <= 9 ? this.position = '0'+ monthNumber : this.position = monthNumber;
        this.date = month+ ' '+ year;
        this.dateAxios = year+'-'+monthNumber;
        this.initialize(this.dateAxios);
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
        console.log("FECHA ",this.dateAxios);
        this.initialize(this.dateAxios);
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
        console.log(this.date);
      },
    }
  }
</script>