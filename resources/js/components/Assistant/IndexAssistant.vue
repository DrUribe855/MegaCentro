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
                    <v-btn fab text small color="grey darken-2" @click="prev">
                      <v-icon small> mdi-chevron-left </v-icon>
                    </v-btn>
                    <v-btn fab text small color="grey darken-2" @click="next">
                      <v-icon small> mdi-chevron-right </v-icon>
                    </v-btn>
                  </div>
                  <div>
                    <v-toolbar-title>
                      {{ date.toUpperCase() }}
                    </v-toolbar-title>
                  </div>
                </div>
                <div class="ml-2">
                  <v-btn
                    color="green"
                    class="ma-2 white--text"
                    @click="downloadExcel"
                  >
                    EXCEL
                    <v-icon right dark> mdi-download </v-icon>
                  </v-btn>
                  <v-btn color="red" class="ma-2 white--text" @click="pdf">
                    PDF
                    <v-icon right dark> mdi-download </v-icon>
                  </v-btn>
                </div>
              </v-toolbar>
            </div>
            <div id="element-to-pdf" class="col-12 row">
              <div class="col-sm-12 my-6">
                <div class="row flex justify-content-center mb-2">
                  <div class="text-center">
                    <h1>FORMULARIO RH</h1>
                  </div>
                  <img
                    src="../img/Imagen1.png"
                    alt="Logo Megacentro"
                    width="110em"
                    class="ml-6 img-fluid"
                  />
                </div>
                <div class="text-center my-6">
                  <h3>FUENTES DE GENERACIÓN Y CLASES DE RESIDUOS</h3>
                </div>
              </div>
              <div class="col-sm-12 row mx-sm-4 mx-2 mt-4">
                <div class="row col-12 m-0 p-0">
                  <div class="col-8">
                    <label class="form-label">NOMBRE DE LA INSTITUCIÓN</label>
                    <input
                      type="text"
                      class="form-control"
                      value="MEGACENTRO PINARES PROPIEDAD HORIZONTAL"
                    />
                  </div>
                  <div class="col-4">
                    <label class="form-label">FECHA</label>
                    <input
                      type="text"
                      class="form-control"
                      :value="date.toUpperCase()"
                    />
                  </div>
                </div>
                <div class="row col-12 m-0 p-0">
                  <div class="col-4">
                    <div>
                      <label class="form-label">DIRECCIÓN</label>
                      <input
                        type="text"
                        class="form-control"
                        value="CARRERA 18 # 12-75 PINARES SAN MARTIN"
                      />
                    </div>
                    <div class="mt-3">
                      <label class="form-label">NIVEL DE ATENCIÓN</label>
                      <input type="text" class="form-control" />
                    </div>
                  </div>
                  <div class="col-4">
                    <div>
                      <label class="form-label">CIUDAD</label>
                      <input type="text" class="form-control" value="PEREIRA" />
                    </div>
                    <div class="mt-3">
                      <label class="form-label">TELÉFONO</label>
                      <input
                        type="text"
                        class="form-control"
                        :value="user.phone"
                      />
                    </div>
                  </div>
                  <div class="col-4">
                    <div>
                      <label class="form-label">PROFESIONAL RESPOSABLE</label>
                      <input
                        type="text"
                        class="form-control"
                        :value="
                          user.name && user.last_name != undefined
                            ? user.name.toUpperCase() +
                              ' ' +
                              user.last_name.toUpperCase()
                            : ''
                        "
                      />
                    </div>
                    <div class="mt-3">
                      <label class="form-label">CARGO</label>
                      <input
                        type="text"
                        class="form-control"
                        :value="role.toUpperCase()"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="role != 'Recolector'" style="width: 100%;">
                <div class="col-sm-12 container">
                  <div class="table-responsive">
                    <div class="text-center mb-6">
                      <h4>TIPO DE RESIDUOS</h4>
                    </div>
                    <table class="table table-bordered" id="table">
                      <thead>
                        <tr>
                          <th
                            rowspan="2"
                            class="text-center px-0"
                            style="
                              padding-bottom: 5em;
                              padding-left: 1em;
                              width: 0.2%;
                            "
                          >
                            DÍA
                          </th>
                          <th
                            colspan="3"
                            class="text-center px-0"
                            style="width: 10%"
                          >
                            Residuos no peligrosos
                          </th>
                          <th
                            colspan="4"
                            class="text-center px-0"
                            style="width: 10%"
                          >
                            Residuos con riesgo biológico o infeccioso
                          </th>
                          <th
                            colspan="1"
                            rowspan="2"
                            class="text-center px-0"
                            style="width: 10%"
                          >
                            Radioactivos
                          </th>
                          <th
                            colspan="5"
                            class="text-center px-0"
                            style="width: 20%"
                          >
                            Otros residuos o desechos peligrosos
                          </th>
                        </tr>
                        <tr>
                          <th class="text-center px-0" style="font-size: 10px">
                            Aprovechables (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Aprovechables orgánicos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            No aprovechables (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Biosanitarios (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Anatomopatalogicos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Cortopunzantse (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            De animales (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Corrosivos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Explosivos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Reactivos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Toxicos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Inflamables (Kg)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="i in index">
                          <td class="text-center px-0">
                            {{ i <= 9 ? "0" + i : i }}
                          </td>
                          <td
                            class="text-center px-0"
                            v-for="residueId in residueIds"
                          >
                            {{ revalidateResidue(residueId, i) }}
                          </td>
                        </tr>
                        <tr>
                          <td class="text-center px-0">TOTAL</td>
                          <td
                            class="text-center px-0"
                            v-for="residueId in residueIds"
                          >
                            {{ revalidateTotal(residueId) }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div v-else style="width: 100%;">
                <div class="col-sm-12 container">
                  <div class="table-responsive">
                    <div class="text-center mb-6">
                      <h4>TIPO DE RESIDUOS</h4>
                    </div>
                    <table class="table table-bordered" id="table">
                      <thead>
                        <tr>
                          <th
                            rowspan="2"
                            class="text-center px-0"
                            style="
                              padding-bottom: 5em;
                              padding-left: 1em;
                              width: 0.2%;
                            "
                          >
                            DÍA
                          </th>
                          <th
                            colspan="3"
                            class="text-center px-0"
                            style="width: 10%"
                          >
                            Residuos no peligrosos
                          </th>
                          <th
                            colspan="5"
                            class="text-center px-0"
                            style="width: 10%"
                          >
                            Residuos con riesgo biológico o infeccioso
                          </th>
                          <th
                            colspan="1"
                            rowspan="2"
                            class="text-center px-0"
                            style="width: 10%"
                          >
                            Metales pesados
                          </th>
                          <th
                            colspan="5"
                            class="text-center px-0"
                            style="width: 20%"
                          >
                            Otros residuos o desechos peligrosos
                          </th>
                        </tr>
                        <tr>
                          <th class="text-center px-0" style="font-size: 10px">
                            Reciclables (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Aprovechables orgánicos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Ordinarios (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Biosanitarios (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Anatomopatalogicos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Cortopunzantse (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            De animales (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Farmacos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Corrosivos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Explosivos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Reactivos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Toxicos (Kg)
                          </th>
                          <th class="text-center px-0" style="font-size: 10px">
                            Inflamables (Kg)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="i in index">
                          <td class="text-center px-0">
                            {{ i <= 9 ? "0" + i : i }}
                          </td>
                          <td
                            class="text-center px-0"
                            v-for="residueId in residueIds2"
                          >
                            {{ revalidateResidue(residueId, i) }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
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
import * as XLSX from "xlsx-js-style";
export default {
  data() {
    return {
      clinic: "",
      role: "",
      clinic_number: "",
      text: "",
      date: "",
      dateAxios: "",
      type: "month",
      loader: null,
      loaderPdf: null,
      loading3: false,
      loadingPdf: false,
      alert: true,
      showAlert: true,
      list_residues: [],
      total_weight: [],
      list_residues_excel: [],
      items: [],
      residueIds: [1, 0, 2, 3, 4, 5, 6, 0, 10, 0, 0, 11, 0],
      residueIds2: [1, 0, 2, 3, 5, 6, 0, 4, 11, 0, 0, 0, 0],
      user: {},
      index: 0,
      position: 0,
      focus: new Date(),
    };
  },
  watch: {
    loader() {
      const l = this.loader;
      this[l] = !this[l];

      setTimeout(() => (this[l] = false), 2000);

      this.loader = null;
    },

    loaderPdf() {
      const l = this.loaderPdf;
      this[l] = !this[l];

      setTimeout(() => (this[l] = false), 1000);

      this.loaderPdf = null;
    },
  },
  created() {
    this.setToday();
    this.clinicNumber();
  },

  methods: {
    clinicNumber() {
      axios
        .get("/residue/clinicNumber")
        .then((res) => {
          this.items = res.data.clinic;
        })
        .catch((error) => {});
    },

    pdf() {
      this.loaderPdf = "loadingPdf";
      var element = document.getElementById("element-to-pdf");
      if (this.alert) {
        var opt = {
          margin: 0.5,
          filename: `Reporte RH mensual ${this.date}.pdf`,
          image: { type: "jpeg", quality: 0.98 },
          html2canvas: { scale: 3 },
          jsPDF: {
            unit: "mm",
            format: "a5",
            orientation: "landscape",
            width: 500,
            height: 297,
          },
        };
      } else if (this.clinic != "") {
        var opt = {
          margin: 0.5,
          filename: `Reporte RH consultorio ${this.clinic_number} ${this.date}.pdf`,
          image: { type: "jpeg", quality: 0.98 },
          html2canvas: { scale: 3 },
          jsPDF: {
            unit: "mm",
            format: "a5",
            orientation: "landscape",
            width: 500,
            height: 297,
          },
        };
      } else {
        this.alertFalse("La descarga no se puede hacer");
        return false;
      }
      html2pdf().from(element).set(opt).save();
    },

    initialize(date) {
      if (date != "") {
        this.total_weight = [];
        this.list_residues = [];
        this.list_residues_excel = [];
        axios
          .get(`/residue/generalShow/${date}`)
          .then((res) => {
            console.log(res.data)
            if (res.data.residues.length != 0) {
              this.total_weight = res.data.total;
              this.list_residues = res.data.residues;
            }
            this.user = res.data.user;
            this.role = res.data.role;
            this.index = res.data.date;
          })
          .catch((error) => {});
      }
    },

    revalidateResidue(residueId, index) {
      if (this.list_residues[index] && this.list_residues[index][residueId]) {
        return this.list_residues[index][residueId].total_weight
          ? this.list_residues[index][residueId].total_weight
          : 0;
      }
      return "0";
    },

    revalidateTotal(residueId) {
      if (this.total_weight[residueId]) {
        return this.total_weight[residueId].total_weight
          ? this.total_weight[residueId].total_weight
          : 0;
      }
      return "0";
    },

    setToday() {
      this.focus = new Date();
      const options = { month: "long" };
      const month = this.focus.toLocaleDateString("es-ES", options);
      const year = this.focus.getFullYear();
      const monthNumber = this.focus.getMonth() + 1;
      this.position =
        monthNumber <= 9
          ? (this.position = "0" + monthNumber)
          : (this.position = monthNumber);
      this.date = month + " " + year;
      this.dateAxios = year + "-" + monthNumber;
      console.log(this.role);
      this.initialize(this.dateAxios);
    },

    prev() {
      const newFocus = new Date(this.focus);
      newFocus.setMonth(newFocus.getMonth() - 1);
      const options = { month: "long" };
      const monthName = newFocus.toLocaleDateString("es-ES", options);
      const year = newFocus.getFullYear();
      this.focus = newFocus;
      this.date = monthName + " " + year;
      this.position--;
      if (this.position == 0) {
        this.position = 12;
      }
      if (this.position <= 9) {
        this.position = "0" + this.position--;
      }
      this.dateAxios = year + "-" + this.position;
      this.initialize(this.dateAxios);
    },

    next() {
      const newFocus = new Date(this.focus);
      newFocus.setMonth(newFocus.getMonth() + 1);
      const options = { month: "long" };
      const monthName = newFocus.toLocaleDateString("es-ES", options);
      const year = newFocus.getFullYear();
      this.focus = newFocus;
      this.date = monthName + " " + year;
      this.position++;
      if (this.position > 12) {
        this.position = 1;
      }
      if (this.position <= 9) {
        this.position = "0" + this.position++;
      }
      this.dateAxios = year + "-" + this.position;
      this.initialize(this.dateAxios);
    },

    alertFalse(text) {
      swal({
        title: "ERROR!",
        text: text,
        icon: "error",
      });
    },

    downloadExcel() {
      const workbook = XLSX.utils.book_new();
      const worksheet = XLSX.utils.aoa_to_sheet([[]]);

      const row0 = [
        ["FORMULARIO RH", "", "", "", "", "", "", "", "", "", "", "", "", ""],
      ];

      XLSX.utils.sheet_add_aoa(worksheet, row0, { origin: "A1" });

      const row1 = [
        [
          "FUENTES DE GENERACIÓN Y CLASES DE RESIDUOS",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
        ],
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row1, { origin: "A2" });

      const row2 = [
        ["NOMBRE DE LA INSTITUCIÓN:  MEGACENTRO PINARES PROPIEDAD HORIZONTAL"],
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row2, { origin: "A3" });

      const row3 = [["DIRECCIÓN:  CARRERA 18 # 12-75 PINARES SAN MARTIN"]];
      XLSX.utils.sheet_add_aoa(worksheet, row3, { origin: "A4" });

      const row4 = [[`TELÉFONO:  ${this.user.phone}`]];
      XLSX.utils.sheet_add_aoa(worksheet, row4, { origin: "A5" });

      const row5 = [["CIUDAD:  PEREIRA"]];
      XLSX.utils.sheet_add_aoa(worksheet, row5, { origin: "A6" });

      const row6 = [
        [
          `PROFESIONAL RESPOSABLE:  ${
            this.user.name && this.user.last_name != undefined
              ? this.user.name.toUpperCase() +
                " " +
                this.user.last_name.toUpperCase()
              : ""
          }`,
        ],
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row6, { origin: "A7" });

      const row7 = [[`CARGO:  ${this.role.toUpperCase()}`]];
      XLSX.utils.sheet_add_aoa(worksheet, row7, { origin: "A8" });

      const row8 = [["NIVEL DE ATENCIÓN: "]];
      XLSX.utils.sheet_add_aoa(worksheet, row8, { origin: "A9" });

      const row10 = [[`FECHA:  ${this.date.toUpperCase()}`]];
      XLSX.utils.sheet_add_aoa(worksheet, row10, { origin: "A10" });

      const row11 = [
        [
          "TIPO DE RESIDUOS",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
          "",
        ],
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row11, { origin: "A11" });

      const row12 = [
        ["DÍA", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""],
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row12, { origin: "A12" });

      const row13 = [
        [
          "",
          "Residuos no peligrosos",
          "",
          "",
          "Residuos con riesgo biológico o infeccioso",
          "",
          "",
          "",
          "Radioactivos",
          "Otros residuos o desechos peligrosos",
          "",
          "",
          "",
          "",
        ],
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row13, { origin: "A13" });

      const row14 = [
        [
          "",
          "Aprovechables ",
          "Aprovechables orgánicos",
          "No aprovechables",
          "Biosanitarios",
          "Anatomopatalogicos ",
          "Cortopunzantse",
          "De animales",
          "",
          "Corrosivos",
          "Explosivos ",
          "Reactivos",
          "Toxicos ",
          "Inflamables",
        ],
      ];
      XLSX.utils.sheet_add_aoa(worksheet, row14, { origin: "A14" });

      const merges = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 13 } }, // FORMULARIO RH
        { s: { r: 1, c: 0 }, e: { r: 1, c: 13 } }, // FORMULARIO RH

        { s: { r: 10, c: 0 }, e: { r: 10, c: 13 } }, // TIPO DE RESIDUOS

        { s: { r: 11, c: 0 }, e: { r: 13, c: 0 } }, // DÍA
        { s: { r: 11, c: 1 }, e: { r: 11, c: 4 } }, // RESIDUOS
        { s: { r: 11, c: 5 }, e: { r: 11, c: 13 } }, // RESIDUOS PELIGROSOS

        { s: { r: 12, c: 1 }, e: { r: 12, c: 3 } }, // NO PELIGROSOS
        { s: { r: 12, c: 4 }, e: { r: 12, c: 7 } }, // INFECCIOSOS O RIESGO BIOLOGICO
        { s: { r: 12, c: 8 }, e: { r: 13, c: 8 } }, // QUIMICOS
        { s: { r: 12, c: 9 }, e: { r: 12, c: 13 } }, // RADIACTIVOS
      ];
      worksheet["!merges"] = merges;
      worksheet["!rows"] = [];
      worksheet["!rows"][13] = { hpx: 30 };
      worksheet["!cols"] = [];
      worksheet["!cols"][13] = {
        wch: 15,
        vertical: "top",
        horizontal: "center",
      };

      const colListResidue = [
        "B14",
        "C14",
        "D14",
        "E14",
        "F14",
        "G14",
        "H14",
        "I14",
        "J14",
        "K14",
        "L14",
        "M14",
        "N14",
        "O14",
        "P14",
        "Q14",
      ];
      for (const itm of colListResidue) {
        if (worksheet[itm]) {
          worksheet[itm].s = {
            font: { sz: 8 },
            alignment: { wrapText: true, horizontal: "center" },
          };
        }
      }

      const colListTypeR = [
        "A12",
        "B12",
        "F12",
        "B13",
        "F13",
        "K13",
        "P13",
        "A11",
        "E13",
        "I13",
        "J13",
      ];
      for (const itm of colListTypeR) {
        if (worksheet[itm]) {
          worksheet[itm].s = {
            font: { bold: true },
            alignment: { horizontal: "center" },
          };
        }
      }

      const colListTitle = ["A1", "A2"];
      for (const itm of colListTitle) {
        if (worksheet[itm]) {
          worksheet[itm].s = {
            font: { sz: 20, bold: true },
            alignment: { horizontal: "center" },
          };
        }
      }

      for (let i = 1; i <= this.index; i++) {
        for (let j = 0; j < this.residueIds.length; j++) {
          if (!this.list_residues_excel[i]) {
            this.$set(this.list_residues_excel, i, {});
          }
          this.list_residues_excel[i][j] = 0;
          if (this.list_residues[i] && this.list_residues[i][j]) {
            var length = this.list_residues[i][j].total_weight.toString();
            if (length.length >= 5) {
              if (j == 1) {
                this.list_residues_excel[i][0] =
                  this.list_residues[i][j].total_weight;
                this.list_residues_excel[i][1] = 0;
              } else if (j == 2) {
                this.list_residues_excel[i][2] =
                  this.list_residues[i][j].total_weight;
              } else if (j == 10) {
                this.list_residues_excel[i][8] =
                  this.list_residues[i][j].total_weight;
                this.list_residues_excel[i][10] = 0;
              } else {
                this.list_residues_excel[i][j] =
                  this.list_residues[i][j].total_weight;
              }
            } else {
              if (j == 1) {
                this.list_residues_excel[i][0] =
                  this.list_residues[i][j].total_weight;
                this.list_residues_excel[i][1] = 0;
              } else if (j == 2) {
                this.list_residues_excel[i][2] =
                  this.list_residues[i][j].total_weight;
              } else if (j == 10) {
                this.list_residues_excel[i][8] =
                  this.list_residues[i][j].total_weight;
                this.list_residues_excel[i][10] = 0;
              } else {
                this.list_residues_excel[i][j] =
                  this.list_residues[i][j].total_weight;
              }
            }
          }
        }
      }
      let data = this.list_residues_excel.map((item) => {
        return Object.values(item);
      });
      XLSX.utils.sheet_add_aoa(worksheet, data, { origin: "B14" });

      let day = 1;
      for (let i = 15; i <= 15 + this.index - 1; i++) {
        const row15 = [[day]];
        XLSX.utils.sheet_add_aoa(worksheet, row15, { origin: "A" + i });
        day++;
      }

      let total = new Array(this.residueIds.length).fill(0);
      for (let j = 0; j < this.residueIds.length; j++) {
        if (this.total_weight[j]) {
          if (j == 1) {
            total[0] = this.total_weight[j].total_weight;
            total[j] = 0;
          } else if (j == 10) {
            total[8] = this.total_weight[j].total_weight;
            total[10] = 0;
          } else {
            total[j] = this.total_weight[j].total_weight;
          }
        }
      }
      total.splice(0, 0, "Total");
      let dataT = [total];
      XLSX.utils.sheet_add_aoa(worksheet, dataT, {
        origin: "A" + (15 + this.index),
      });

      let colList = 0;
      for (let i = 15; i <= this.index + 15; i++) {
        colList = [
          "A" + i,
          "B" + i,
          "C" + i,
          "D" + i,
          "E" + i,
          "F" + i,
          "G" + i,
          "H" + i,
          "I" + i,
          "J" + i,
          "K" + i,
          "L" + i,
          "M" + i,
          "N" + i,
          "O" + i,
          "P" + i,
          "Q" + i,
        ];
        for (const itm of colList) {
          if (worksheet[itm]) {
            worksheet[itm].s = {
              alignment: { horizontal: "center" },
            };
          }
        }
      }

      XLSX.utils.book_append_sheet(workbook, worksheet, `Datos Excel`);
      XLSX.writeFile(workbook, `Reporte RH mensual ${this.date}.xlsx`);
    },
  },
};
</script>
