<template>
    <div style="height: 100vh; overflow-y: scroll">
        <v-app>
            <v-main>
                <div class="container-fluid row">
                    <div class="col-sm-12 my-6">
                        <v-container class="col-6 v-alert-container">
                            <v-alert 
                                v-if="!showAlert && !alert"
                                shaped
                                type="info"
                                transition="scale-transition">
                                Ahora puede buscar el consultorio que desea consultar
                            </v-alert>
                            <v-alert 
                                v-if="!showAlert && alert"
                                shaped
                                type="info"
                                transition="scale-transition">
                                Ahora esta en el formualario general
                            </v-alert>
                        </v-container>
                        <v-toolbar flat class="mb-6">
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
                                <!-- <v-btn :loading="loading3" :disabled="loading3" color="green" class="ma-2 white--text"
                                    @click="loader = 'loading3'">
                                    EXCEL
                                    <v-icon right dark>
                                        mdi-cloud-upload
                                    </v-icon>
                                </v-btn> -->
                                <v-btn :loading="loadingPdf" :disabled="loadingPdf" color="red" class="ma-2 white--text"
                                    @click="pdf">
                                    PDF
                                    <v-icon right dark>
                                        mdi-file-download
                                    </v-icon>
                                </v-btn>
                            </div>

                            <v-menu bottom right>
                            </v-menu>
                        </v-toolbar>
                        <div class="col-6 m-6 row">
                            <div class="col-12 col-sm-6" style="width: 200px !important;">
                                <v-autocomplete v-if="!alert" 
                                    v-model="clinic" 
                                    :items="items" 
                                    label="Consultorio"
                                    @input="clinicSelected()"
                                    item-text="clinic_number"
                                    item-value='id'>
                                </v-autocomplete>
                                <v-btn
                                    v-if="!alert"
                                    class="ma-2"
                                    color="primary"
                                    dark
                                    @click="mounted(), alert = true, changeData(), showAlert = false">
                                    <v-icon
                                        dark
                                        left>
                                        mdi-arrow-left
                                    </v-icon>
                                    volver
                                </v-btn>
                                <v-btn
                                    v-if="alert"
                                    color="primary"
                                    @click="mounted(), alert = !alert, showAlert = !showAlert, changeData()">
                                    <v-icon
                                        left>
                                        fas fa-search
                                    </v-icon>
                                    Consultorios
                                </v-btn>
                            </div>
                            <div class="col-12 col-sm-6">
                                <v-btn
                                    color="red"
                                    class="white--text"
                                    @click="saveChange">
                                    Guardar cambios
                                    <v-icon
                                        right>
                                        mdi-content-save
                                    </v-icon>
                                </v-btn>
                            </div>
                        </div>
                    </div>
                    <div id="element-to-pdf" class="col-12 row">
                        <div class="col-sm-12 my-6">
                            <div class="row flex justify-content-center mb-2">
                                <div class="text-center">
                                    <h2>FORMULARIO RH CONTINUACIÓN {{ !alert && clinic != null ? 'CONSULTORIO ' + clinic_number : '' }}</h2>
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
                                            <th class="text-center p-0" style="font-size: 15px;">Pretratamiento usado de desactivacion</th>
                                            <th class="text-center p-0" style="font-size: 15px;" v-if="!alert && clinic != ''">Almacenamientos (días)</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Tipo de tratamiento</th>
                                            <th class="text-center p-1" style="font-size: 15px;">Hora de recolección</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Dotacion personal General </th>
                                            <th class="text-center p-0" style="font-size: 15px;">Dotacion PSEA adecuada?</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Color bolsa utilizada</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Proceso productivo</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Residuos similar (KG / día)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="i in index">
                                            <td class="text-center">{{ i <= 9 ? '0'+i : i }}</td>
                                            <td class="text-center">{{ revalidateData(i,0) }}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center">{{ revalidateData(i,1) }}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center" v-if="!alert && clinic != ''">{{ stored[i] == i ? stored_days[i] : '0' }}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center p-0">
                                                <input 
                                                    v-model="hoursText[i]"
                                                    type="text" 
                                                    @input="uperCaseText(hoursText[i],i)" 
                                                    class="border border-black form-control" 
                                                    style="width: 100%; height: 2.8em;"
                                                >
                                            </td>
                                            <td class="text-center"></td>
                                            <td class="text-center p-0"> 
                                                <select v-model="selectYesOrNot[i]" class="border border-black form-control" style="width: 100%; height: 2.8em;">
                                                    <option selected></option>
                                                    <option>Si</option>
                                                    <option>No</option>
                                                </select>
                                            </td>
                                            <td class="text-center">ROJO</td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">TOTAL</td>
                                            <td class="text-center">{{ revalidateData(0,2) }}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center">{{ revalidateData(0,3) }}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center" v-if="!alert && clinic != ''">{{ totalDay }}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
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
            date: '',
            dateAxios: '',
            clinic: '',
            clinic_number: '',
            type: 'month',
            loader: null,
            loaderPdf: null,
            loading3: false,
            loadingPdf: false,
            validateTable: false,
            alert: true,
            showAlert: true,
            selectYesOrNot: [],
            hoursText: [],
            day: [],
            list_residues: [],
            data_residues: [],
            list_residues_clinic: [],
            list_residues_temp: [],
            data_garbage_bags: [],
            items: [],
            collection_logs: [],
            stored_days: [],
            stored: [],
            index: 0,
            position: 0,
            total: 0,
            total_clinic: 0,
            total_temp: 0,
            totalDay: 0,
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
        pdf() {
            this.loaderPdf = 'loadingPdf'
            var element = document.getElementById('element-to-pdf');
            if (this.alert) {
                var opt = {
                    margin: 1,
                    filename: `Reporte RH constinuacion ${this.date}.pdf`,
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
                    margin: 1,
                    filename: `Reporte RH continuacion consultorio ${this.clinic_number} ${this.date}.pdf`,
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

        clinicNumber() {
            axios.get('/residue/clinicNumber').then(res => {
                console.log("Respuesta del servidor");
                console.log("Datos de consulta ", res.data);
                this.items = res.data.clinic;
            }).catch(error => {
                console.log("Error en servidor");
                console.log(error);
                console.log(error.response);
            });
        },

        initialize(date) {
            console.log("date ", date);
            if (date != '') {
                axios.get(`/residue/showContinuation/${date}`).then(res => {
                    console.log("Respuesta del servidor");
                    console.log("Datos de consulta ", res.data);
                    this.list_residues = res.data.residues;
                    this.index = res.data.date;
                    this.total = res.data.total;
                    this.list_residues_temp = this.list_residues;
                    this.total_temp = this.total;
                    this.changeData();
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            }
        },

        clinicInitialize(id) {
            if (this.dateAxios != '' && id != '') {
                axios.get(`/residue/clinicContinuation/${this.dateAxios}/${id}`).then(res => {
                    console.log("Respuesta del servidor");
                    console.log("Datos de consulta ", res.data);
                    this.list_residues_clinic = res.data.clinicResidue;
                    this.total_clinic = res.data.totalClinic;
                    this.collection_logs = res.data.collectionLog;
                    this.changeData();
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            }
        },

        changeData() {
            if (this.alert) {
                this.list_residues = this.list_residues_temp;
                this.total = this.total_temp;
            } else {
                this.list_residues = this.list_residues_clinic;
                this.total = this.total_clinic;
            }
            this.getResidueValue();
        },

        revalidateData(index, type) {
            if (this.data_residues[index] != undefined && type == 0) {
                return this.data_residues[index];
            }else if (this.data_garbage_bags[index] != undefined && type == 1){
                return this.data_garbage_bags[index];
            }else if (this.total[index] != undefined) {
                if (type == 2) {
                    return this.formater(this.total[index].total_weight)
                }else{
                    return this.formater(this.total[index].garbage_bags)
                }
            }
            return '0';
        },

        getResidueValue() {
            this.data_residues = [];
            this.data_garbage_bags = [];
            this.stored = [];
            this.stored_day = [];
            this.totalDay = 0;
            let daysTemp = '';
            for (let i = 0; i < this.index; i++) {
                if (!this.data_residues[i]) {
                    this.$set(this.data_residues, i);
                }
                this.$set(this.stored, i);
                this.stored[i] = 0;
                this.$set(this.stored_days, i);
            }
            if (this.list_residues.length != 0) {
                for (let i = 1; i <= this.index; i++) {
                    if (!this.alert && this.clinic != '') {
                        if (this.collection_logs[i-1] != undefined) {
                            const firstDate = new Date(this.collection_logs[i-1].collection_date);
                            const secondDate = new Date(this.collection_logs[i-1].date);
                            daysTemp = this.collection_logs[i-1].day;
                            for (let j = 0; j < this.index; j++) {
                                if (j == daysTemp) {
                                    this.stored[j] = daysTemp;
                                    this.stored_days[j] = Math.floor((firstDate - secondDate) / (1000 * 60 * 60 * 24));
                                    this.totalDay += this.stored_days[j];
                                    break;
                                }
                            }
                        }
                    }
                    for (let l = 0; l < this.list_residues.length; l++) {
                        if (this.list_residues[l].day == i) {
                            this.data_garbage_bags[i] = this.formater(this.list_residues[l].garbage_bags);
                            this.data_residues[i] = this.formater(this.list_residues[l].total_weight);
                            this.hoursText[i] = this.list_residues[l].hour;
                            this.selectYesOrNot[i] = this.list_residues[l].yesOrNot;
                        }
                    }
                }
            }
        },

        formater(total){
            total = accounting.formatMoney(total, {
                symbol: '',
                precision: '',
                thousand: ',',
                decimal: '.'
            });
            return total;
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
            this.clinicInitialize(this.clinic);
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
            this.clinicInitialize(this.clinic);
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

        uperCaseText(text,position){
            return this.hoursText[position] = text.toUpperCase();
        },

        saveChange(){
            let data = [];
            let size = 0;
            let typeAlert = 0;
            if (this.hoursText.length > this.selectYesOrNot.length) {
                size = this.hoursText.length;
            }else{
                size = this.selectYesOrNot.length;
            }
            let currentDate = new Date();
            let year = currentDate.getFullYear();
            let month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
            let day = currentDate.getDate().toString().padStart(2, '0');

            let formattedDate = `${year}-${month}-${day}`;
            for (let i = 0; i < size; i++) {
                if (this.hoursText[i] != undefined && this.selectYesOrNot[i] != undefined) {
                    if (this.validateHour(this.hoursText[i])) {
                        data = {
                            'yesOrNot': this.selectYesOrNot[i],
                            'hour': this.hoursText[i],
                            'date': formattedDate,
                        };
                        axios.post(`/residue/registerDateCollector/${i}`, data).then(res => {
                        }).catch(error => {
                            console.log(error.response);
                            typeAlert = 1;
                        })
                    }else{
                        typeAlert = 2;
                    }
                }
            }
            if (typeAlert == 1) {
                this.alertFalse("Parece que algo salio mal");
            }else if (typeAlert == 2){
                this.alertFalse("Parece que algunos campos de la hora son incorrectos");
            }
        },

        validateHour(hour){
            let restriction = /^(0?[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/i;
            return restriction.test(hour);
        },

        alertTrue(text){
            swal({
                title: "Cambio Exitoso!",
                text: text,
                icon: "success",
            });
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