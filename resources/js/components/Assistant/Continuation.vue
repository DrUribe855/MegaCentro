<template>
    <div style="height: 100vh; overflow-y: scroll">
        <v-app>
            <v-main>
                <div class="container-fluid row">
                    <div class="col-sm-12 my-6">
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
                                <v-btn color="green" class="ma-2 white--text" @click="downloadExcel">
                                    EXCEL
                                    <v-icon right dark>
                                        mdi-download
                                    </v-icon>
                                </v-btn>
                                <v-btn v-if="isAdmin == true" color="red" class="ma-2 white--text" @click="pdf">
                                    PDF
                                    <v-icon right dark>
                                        mdi-download
                                    </v-icon>
                                </v-btn>
                            </div>

                            <v-menu bottom right>
                            </v-menu>
                        </v-toolbar>
                        <div class="col-6 m-6 row">
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
                        <div class="col-sm-12 container" tabindex="0" @keyup.enter="evntoEnter">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center p-0" style="font-size: 15px;">Día</th>
                                            <th class="text-center p-0" style="font-size: 15px;">KG/Residuo</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Camas ocupadas/ día</th>
                                            <th class="text-center p-0" style="font-size: 15px;">No. Consultas/ día</th>
                                            <th class="text-center p-0" style="font-size: 15px;">No. Bolsas Entregadas</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Pretratamiento usado de desactivación</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Almacena miento (días)</th>
                                            <th class="text-center p-4" style="font-size: 15px;">Tipo de tratamiento</th>
                                            <th class="text-center p-2" style="font-size: 15px;">Hora de recolección</th>
                                            <th class="text-center p-0" style="font-size: 15px;">Dotación personal General </th>
                                            <th class="text-center p-0" style="font-size: 15px;">Dotación PSEA adecuada?</th>
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
                                            <td class="text-center p-0">
                                                <input 
                                                    :disabled="revalidateData(i,3)"
                                                    v-model="garbageBags[i]"
                                                    type="number" 
                                                    class="border border-black form-control" 
                                                    style="width: 100%; height: 2.8em;"
                                                >
                                            </td>
                                            <td class="text-center">Amonio</td>
                                            <td class="text-center">{{ revalidateData(i,4) }}</td>
                                            <td class="text-center p-0">
                                                <select :disabled="revalidateData(i,3)" v-model="treatmentType[i]" class="border border-black form-control" style="width: 100%; height: 2.8em;">
                                                    <option selected></option>
                                                    <option>Esterilización</option>
                                                    <option>Incineración</option>
                                                </select>
                                            </td>
                                            <td class="text-center p-0">
                                                <v-combobox 
                                                    :disabled="revalidateData(i,3)"
                                                    class="border border-black form-control" style="width: 100%; height: 2.8em;"
                                                    v-model="hoursText[i]"
                                                    :items="itemsSelect"
                                                    solo>
                                                </v-combobox>
                                            </td>
                                            <td class="text-center p-0"> 
                                                <select :disabled="revalidateData(i,3)" v-model="staffing[i]" class="border border-black form-control" style="width: 100%; height: 2.8em;">
                                                    <option selected></option>
                                                    <option>SI</option>
                                                    <option>NO</option>
                                                </select>
                                            </td>
                                            <td class="text-center p-0"> 
                                                <select :disabled="revalidateData(i,3)" v-model="selectYesOrNot[i]" class="border border-black form-control" style="width: 100%; height: 2.8em;">
                                                    <option selected></option>
                                                    <option>SI</option>
                                                    <option>NO</option>
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
                                            <td class="text-center">{{ totalGarbageBags }}</td>
                                            <td class="text-center"></td>
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
import * as XLSX from 'xlsx-js-style';
export default {
    data() {
        return {
            date: '',
            dateAxios: '',
            clinic: '',
            clinic_number: '',
            dateComplet: '',
            validateMonth: '',
            type: 'month',
            loader: null,
            loaderPdf: null,
            loading3: false,
            loadingPdf: false,
            alert: true,
            dateStorage: [],
            selectYesOrNot: [],
            staffing:[],
            treatmentType: [],
            hoursText: [],
            garbageBags: [],
            day: [],
            list_residues: [],
            data_residues: [],
            list_residues_clinic: [],
            list_residues_temp: [],
            list_date: [],
            items: [],
            collection_logs: [],
            stored_days: [],
            stored: [],
            itemsSelect: [
                '01:00',
                '02:00',
                '03:00',
                '04:00',
                '05:00',
                '06:00',
                '07:00',
                '08:00',
                '09:00',
                '10:00',
                '11:00',
                '12:00',
            ],
            index: 0,
            position: 0,
            total: 0,
            total_clinic: 0,
            total_temp: 0,
            totalGarbageBags: 0,
            focus: new Date(),
            isAdmin: false,
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
        this.getuserRole();
    },

    methods: {
        evntoEnter() {
            this.saveChange();
        },

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
                        height: 397
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
                this.items = res.data.clinic;
            }).catch(error => {
            });
        },

        initialize(date) {
            if (date != '') {
                axios.get(`/residue/showContinuation/${date}`).then(res => {
                    this.list_residues = res.data.residues;
                    this.index = res.data.date;
                    this.total = res.data.total;
                    this.list_residues_temp = this.list_residues;
                    this.total_temp = this.total;
                    this.totalGarbageBags = res.data.totalGarbageBags;
                    this.changeData();
                }).catch(error => {
                });
            }
        },

        clinicInitialize(id) {
            if (this.dateAxios != '' && id != '') {
                axios.get(`/residue/clinicContinuation/${this.dateAxios}/${id}`).then(res => {
                    this.list_residues_clinic = res.data.clinicResidue;
                    this.total_clinic = res.data.totalClinic;
                    this.collection_logs = res.data.collectionLog;
                    this.changeData();
                }).catch(error => {
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
                var length = this.data_residues[index].toString();
                if (length.length >= 5) {
                    return this.data_residues[index].toFixed(2);
                }else{
                    return this.data_residues[index];
                }
            }else if (this.total[index] != undefined && type == 2) {
                return this.total[index].total_weight.toFixed(2);
            }else if(type == 3){
                if (this.list_date[index] != undefined && this.list_date[index] == this.dateComplet-1 || this.list_date[index] == this.dateComplet) {
                    return false;
                }else{
                    return true;
                }
            }else if(type == 4){
                if (this.dateStorage[index] != undefined) {
                    return this.dateStorage[index];
                }else{
                    return '';
                }
            }
            return '0';
        },

        getResidueValue() {
            this.data_residues = [];
            this.stored = [];
            this.stored_day = [];
            this.garbageBags = [];
            this.hoursText = [];
            this.selectYesOrNot = [];
            this.staffing = [];
            this.list_date = [];
            this.treatmentType = [];
            this.dateStorage = [];
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
                    for (let l = 0; l < this.list_residues.length; l++) {
                        if (this.list_residues[l].day == i) {
                            this.data_residues[i] = this.list_residues[l].total_weight;
                            this.hoursText[i] = this.list_residues[l].hour;
                            this.selectYesOrNot[i] = this.list_residues[l].yesOrNot;
                            this.garbageBags[i] = this.list_residues[l].garbage_bags;
                            this.staffing[i] = this.list_residues[l].staffing;
                            this.treatmentType[i] = this.list_residues[l].treatmentType;
                            this.list_date[i] = `${this.list_residues[l].year}${this.list_residues[l].month}${this.list_residues[l].day}`;
                            
                            if (this.list_residues[l].collection_date != null) {
                                let dateTemp = `${this.list_residues[l].year}-${this.list_residues[l].month < 10 ? '0' + this.list_residues[l].month : this.list_residues[l].month}-${this.list_residues[l].day}`;
                                let firstDate = new Date(this.list_residues[l].collection_date);
                                let secondDate = new Date(dateTemp);
                                this.dateStorage[i] = Math.floor((firstDate - secondDate) / (1000 * 60 * 60 * 24));
                                if (this.dateStorage[i]<0) {
                                    this.dateStorage[i] = 0;
                                }
                            }
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
            const day = this.focus.getDate();
            this.position = monthNumber <= 9 ? this.position = '0' + monthNumber : this.position = monthNumber;
            this.date = month + ' ' + year;
            this.dateAxios = year + '-' + monthNumber;
            this.validateMonth = monthNumber;
            this.dateComplet = `${year}${monthNumber}${day}`;
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
            this.clinicInitialize(this.clinic);
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

        getuserRole(){
            axios.get('/collector/getRole').then(res => {
                if(res.data.role == 'Administrador' || res.data.role == 'Auxiliar Contable'){
                    this.isAdmin = true;
                }else{
                    this.isAdmin = false;
                }
            }).catch(error => {
            })
        },

        saveChange(){
            let size = 0;
            let typeAlert = 0;
            if (this.hoursText.length > this.selectYesOrNot.length && this.hoursText.length > this.garbageBags.length && this.hoursText.length > this.treatmentType.length) {
                size = this.hoursText.length;
            }else if(this.selectYesOrNot.length > this.hoursText.length && this.selectYesOrNot.length > this.garbageBags.length && this.selectYesOrNot.length > this.treatmentType.length){
                size = this.selectYesOrNot.length;
            }else if (this.garbageBags.length > this.hoursText.length && this.garbageBags.length > this.selectYesOrNot.length && this.garbageBags.length > this.treatmentType.length){
                size = this.garbageBags.length;
            }else{
                size = this.treatmentType.length;
            }
            let currentDate = new Date();
            let year = currentDate.getFullYear();
            let month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
            let day = currentDate.getDate().toString().padStart(2, '0');
            let formattedDate = `${year}-${month}-${day}`;
            let requests = [];
            let validateHour = true;
            for (let i = 0; i < size; i++) {
                if (this.data_residues[i] != undefined) {
                    let hour = null;
                    let selectYesOrNot = null;
                    let garbage_bags = null;
                    let staffing = null;
                    let treatmentType = null;
                    if (this.hoursText[i] != null && this.hoursText[i] != '') {
                        if (this.validateHour(this.hoursText[i])) {
                            if (this.hoursText[i] != undefined) {
                                hour = this.hoursText[i];
                            }
                        }else{
                            validateHour = false;
                            this.alertFalse(`La hora del día: ${i} es incorrecta el formato debe ser 00:00`);
                        }
                    }
                    if (this.selectYesOrNot[i] != undefined) {
                        selectYesOrNot = this.selectYesOrNot[i];
                    }
                    if (this.garbageBags[i] != undefined) {
                        if (this.garbageBags[i] >= 0) {
                            garbage_bags = this.garbageBags[i];
                        }else{
                            validateHour = false;
                            this.alertFalse(`La cantidad de bolsas del día: ${i} deben ser números positivos`);
                        }
                    }
                    if (this.staffing[i] != undefined) {
                        staffing = this.staffing[i];
                    }
                    if (this.treatmentType[i] != undefined) {
                        treatmentType = this.treatmentType[i];
                    }
                    let data = {
                        'yesOrNot': selectYesOrNot,
                        'hour': hour,
                        'garbage_bags': garbage_bags,
                        'staffing': staffing,
                        'date': formattedDate,
                        'treatmentType': treatmentType,
                    };  
                    if (validateHour) {
                        let request = axios.post(`/residue/registerDateCollector/${i}`, data).then(res => {
                        }).catch(error => {
                        })
                        requests.push(request);
                    }
                }
            }
            if (validateHour) {
                axios.all(requests).then(
                    axios.spread((...responses) => {
                        typeAlert = 3;
                    })
                ).catch(error => {
                    console.error(error);
                    typeAlert = 1;
                }).finally(() => {
                    if (typeAlert == 1) {
                        this.alertFalse("Parece que algo salió mal");
                    }else if (typeAlert == 3) {
                        this.setToday()
                        this.alertTrue("Cambios exitosos. Recuerde que los cambios hechos en los días que no tienen recolección de residuos no serán registrados");
                    }
                });
            }
        },

        validateHour(hour){
            let restriction = /^(0?[1-9]|1[0-2]):[0-5][0-9]$/i;
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

        downloadExcel(){
            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.aoa_to_sheet([[]]);

            const row0 = [ 
                ['FORMULARIO RH CONTINUACIÓN', '', '', '', '', '', '', '', '', '', '', '', ''] 
            ];

            XLSX.utils.sheet_add_aoa(worksheet, row0, { origin: "A1" });

            const row1 = [ 
                ['REGISTRO DIARIO DE GENERACIÓN DE RESIDUOS HOSPITALARIOS Y SIMILARES', '', '', '', '', '', '', '', '', '', '', '', '', '']
            ];
            XLSX.utils.sheet_add_aoa(worksheet, row1, { origin: "A2" });

            const row2 = [ 
                ['DÍA', 'KG/Residuo', 'Camas ocupadas/día', 'No. Consultas/día', 'No. Bolsas Entregadas', 'Pretratamiento usado de desactivación', 'Almacena miento (días)', 'Tipo de tratamiento', 'Hora de recolección', 'Dotación personal General', 'Dotación PSEA adecuada?', 'Color bolsa utilizada', 'Proceso productivo', 'Residuos similar (KG / día)']
            ];
            XLSX.utils.sheet_add_aoa(worksheet, row2, { origin: "A3" });

            const merges = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: 13 } },
                { s: { r: 1, c: 0 }, e: { r: 1, c: 13 } },
            ];
            worksheet['!merges'] = merges;
            worksheet['!rows'] = [];
            worksheet['!rows'][2] = { hpx: 60 };

            const colListTitle = ['A1', 'A2'];
            for (const itm of colListTitle) {
                if (worksheet[itm]) {
                    worksheet[itm].s = {
                        font: {sz: 20, bold:true},
                        alignment: {horizontal:'center'},
                    };
                }
            }

            const colListType = ['A3', 'B3', 'C3', 'D3', 'E3', 'F3', 'G3', 'H3', 'I3', 'J3', 'K3', 'L3', 'M3', 'N3'];
            for (const itm of colListType) {
                if (worksheet[itm]) {
                    worksheet[itm].s = {
                        font: {sz: 12, bold:true},
                        alignment: {horizontal:'center', wrapText:true},
                    };
                }
            }

            for (let i = 1; i <= this.index; i++) {
                if (this.data_residues[i] == undefined) {
                    this.data_residues[i] = 0;
                }
                if (this.garbageBags[i] == undefined) {
                    this.garbageBags[i] = 0;
                }

                const rowD = [ 
                    [i]
                ];
                XLSX.utils.sheet_add_aoa(worksheet, rowD, { origin: "A"+(i+3) });
                
                const row3 = [ 
                    [this.data_residues[i]]
                ];
                XLSX.utils.sheet_add_aoa(worksheet, row3, { origin: "B"+(i+3) });

                const row4 = [ 
                    [this.garbageBags[i]]
                ];
                XLSX.utils.sheet_add_aoa(worksheet, row4, { origin: "E"+(i+3) });
                
                const row5 = [ 
                    ['Amonio']
                ];
                XLSX.utils.sheet_add_aoa(worksheet, row5, { origin: "F"+(i+3) });
                
                if (this.dateStorage[i] != undefined) {
                    const row6 = [ 
                        [this.dateStorage[i]]
                    ];
                    XLSX.utils.sheet_add_aoa(worksheet, row6, { origin: "G"+(i+3) });
                }
                
                if (this.treatmentType[i] != undefined) {
                    const row7 = [ 
                        [this.treatmentType[i]]
                    ];
                    XLSX.utils.sheet_add_aoa(worksheet, row7, { origin: "H"+(i+3) });
                }

                if (this.hoursText[i] != undefined) {
                    const row8 = [ 
                        [this.hoursText[i]]
                    ];
                    XLSX.utils.sheet_add_aoa(worksheet, row8, { origin: "I"+(i+3) });
                }
                
                if (this.staffing[i] != undefined) {
                    const row9 = [ 
                        [this.staffing[i]]
                    ];
                    XLSX.utils.sheet_add_aoa(worksheet, row9, { origin: "J"+(i+3) });
                }
                
                if (this.selectYesOrNot[i] != undefined) {
                    const row10 = [ 
                        [this.selectYesOrNot[i]]
                    ];
                    XLSX.utils.sheet_add_aoa(worksheet, row10, { origin: "K"+(i+3) });
                }

                const row11 = [ 
                    ['ROJO']
                ];
                XLSX.utils.sheet_add_aoa(worksheet, row11, { origin: "L"+(i+3) });
                
                const colListType = ['A'+(i+3), 'B'+(i+3), 'C'+(i+3), 'D'+(i+3), 'E'+(i+3), 'F'+(i+3), 'G'+(i+3), 'H'+(i+3), 'I'+(i+3), 'J'+(i+3), 'K'+(i+3), 'L'+(i+3), 'M'+(i+3), 'N'+(i+3),];
                for (const itm of colListType) {
                    if (worksheet[itm]) {
                        worksheet[itm].s = {
                            alignment: {horizontal:'center'},
                        };
                    }
                }
            }
            
            const row12 = [ 
                ['Total']
            ];
            XLSX.utils.sheet_add_aoa(worksheet, row12, { origin: "A"+(this.index+4) });

            if (this.total[0] != undefined) {
                const row13 = [ 
                    [this.total[0].total_weight.toFixed(2)]
                ];
                XLSX.utils.sheet_add_aoa(worksheet, row13, { origin: "B"+(this.index+4) });
            }   
            
            const row14 = [ 
                [this.totalGarbageBags]
            ];
            XLSX.utils.sheet_add_aoa(worksheet, row14, { origin: "E"+(this.index+4) });

            const colListT = ['A'+(this.index+4), 'B'+(this.index+4), 'E'+(this.index+4)];
            for (const itm of colListT) {
                if (worksheet[itm]) {
                    worksheet[itm].s = {
                        alignment: {horizontal:'center'},
                    };
                }
            }

            XLSX.utils.book_append_sheet(workbook, worksheet, `Datos Excel`);
            XLSX.writeFile(workbook, `Reporte RH constinuacion ${this.date}.xlsx`);
        }
    }
}
</script>
