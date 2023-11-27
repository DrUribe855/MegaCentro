<template>
    <div style="height: 100vh; overflow-y: scroll">
        <v-app>
            <v-main>
                <div class="container-fluid row">
                    <div class="col-sm-12 my-6">
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
                                <v-btn :loading="loading3" :disabled="loading3" color="green" class="ma-2 white--text"
                                    @click="loader = 'loading3'">
                                    EXCEL
                                    <v-icon right dark>
                                        mdi-cloud-upload
                                    </v-icon>
                                </v-btn>
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
                                    <h1>FORMULARIO RH CONTINUACIÓN</h1>
                                </div>
                                <img src="../img/Imagen1.png" alt="Logo Megacentro" width="110em" class="ml-6 img-fluid">
                            </div>
                            <div class="text-center my-6">
                                <h3>REGISTRO DIARIO DE GENERACIÓN DE RESIDUOS HOSPITALARIOS Y SIMILARES</h3>
                            </div>
                        </div>
                        <div class="col-sm-12 container">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center p-0" style="font-size: 15px;">Día</th>
                                            <th class="text-center p-0" style="font-size: 15px;">KG/Residuo</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Camas ocupadas/ día</th>
                                            <th class="text-center p-0" style="font-size: 15px;">No. Consultas/ día</th>
                                            <th class="text-center p-0" style="font-size: 15px;">No. Bolsas Entregadas</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Pretratamineto usando de desactivacion</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Almacenaminetos (días)</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Tipo de tratamiento</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Hora de recolección</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Dotacion personal General </th>
                                            <th class="text-center p-0" style="font-size: 15px;">Dotacion PSEA adecuada?</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Color bolsa utilizada</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Proceso productivo</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Residuos similar (KG / día)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(list, i) in list_residues">
                                            <td class="text-center">{{ i+1 }}</td>
                                            <td class="text-center">{{ list.total_weight != '' ? list.total_weight : '0' }}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center">{{ list.garbage_bags != '' ? list.garbage_bags : '0' }}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center">1</td>
                                            <td class="text-center"></td>
                                            <td class="text-center">{{ list.hour != '' ? list.hour : '0' }}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>
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
            validateTable: false,
            list_residues: [],
            index: 31,
            type: 'month',
            focus: new Date(),
            date: '',
            dateAxios: '',
            position: 0,
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
        this.initialize(this.dateAxios);
    },

    methods: {
        pdf() {
            this.loaderPdf = 'loadingPdf'
            var element = document.getElementById('element-to-pdf');
            var opt = {
                margin: 2.1,
                filename: 'Reporte RH mensual.pdf',
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
            console.log("date ", date);
            if (date != '') {
                axios.get(`/residue/showContinuation/${date}`).then(res => {
                    console.log("Respuesta del servidor");
                    console.log("Datos de consulta ", res.data.residues);
                    this.list_residues = res.data.residues;
                    for (let i = 0; i < this.list_residues.length; i++) {
                        this.list_residues[i].total_weight = accounting.formatMoney(this.list_residues[i].total_weight, {
                            symbol: '',
                            precision: '',
                            thousand: ',',
                            decimal: '.'
                        });
                    }
                    this.getResidueValue();
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            }
        },

        setToday() {
            this.focus = new Date();
            const options = { month: 'long' };
            const month = this.focus.toLocaleDateString('es-ES', options);
            const year = this.focus.getFullYear();
            const monthNumber = this.focus.getMonth() + 1;
            this.position = monthNumber <= 9 ? this.position = '0' + monthNumber : this.position = monthNumber;
            this.date = month + ' ' + year;
            this.dateAxios = year + '-' + monthNumber;
            this.initialize(this.dateAxios);
        },

        prev() {
            const newFocus = new Date(this.focus);
            newFocus.setMonth(newFocus.getMonth() - 1);
            const options = { month: 'long' };
            const monthName = newFocus.toLocaleDateString('es-ES', options);
            const year = newFocus.getFullYear();
            this.focus = newFocus;
            this.date = monthName + ' ' + year
            this.position--;
            if (this.position == 0) {
                this.position = 12;
            }
            if (this.position <= 9) {
                this.position = '0' + this.position--;
            }
            this.dateAxios = year + '-' + this.position;
            console.log("FECHA ", this.dateAxios);
            this.initialize(this.dateAxios);
        },

        next() {
            const newFocus = new Date(this.focus);
            newFocus.setMonth(newFocus.getMonth() + 1);
            const options = { month: 'long' };
            const monthName = newFocus.toLocaleDateString('es-ES', options);
            const year = newFocus.getFullYear();
            this.focus = newFocus;
            this.date = monthName + ' ' + year
            this.position++;
            if (this.position > 12) {
                this.position = 1;
            }
            if (this.position <= 9) {
                this.position = '0' + this.position++;
            }
            this.dateAxios = year + '-' + this.position;
            this.initialize(this.dateAxios);
            console.log(this.date);
        },
    }
}
</script>