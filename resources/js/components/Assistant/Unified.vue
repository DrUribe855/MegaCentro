<template>
  <div style="height: 100vh; overflow-y: scroll">
    <v-app>
      <v-main>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 my-6">
              <v-toolbar flat>
                <div class="row">
                  <div>
                    <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
                      Año actual
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
                      {{ date }}
                    </v-toolbar-title>
                  </div>
                </div>
                <div class="ml-2">
                  <!-- <v-btn
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
                    </v-btn> -->
                  <v-btn :loading="loadingPdf" :disabled="loadingPdf" color="red" class="ma-2 white--text" @click="pdf">
                    PDF
                    <v-icon right dark>
                      mdi-content-save
                    </v-icon>
                  </v-btn>
                </div>

                <v-menu bottom right>
                </v-menu>
              </v-toolbar>
            </div>
            <div id="element-to-pdf" class="col-12 row">
              <div class="col-sm-12 my-6">
                <div class="row flex justify-content-center mb-2">
                  <div class="text-center">
                    <h1>FORMULARIO RH - CONSOLIDADO ANUAL</h1>
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
                    <label class="form-label">AÑO</label>
                    <input type="text" class="form-control" :value="date">
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
                        <th rowspan="3" style="padding-bottom: 5em; padding-left: 1em; width: 0.2%">MES</th>
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
                        <th class="text-center px-0" style="font-size: 10px;">RECICLABES (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">ORDINARIOS-COMUNES (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">BIOSANITARIOS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">FARMACOS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">ANATOMOPATOLOGICOS(Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">CORTOPUNZANTES (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">ANIMALES (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">TÓNERES (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">LUMINARIAS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">RAEES (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">PILAS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">LIQUIDO FIJADOR (kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">TINTAS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">AMALGAMAS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">FUENTES ABIERTAS (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">FUENTES CERRADAS (Kg)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(month, i) in index">
                        <td class="px-0">{{ month }}</td>
                        <td class="text-center px-0" v-for="(residueId) in residueIds">
                          {{ revalidateResidue(residueId, i) }}
                        </td>
                      </tr>
                      <tr>
                        <td class="px-0">TOTAL</td>
                        <td class="text-center px-0" v-for="residueId in residueIds">
                          {{ revalidateTotal(residueId) }}
                        </td>
                      </tr>
                      <tr>
                        <td class="px-0">GRAN TOTAL</td>
                        <td colspan="23">{{ bigTotal }}</td>
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
  data() {
    return {
      loader: null,
      loading3: false,
      loaderPdf: null,
      loadingPdf: false,
      list_residues: [],
      data_residues: [[], []],
      user: {},
      role: '',
      index: ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE',],
      residueIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
      focus: new Date(),
      date: '',
      position: 0,
      bigTotal: 0,
      total_weight: [],
      data_total: [],
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
    this.setToday()
  },

  methods: {
    pdf() {
      this.loaderPdf = 'loadingPdf'
      var element = document.getElementById('element-to-pdf');
      var opt = {
        margin: 0.5,
        filename: `Reporte RH anual ${this.date}.pdf`,
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
      html2pdf().from(element).set(opt).save();
    },

    initialize(date) {
      if (date != '') {
        axios.get(`/residue/showUnified/${date}`).then(res => {
          this.total_weight = res.data.total;
          this.list_residues = res.data.residues;
          this.user = res.data.user;
          this.role = res.data.role;
          this.bigTotal = res.data.bigTotal[0].weight;
          this.getResidueValue();
        }).catch(error => {
        });
      }
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
        for (let j = 0; j < this.index.length; j++) {
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
          for (let j = 0; j < this.index.length; j++) {
            for (let l = 0; l < this.list_residues.length; l++) {
              if (this.list_residues[l].month == j+1 && this.list_residues[l].id_residue == i) {
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
      const year = this.focus.getFullYear();
      this.date = year;
      this.position = year;
      this.initialize(year);
    },

    prev() {
      this.position--;
      this.date = this.position
      this.initialize(this.position);
    },

    next() {
      this.position++;
      this.date = this.position
      this.initialize(this.position);
    },
  }
}
</script>