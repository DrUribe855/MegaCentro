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
                                    <v-btn
                                        :loading="loading3"
                                        :disabled="loading3"
                                        color="green"
                                        class="ma-2 white--text"
                                        @click="downloadExcel"
                                        >
                                        EXCEL
                                        <v-icon
                                            right
                                            dark
                                        >
                                            mdi-download
                                        </v-icon>
                                    </v-btn>
                                    <v-btn :loading="loadingPdf" :disabled="loadingPdf" color="red" class="ma-2 white--text"
                                        @click="pdf">
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
                                                <td class="text-center p-1" style="font-size: 13px;">DOT PERSONAL PSE ADECUADA</td>
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
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'"></td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'"></td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'"></td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'"></td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'"></td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'"></td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'"></td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center" v-if="month == 'ENERO'"></td>
                                                <td rowspan="12" style="padding-top: 20.5%;" class="text-center px-2" v-if="month == 'ENERO'"></td>
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
import * as XLSX from 'xlsx-js-style';
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
                this.total = [];
                axios.get(`/residue/showUnifiedContinuation/${date}`).then(res => {
                    this.total = res.data.total;
                    this.list_residues = res.data.residues;
                    this.getResidueValue();
                }).catch(error => {
                });
            }
        },

        revalidateResidue(index, type) {
            if (this.data_residues[index] !== undefined && type == 0) {
                return this.data_residues[index].toFixed(2);
            }else if (this.data_garbage_bags[index] != undefined && type == 1){
                return this.data_garbage_bags[index];
            }else if (this.total[index] != undefined) {
                if (type == 2) {
                    return  this.total[index].total_weight.toFixed(2);
                }else if(type == 3){
                    return this.total[index].garbage_bags;
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
                            this.data_residues[i] = this.data_residues[i];
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

        downloadExcel(){
            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.aoa_to_sheet([[]]);

            const row0 = [ 
                ['FORMULARIO RH - CONSOLIDADO ANUAL CONTINUACIÓN', '', '', '', '', '', '', '', '', '', ''] 
            ];

            XLSX.utils.sheet_add_aoa(worksheet, row0, { origin: "A1" });

            const row1 = [ 
                ['REGISTRO MENSUAL DE GENERACIÓN DE RESIDUOS HOSPITALARIOS Y SIMILARES', '', '', '', '', '', '', '', '', '', '']
            ];
            XLSX.utils.sheet_add_aoa(worksheet, row1, { origin: "A2" });

            const row2 = [ 
                ['MES', 'KG/Residuo', 'No. Bolsas Entregadas', 'Pretratamiento usado de desactivación', 'Almacena miento (días)', 'Tipo de tratamiento', 'Hora de recolección', 'Dotación personal General', 'Dotación PSEA adecuada?', 'Color bolsa utilizada', 'Proceso productivo', 'Residuos similar (KG/día)']
            ];
            XLSX.utils.sheet_add_aoa(worksheet, row2, { origin: "A3" });

            const merges = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: 11 } },
                { s: { r: 1, c: 0 }, e: { r: 1, c: 11 } },
                
                { s: { r: 2, c: 3 }, e: { r: 15, c: 3 } }, // Pretratamiento usado de desactivación
                { s: { r: 2, c: 4 }, e: { r: 15, c: 4 } }, // Almacena miento (días)
                { s: { r: 2, c: 5 }, e: { r: 15, c: 5 } }, // Tipo de tratamiento
                { s: { r: 2, c: 6 }, e: { r: 15, c: 6 } }, // Hora de recolección
                { s: { r: 2, c: 7 }, e: { r: 15, c: 7 } }, // Dotación personal Genera
                { s: { r: 2, c: 8 }, e: { r: 15, c: 8 } }, //  Dotación PSEA adecuada?
                { s: { r: 2, c: 9 }, e: { r: 15, c: 9 } }, // Color bolsa utilizada
                { s: { r: 2, c: 10 }, e: { r: 15, c: 10 } }, // Proceso productivo
                { s: { r: 2, c: 11 }, e: { r: 15, c: 11 } }, // Residuos similar (KG/día)
            ];
            worksheet['!merges'] = merges;

            const colListTitle = ['A1', 'A2'];
            for (const itm of colListTitle) {
                if (worksheet[itm]) {
                    worksheet[itm].s = {
                        font: {sz: 20, bold:true},
                        alignment: {horizontal:'center'},
                    };
                }
            }

            const colListType = ['A3', 'B3', 'C3', 'D3', 'E3', 'F3', 'G3', 'H3', 'I3', 'J3', 'K3', 'L3'];
            for (const itm of colListType) {
                if (worksheet[itm]) {
                    worksheet[itm].s = {
                        font: {sz: 12, bold:true},
                        alignment: {horizontal:'center', wrapText:true, vertical:'top'},
                    };
                }
            }

            for (let i = 0; i < this.index.length; i++) {
                if (this.data_residues[i] == undefined) {
                    this.data_residues[i] = 0;
                }
                if (this.data_garbage_bags[i] == undefined) {
                    this.data_garbage_bags[i] = 0;
                }

                const rowM = [ 
                    [this.index[i]]
                ];
                XLSX.utils.sheet_add_aoa(worksheet, rowM, { origin: "A"+(i+4) });
                
                const row3 = [ 
                    [this.data_residues[i]]
                ];
                XLSX.utils.sheet_add_aoa(worksheet, row3, { origin: "B"+(i+4) });

                const row4 = [ 
                    [this.data_garbage_bags[i]]
                ];
                XLSX.utils.sheet_add_aoa(worksheet, row4, { origin: "C"+(i+4) });
                
                const colListType = ['A'+(i+4), 'B'+(i+4), 'C'+(i+4)];
                for (const itm of colListType) {
                    if (worksheet[itm]) {
                        worksheet[itm].s = {
                            alignment: {horizontal:'center'},
                        };
                    }
                }
            }
            
            const row12 = [ 
                ['TOTAL']
            ];
            XLSX.utils.sheet_add_aoa(worksheet, row12, { origin: "A"+(this.index.length+4) });

            if (this.total[0] != undefined) {
                const row13 = [ 
                    [this.total[0].total_weight.toFixed(2)]
                ];
                XLSX.utils.sheet_add_aoa(worksheet, row13, { origin: "B"+(this.index.length+4) });

            }
            
            if (this.total[0] != undefined) {
                const row14 = [ 
                    [this.total[0].garbage_bags]
                ];
                XLSX.utils.sheet_add_aoa(worksheet, row14, { origin: "C"+(this.index.length+4) });
            }
            

            const colListT = ['A'+(this.index.length+4), 'B'+(this.index.length+4), 'C'+(this.index.length+4)];
            for (const itm of colListT) {
                if (worksheet[itm]) {
                    worksheet[itm].s = {
                        alignment: {horizontal:'center'},
                    };
                }
            }

            XLSX.utils.book_append_sheet(workbook, worksheet, `Datos Excel`);
            XLSX.writeFile(workbook, `Reporte RH anual continuación ${this.date}.xlsx`);
        }
    }
}
</script>
