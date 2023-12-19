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
                                    <v-btn :loading="loadingPdf" :disabled="loadingPdf" color="red" class="ma-2 white--text"
                                        @click="pdf">
                                        PDF
                                        <v-icon right dark>
                                            mdi-cloud-upload
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
                                        <h1>FORMULARIO RH - CONSOLIDADO ANUAL CONTINUACIÓN</h1>
                                    </div>
                                    <img src="../img/Imagen1.png" alt="Logo Megacentro" width="110em"
                                        class="ml-6 img-fluid">
                                </div>
                                <div class="text-center my-6">
                                    <h3>REGISTRO MENSUAL DE GENERACIÓN DE RESIDUOS HOSPITALARIOS Y SIMILARES</h3>
                                </div>
                            </div>
                            <div class="col-sm-12 container">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td class="text-center p-0" style="font-size: 13px;">MES</td>
                                                <td class="text-center p-1" style="font-size: 13px;">KG/RESIDUO</td>
                                                <td class="text-center p-1" style="font-size: 13px;">No BOLSAS ENTREGADAS</td>
                                                <td class="text-center p-1" style="font-size: 13px;">PRETRATAMIENTO USADO DESACTIVACIÓN</td>
                                                <td class="text-center p-1" style="font-size: 13px;">ALMACENAMIENTO (DÍAS)</td>
                                                <td class="text-center p-1" style="font-size: 13px;">TIPO DE TRATAMIENTO</td>
                                                <td class="text-center p-1" style="font-size: 13px;">HORA DE RECOLECCIÓN</td>
                                                <td class="text-center p-1" style="font-size: 13px;">DOT. PERSONAL GENERADOR ADECUADA</td>
                                                <td class="text-center p-1" style="font-size: 13px;">DOT PERSONAL PSEG ADECUADA</td>
                                                <td class="text-center p-1" style="font-size: 13px;">COLOR DE BOLSA UTILIZADA</td>
                                                <td class="text-center p-1" style="font-size: 13px;">PROCESO PRODUCTIVO</td>
                                                <td class="text-center p-1" style="font-size: 13px;">RESIDUOS SIMILAR KG/DIA</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(month, i) in index">
                                                <td class="pl-0 pr-1">{{ month }}</td>
                                                <td class="text-center" >{{ revalidateResidue(i,0) }}</td>
                                                <td class="text-center" >{{ revalidateResidue(i,1) }}</td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'">BENZALDINA</td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'">1</td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'">TERMINCO</td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'">6:30:00 A.M</td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'">SI</td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'">SI</td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'">ROJO</td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'">SALUD</td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center px-2" v-if="month == 'ENERO'">0</td>
                                            </tr>
                                            <tr>
                                                <td class="">TOTAL</td>
                                                <td class="text-center">{{ revalidateResidue(0,2) }}</td>
                                                <td class="text-center">{{ revalidateResidue(0,3) }}</td>
                                                <td colspan="10" class="text-center"></td>
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
            data_residues: [],
            data_garbage_bags: [],
            user: {},
            role: '',
            index: ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE',],
            residueIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
            focus: new Date(),
            date: '',
            position: 0,
            total: [],
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
                margin: 2,
                filename: `Reporte RH anual continuación ${this.date}.pdf`,
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
                axios.get(`/residue/showUnifiedContinuation/${date}`).then(res => {
                    console.log("Respuesta del servidor");
                    console.log("Datos de consulta ", res.data);
                    this.total = res.data.total;
                    this.list_residues = res.data.residues;
                    this.getResidueValue();
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            }
        },

        revalidateResidue(index, type) {
            if (this.data_residues[index] !== undefined && type == 0) {
                return this.data_residues[index];
            }else if (this.data_garbage_bags[index] != undefined && type == 1){
                return this.formater(this.data_garbage_bags[index]);
            }else if (this.total[index] != undefined) {
                if (type == 2) {
                    return this.formater(this.total[index].total_weight)
                }else if(type == 3){
                    return this.formater(this.total[index].garbage_bags)
                }
            }
            return 0;
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
            this.data_residues = [];
            this.data_garbage_bags = [];
            for (let i = 0; i < this.index.length; i++) {
                if (!this.data_residues[i]) {
                    this.$set(this.data_residues, i);
                }
            }
            if (this.list_residues.length != 0) {
                for (let i = 0; i < this.index.length; i++) {
                    for (let l = 0; l < this.list_residues.length; l++) {
                        if (this.list_residues[l].month == i + 1) {
                            this.data_residues[i] = this.list_residues[l].total_weight;
                            this.data_garbage_bags[i] = this.list_residues[l].garbage_bags;
                            this.data_residues[i] = this.formater(this.data_residues[i]);
                        }
                    }
                }
                console.log(this.data_garbage_bags);
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
            console.log("prev " + this.position);
            this.date = this.position
            this.initialize(this.position);
        },

        next() {
            this.position++;
            console.log("next ", this.position);
            this.date = this.position
            this.initialize(this.position);
        },
    }
}
</script>