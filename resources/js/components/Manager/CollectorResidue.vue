<template>
    <v-app>
        <v-main>
            <template>
                <v-card-title>
                    Recolectar los residuos almacenados en los consultorios
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="date"
                        label="Fecha de la recoleccion"
                        value=""
                        type="date"
                        suffix=""
                        @input="changeDate"
                    ></v-text-field>
                    <v-spacer></v-spacer>
                    <v-text-field   
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Buscar"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-card-title>
                <v-data-table
                    :search="search"
                    v-model="selected"
                    :headers="headers"
                    :items="desserts"
                    item-key="positionId"
                    show-select
                    class="elevation-1">
                </v-data-table>
            </template>
        </v-main>
    </v-app>
</template>

<script>
    import moment from 'moment';
    import { format } from 'date-fns';
    export default {
        data () {
        return {
            positionId: 0,
            positionTime: 0,
            singleSelect: false,
            selected: [],
            search: '',
            date: '',
            headers: [
                { text: 'N consultorio', value: 'clinic.clinic_number' },
                { text: 'Recolector', value: 'user.document' },
                { text: 'Días almacenado', value: 'storeTime' },
                { text: 'Fecha recoleccion', value: 'date' },
                { text: 'Estado almacenamiento', value: 'stored_stated' },
            ],
            desserts: [
                
            ],
        }
        },

        created(){
            this.initialize();
        },

        methods: {
            initialize() {
                axios.get('/residue/showCollectorResidue').then(res => {
                    console.log('Respuesta del servidor');
                    console.log(res.data.records);
                    this.desserts = res.data.records.map(item => ({ ...item, positionId: this.generateUniqueId(), storeTime: this.setToday(res.data.records[this.positionTime].dateTemp) }));
                }).catch(error => {
                    console.log("Error en servidor");
                    console.log(error);
                    console.log(error.response);
                });
            },

            generateUniqueId: function() {
                return Math.random().toString(36).substr(2, 20);
            },

            setToday (position) {
                this.positionTime++;
                let focus = moment().format('YYYYMMDD');
                console.log(position, " -- ", focus);
                if (position > focus) {
                    if (position.collection_date == null) {
                        let operacion = parseInt(focus) - parseInt(position);
                        console.log(position, " -- ", focus);
                        return operacion;
                    }else{
                        let operacion = parseInt(focus) - parseInt(position);
                        console.log(focus);
                        return operacion;
                    }
                }else{
                    return 0
                }
            },

            changeDate(){
                const focus = parseInt(format(new Date(this.date), 'yyyydd')) + 1;
                console.log(focus);
                for (let i = 0; i < this.desserts.length; i++) {
                    if (this.desserts[i].collection_date == null) {
                        let operacion = Date.parse(this.desserts[i].dateTemp) - Date.parse(focus);
                        this.desserts[i].storeTime = operacion;
                    }else{
                        let operacion = parseInt(this.desserts[i].dateTemp) - parseInt(focus);
                        console.log(focus);
                        this.desserts[i].storeTime = operacion;
                    }
                }
                console.log(this.desserts);
            },
        },

    }
</script>