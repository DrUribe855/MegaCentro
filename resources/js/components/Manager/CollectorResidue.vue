<template>
    <div class="bg-white" style="height: 100vh; overflow-y: scroll">
        <v-app>
            <v-main>
                <template>
                    <v-card align="end">
                        <v-card-title>
                            {{ tittle }}
                            <v-spacer></v-spacer>
                            <v-text-field   
                                v-if="!showTable" 
                                v-model="date"
                                label="Fecha de la recoleccion"
                                type="date"
                                suffix=""
                                single-line
                                hide-details
                                @input="changeDate"
                            ></v-text-field>
                            <v-text-field  
                                class="mx-5" 
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Buscar"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-card-title>
                        <v-btn
                            class="ma-2"
                            color="red darken-2"
                            dark
                            @click="changeView">
                            <v-icon
                                dark
                                left>
                                mdi-arrow-left
                            </v-icon>
                            {{ changeTable }}
                        </v-btn>
                        <v-btn
                            v-if="!showTable"
                            class="mr-6 my-3"
                            depressed
                            color="primary"
                            @click="gather"> 
                            Recolectar
                            <v-icon
                                right
                                dark>
                                mdi-delete
                            </v-icon>
                        </v-btn>
                        <!-- Tabla de almacenado -->
                        <v-data-table
                            v-if="!showTable"
                            :search="search"
                            v-model="selected"
                            :headers="headers"
                            :items="desserts"
                            item-key="positionId"
                            show-select
                            class="elevation-1">
                            <template v-slot:item.storeTime="{ item }">
                                <v-chip
                                    :color="getColor(item.storeTime)"
                                    dark>
                                    {{ item.storeTime }}
                                </v-chip>
                            </template>
                        </v-data-table>
                        <!-- Tabla de recolectado -->
                        <v-data-table
                            v-if="showTable"
                            :search="search"
                            :headers="headersCollected"
                            :items="dessertsCollected"
                            class="elevation-1">
                            <template v-slot:item.storeTime="{ item }">
                                <v-chip
                                    :color="getColor(item.storeTime)"
                                    dark>
                                    {{ item.storeTime }}
                                </v-chip>
                            </template>
                        </v-data-table>
                    </v-card>
                </template>
            </v-main>
        </v-app>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        data () {
            return {
                showTable: false,
                positionId: 0,
                positionTime: 0,
                selected: [],
                search: '',
                date: '',
                changeTable: 'Residuos retirados',
                tittle: 'Residuos almacenados',
                headers: [
                    { text: 'N consultorio', value: 'clinic.clinic_number' },
                    { text: 'Recolector', value: 'user.document' },
                    { text: 'Días almacenado', value: 'storeTime' },
                    { text: 'Fecha recoleccion', value: 'date' },
                    { text: 'Estado almacenamiento', value: 'stored_stated' },
                ],
                desserts: [],
                headersCollected: [
                    { text: 'N consultorio', value: 'clinic.clinic_number' },
                    { text: 'Recolector', value: 'user.document' },
                    { text: 'Días almacenado', value: 'storeTime' },
                    { text: 'Fecha recoleccion', value: 'date' },
                    { text: 'Fecha retiro residuo', value: 'collection_date' },
                    { text: 'Estado almacenamiento', value: 'stored_stated' },
                ],
                dessertsCollected: [],
            }
        },
        
        
        created(){
            this.initialize(true);
            this.date = new Date().toISOString().split('T')[0];
        },
        
        methods: {
            initialize(typeTable) {
                this.positionTime = 0;
                axios.get(`/residue/showCollectorResidue/${typeTable}`).then(res => {
                    console.log('Respuesta del servidor');
                    console.log(res.data);
                    if (typeTable) {
                        this.desserts = res.data.records.map(item => ({ ...item, positionId: this.generateUniqueId(), storeTime: this.setToday(res.data.records[this.positionTime]) }));
                        if (res.data.records.length == 0) {
                            this.alertFalse("No existen residuos almacenados");
                        }
                    }else{
                        this.dessertsCollected = res.data.records.map(item => ({ ...item, positionId: this.generateUniqueId(), storeTime: this.setToday(res.data.records[this.positionTime]) }));
                        if (res.data.records.length == 0) {
                            this.alertFalse("No existen residuos retirados");
                        }
                    }
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            },

            gather(){
                let validation = 0;
                if (this.selected.length != 0) {
                    for (let i = 0; i < this.selected.length; i++) {
                        if (this.selected[i].date <= this.date) {
                            axios.post(`/residue/registerCollector/${this.selected[i].id}/${this.date}`).then(res => {
                                console.log('Respuesta del servidor ', i);
                                console.log(res.data);
                            }).catch(error => {
                                console.log("Error en servidor ", i);
                                console.log(error);
                                console.log(error.response);
                                validation = 1;
                            });
                        }else{
                            this.alertFalse("La fecha del retiro del residuo debe ser igual o mayor a la fecha de recoleccion");
                            validation = 2;
                        }
                        console.log(this.selected[i].date ," - ", this.date);
                    }
                    if (validation == 0) {
                        this.alertTrue("Los residuos seleccionados se retiraron con exito");
                        this.search = '';
                        this.tittle = 'Residuos retirados';
                        this.changeTable = 'Residuos almacenados';
                        this.initialize(false);
                        this.showTable = true;
                    }else if(validation == 1){
                        this.alertFalse("parece que algo salio mal");
                    }
                }else{
                    this.alertFalse("Debe seleccionar un residuo para retirarlo del almacenamiento");
                }
            },

            generateUniqueId: function() {
                return Math.random().toString(36).substr(2, 20);
            },

            setToday (position) {
                this.positionTime++;
                if (position.collection_date != null) {
                    const firstDate = new Date(position.collection_date);
                    const secondDate = new Date(position.date);
                    return Math.floor((firstDate - secondDate) / (1000 * 60 * 60 * 24));
                }else{
                    const firstDate = moment().toDate(); 
                    const secondDate = moment(position.date, 'YYYY-MM-DD').toDate();
                    return Math.floor((firstDate - secondDate) / (1000 * 60 * 60 * 24));
                }
            },

            changeDate(){
                for (let i = 0; i < this.desserts.length; i++) {
                    const firstDate = new Date(this.date);
                    const secondDate = new Date(this.desserts[i].date);
                    this.desserts[i].storeTime = Math.floor((firstDate - secondDate) / (1000 * 60 * 60 * 24));
                    if (this.desserts[i].storeTime < 0) {
                        this.desserts[i].storeTime = 0;
                    }
                }
            },

            getColor (storeTime) {
                if (storeTime > 5) return 'red'
                else if (storeTime > 2) return 'orange'
                else return 'blue'
            },

            changeView(){
                this.search = '';
                if (this.changeTable == 'Residuos retirados') {
                    this.tittle = 'Residuos retirados';
                    this.changeTable = 'Residuos almacenados'
                    this.showTable = true;
                    this.initialize(false);
                }else{
                    this.tittle = 'Residuos almacenados';
                    this.changeTable = 'Residuos retirados'
                    this.showTable = false;
                    this.initialize(true);
                }
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
        },

    }
</script>