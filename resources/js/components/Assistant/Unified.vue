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
                  <v-btn color="green" class="ma-2 white--text" @click="downloadExcel"
                    >
                      EXCEL
                      <v-icon
                        right
                        dark
                      >
                        mdi-download
                      </v-icon>
                    </v-btn>
                  <v-btn color="red" class="ma-2 white--text" @click="pdf">
                    PDF
                    <v-icon right dark>
                      mdi-download
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
                        <th rowspan="2" class="text-center px-0"
                          style="padding-bottom: 5em; padding-left: 1em; width: 0.2%">Mes</th>
                        <th colspan="4" class="text-center px-0" style="width: 10%;">Residuos no peligrosos</th>
                        <th colspan="5" class="text-center px-0" style="width: 10%;">Residuos con riesgo biológico o infeccioso</th>
                        <th colspan="1" rowspan="2" class="text-center px-0" style="width: 10%;">Radioactivos</th>
                        <th colspan="6" class="text-center px-0" style="width: 20%;">Otros residuos o desechos peligrosos</th>
                        <th class="text-center px-0" style="width: 5%;">Total mes</th>
                      </tr>
                      <tr>
                        <th class="text-center px-0" style="font-size: 10px;">Aprovechables (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Aprovechables orgánicos (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">No aprovechables (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Total</th>
                        <th class="text-center px-0" style="font-size: 10px;">Biosanitarios (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Anatomopatalogicos (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Cortopunzantse (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">De animales (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Total</th>
                        <th class="text-center px-0" style="font-size: 10px;">Corrosivos (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Explosivos (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Reactivos (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Toxicos (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Inflamables (Kg)</th>
                        <th class="text-center px-0" style="font-size: 10px;">Total</th>
                        <th class="text-center px-0" style="font-size: 10px;"></th>
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
import * as XLSX from 'xlsx-js-style';
export default {
  data() {
    return {
      loader: null,
      loading3: false,
      loaderPdf: null,
      loadingPdf: false,
      list_residues: [],
      user: {},
      role: '',
      index: ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE',],
      residueIds: [1, 0, 2, 20, 3, 4, 5, 6, 21, 0, 8, 0, 0, 11, 0, 0, 22],
      list_residues_excel: [],
      focus: new Date(),
      date: '',
      position: 0,
      bigTotal: 0,
      total_weight: [],
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
        this.total_weight = [];
        this.list_residues = [];
        this.list_residues_excel = [],
        this.bigTotal = 0;
        axios.get(`/residue/showUnified/${date}`).then(res => {
          if (res.data.residues.length != 0) {
            this.total_weight = res.data.total;
            this.list_residues = res.data.residues;
            this.bigTotal = res.data.bigTotal[0].weight
          }
          this.user = res.data.user;
          this.role = res.data.role;
        }).catch(error => {
        });
      }
    },

    revalidateResidue(residueId, index) {
      if(this.list_residues[index+1]){
        if(residueId == 20){
          let total = 0
          if(this.list_residues[index+1][1].total_weight){
            total += this.list_residues[index+1][1].total_weight
          }
          if(this.list_residues[index+1][2].total_weight){
            total += this.list_residues[index+1][2].total_weight

          }
          return total.toFixed(1)
        }
      }
      if(this.list_residues[index+1]){
        if(residueId == 21){
          let total = 0
          if(this.list_residues[index+1][3].total_weight){
            total += this.list_residues[index+1][3].total_weight
          }
          if(this.list_residues[index+1][4].total_weight){
            total += this.list_residues[index+1][4].total_weight
          }
          if(this.list_residues[index+1][5].total_weight){
            total += this.list_residues[index+1][5].total_weight
          }
          if(this.list_residues[index+1][6].total_weight){
            total += this.list_residues[index+1][6].total_weight
          }
          return total.toFixed(1)
        }
      }

      if(this.list_residues[index+1]){
        if(residueId == 22){
          let total = 0
          if(this.list_residues[index+1][8].total_weight){
            total += this.list_residues[index+1][8].total_weight
          }
          if(this.list_residues[index+1][11].total_weight){
            total += this.list_residues[index+1][11].total_weight
          }
          return total.toFixed(1)
        }
      }

      if (this.list_residues[index+1] && this.list_residues[index+1][residueId]) {


          return (this.list_residues[index+1][residueId].total_weight) ?  parseFloat(this.list_residues[index+1][residueId].total_weight).toFixed(1) : 0

      }
      return '0';
    },

    revalidateTotal(residueId) {
      if(residueId == 20){
        let total = 0
        if(this.total_weight[1]){
          total+= this.total_weight[1].total_weight
        }
        if(this.total_weight[2]){
          total+= this.total_weight[2].total_weight
        }
        return total.toFixed(1)
      }
      if(residueId == 21){
        let total = 0
        if(this.total_weight[3]){
          total+= this.total_weight[3].total_weight
        }
        if(this.total_weight[4]){
          total+= this.total_weight[4].total_weight
        }
        if(this.total_weight[5]){
          total+= this.total_weight[5].total_weight
        }
        if(this.total_weight[6]){
          total+= this.total_weight[6].total_weight
        }
        return total.toFixed(1)
      }
      if(residueId == 22){
        let total = 0
        if(this.total_weight[8]){
          total+= this.total_weight[8].total_weight
        }
        if(this.total_weight[11]){
          total+= this.total_weight[11].total_weight
        }
        return total.toFixed(1)
      }
      if (this.total_weight[residueId]) {
          return (this.total_weight[residueId].total_weight) ? parseFloat(this.total_weight[residueId].total_weight).toFixed(1) : 0;

      }
      return '0';
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

    downloadExcel(){
      const workbook = XLSX.utils.book_new();
      const worksheet = XLSX.utils.aoa_to_sheet([[]]);

      const row0 = [
        ['FORMULARIO RH - CONSOLIDADO ANUAL', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']
      ];

      XLSX.utils.sheet_add_aoa(worksheet, row0, { origin: "A1" });

      const row1 = [
        ['FUENTES DE GENERACIÓN Y CLASES DE RESIDUOS', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row1, { origin: "A2" });

      const row2 = [
        ['NOMBRE DE LA INSTITUCIÓN:  MEGACENTRO PINARES PROPIEDAD HORIZONTAL']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row2, { origin: "A3" });

      const row3 = [
        ['DIRECCIÓN:  CARRERA 18 # 12-75 PINARES SAN MARTIN']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row3, { origin: "A4" });

      const row4 = [
        [`TELÉFONO:  ${this.user.phone}`]
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row4, { origin: "A5" });

      const row5 = [
        ['CIUDAD:  PEREIRA']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row5, { origin: "A6" });

      const row6 = [
        [`PROFESIONAL RESPOSABLE:  ${this.user.name && this.user.last_name != undefined ? this.user.name.toUpperCase() + ' ' + this.user.last_name.toUpperCase() : ''}`]
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row6, { origin: "A7" });

      const row7 = [
        [`CARGO:  ${this.role.toUpperCase()}`]
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row7, { origin: "A8" });

      const row8 = [
        ['NIVEL DE ATENCIÓN: ']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row8, { origin: "A9" });

      const row10 = [
        [`AÑO:  ${this.date}`]
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row10, { origin: "A10" });

      const row11 = [
        ['TIPO DE RESIDUOS', '', '', '', '', '', '', '', '', '', '', '', '', '', '']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row11, { origin: "A11" });

      const row12 = [
        ['MES', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row12, { origin: "A12" });

      const row13 = [
        ['', 'Residuos no peligrosos', '', '', '', 'Residuos con riesgo biológico o infeccioso', '','', '', '', 'Radioactivos', 'Otros residuos o desechos peligrosos', '', '', '', '','']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row13, { origin: "A13" });

      const row14 = [
        ['' ,'Aprovechables ', 'Aprovechables orgánicos', 'No aprovechables','Total', 'Biosanitarios', 'Anatomopatalogicos ', 'Cortopunzantse', 'De animales', 'Total', '', 'Corrosivos', 'Explosivos ', 'Reactivos', 'Toxicos ', 'Inflamables', 'Total']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row14, { origin: "A14" });

      const colListResidue = ['B14', 'C14', 'D14', 'E14', 'F14', 'G14', 'H14', 'I14', 'J14', 'K14', 'L14', 'M14', 'N14', 'O14', 'P14', 'Q14'];
      for (const itm of colListResidue) {
        if (worksheet[itm]) {
          worksheet[itm].s = {
            font: {sz: 9},
            alignment: {wrapText: true, horizontal:'center'},
            // border: {top:{style:"thin", color:{rgb: '000000'}}, bottom:{style:"thin", color:{rgb: '000000'}}, left: {style:"thin", color:{rgb: '000000'}}, right: {style:"thin", color:{rgb: '000000'}}}
          };
        }
      }

      const colListTypeR = ['A12', 'B13', 'F12', 'B13', 'F13', 'K13', 'P13', 'A11', 'L13'];
      for (const itm of colListTypeR) {
        if (worksheet[itm]) {
          worksheet[itm].s = {
            font: {bold: true},
            alignment: {horizontal:'center'},
          };
        }
      }

      const colListTitle = ['A1', 'A2'];
      for (const itm of colListTitle) {
        if (worksheet[itm]) {
          worksheet[itm].s = {
            font: {sz: 20, bold:true},
            alignment: {horizontal:'center'},
          };
        }
      }
      for (let i = 1; i <= this.index.length; i++) {
          if (!this.list_residues_excel[i]) {
            this.$set(this.list_residues_excel, i, {});
          }
          if(this.list_residues[i]){
            this.list_residues_excel[i][0] = this.list_residues[i][1] ? this.list_residues[i][1].total_weight : 0;
            this.list_residues_excel[i][1] = 0;
            this.list_residues_excel[i][2] = this.list_residues[i][2] ? this.list_residues[i][2].total_weight : 0;
            this.list_residues_excel[i][3] = this.list_residues_excel[i][0] + this.list_residues_excel[i][2];
            this.list_residues_excel[i][4] = this.list_residues[i][3] ? this.list_residues[i][3].total_weight : 0;
            this.list_residues_excel[i][5] = this.list_residues[i][4] ? this.list_residues[i][4].total_weight : 0;
            this.list_residues_excel[i][6] = this.list_residues[i][5] ? this.list_residues[i][5].total_weight : 0;
            this.list_residues_excel[i][7] = this.list_residues[i][6] ? this.list_residues[i][6].total_weight : 0;
            this.list_residues_excel[i][8] = this.list_residues_excel[i][4] + this.list_residues_excel[i][5] + this.list_residues_excel[i][6] + this.list_residues_excel[i][7];
            this.list_residues_excel[i][9] = 0;
            this.list_residues_excel[i][10] = this.list_residues[i][8] ? this.list_residues[i][8].total_weight : 0;
            this.list_residues_excel[i][11] = 0;
            this.list_residues_excel[i][12] = 0;
            this.list_residues_excel[i][13] = this.list_residues[i][11] ? this.list_residues[i][11].total_weight : 0;
            this.list_residues_excel[i][14] = 0;
            this.list_residues_excel[i][15] = this.list_residues_excel[i][10] + this.list_residues_excel[i][13];
          }else {
            this.list_residues_excel[i][0] = 0;
            this.list_residues_excel[i][1] = 0;
            this.list_residues_excel[i][2] = 0;
            this.list_residues_excel[i][3] = 0;
            this.list_residues_excel[i][4] = 0;
            this.list_residues_excel[i][5] = 0;
            this.list_residues_excel[i][6] = 0;
            this.list_residues_excel[i][7] = 0;
            this.list_residues_excel[i][8] = 0;
            this.list_residues_excel[i][9] = 0;
            this.list_residues_excel[i][10] = 0;
            this.list_residues_excel[i][11] = 0;
            this.list_residues_excel[i][12] = 0;
            this.list_residues_excel[i][13] = 0;
            this.list_residues_excel[i][14] = 0;
            this.list_residues_excel[i][15] = 0;
          }

      }

      let data = this.list_residues_excel.map(item => {
        return Object.values(item);
      });
      XLSX.utils.sheet_add_aoa(worksheet, data, { origin: "B14" });

      let day = 0;
      for (let i = 15; i <= 15+this.index.length-1; i++) {
        const row15 = [
          [this.index[day]]
        ];
        XLSX.utils.sheet_add_aoa(worksheet, row15, { origin: "A"+i });
        day++;
      }


      let total = new Array(16).fill(0);
      total[0] = (this.total_weight[1].total_weight) ? this.total_weight[1].total_weight : 0
      total[2] = (this.total_weight[2].total_weight) ? this.total_weight[2].total_weight : 0
      total[3] = 0
      total[8] = 0
      total[15] = 0
      this.list_residues_excel.forEach(element => {
        total[3] += element[3]
        total[8] += element[8]
        total[15] += element[15]
      })
      total[4] = (this.total_weight[3].total_weight) ? this.total_weight[3].total_weight : 0
      total[5] = (this.total_weight[4].total_weight) ? this.total_weight[4].total_weight : 0
      total[6] = (this.total_weight[5].total_weight) ? this.total_weight[5].total_weight : 0
      total[7] = (this.total_weight[6].total_weight) ? this.total_weight[6].total_weight : 0
      total[10] = (this.total_weight[8].total_weight) ? this.total_weight[8].total_weight : 0
      total[13] = (this.total_weight[11].total_weight) ? this.total_weight[11].total_weight : 0


      total.splice(0, 0, 'TOTAL');
      let dataT = [total];
      XLSX.utils.sheet_add_aoa(worksheet, dataT, { origin: "A"+(15+this.index.length) });

      const row16 = [
        ['GRAN TOTAL', this.bigTotal, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row16, { origin: "A"+(16+this.index.length) });
      day++;

      let colList = 0;
      for (let i = 15; i <= this.index.length+15; i++) {
        colList = ['A'+i, 'B'+i, 'C'+i, 'D'+i, 'E'+i, 'F'+i, 'G'+i, 'H'+i, 'I'+i, 'J'+i, 'K'+i, 'L'+i, 'M'+i, 'N'+i, 'O'+i, 'P'+i, 'Q'+i,];
        for (const itm of colList) {
          if (worksheet[itm]) {
            worksheet[itm].s = {
              alignment: {horizontal:'center'},
            };
          }
        }
      }

      const merges = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 16 } }, // FORMULARIO RH
        { s: { r: 1, c: 0 }, e: { r: 1, c: 16 } }, // FORMULARIO RH

        { s: { r: 10, c: 0 }, e: { r: 10, c: 16 } }, // TIPO DE RESIDUOS

        { s: { r: 11, c: 0 }, e: { r: 13, c: 0 } }, // DÍA
        { s: { r: 11, c: 1 }, e: { r: 11, c: 4 } }, // RESIDUOS
        { s: { r: 11, c: 5 }, e: { r: 11, c: 16 } }, // RESIDUOS PELIGROSOS

        { s: { r: 12, c: 1 }, e: { r: 12, c: 4 } },  // NO PELIGROSOS
        { s: { r: 12, c: 5 }, e: { r: 12, c: 9 } },  // INFECCIOSOS O RIESGO BIOLOGICO
        { s: { r: 12, c: 10 }, e: { r: 13, c: 10 } }, // QUIMICOS
        { s: { r: 12, c: 11 }, e: { r: 12, c: 16 } }, // RADIACTIVOS

        { s: { r: 15+this.index.length, c: 1 }, e: { r: 15+this.index.length, c: 16 } } // GRAN TOTAL
      ];
      worksheet['!merges'] = merges;
      worksheet['!rows'] = [];
      worksheet['!rows'][13] = { hpx: 30 };
      worksheet['!cols'] = [];
      worksheet['!cols'][13] =  { wch: 15, vertical:"top", horizontal:"center" };


      XLSX.utils.book_append_sheet(workbook, worksheet, `Datos Excel`);
      XLSX.writeFile(workbook, `Reporte RH anual ${this.date}.xlsx`);
    }
  }
}
</script>
