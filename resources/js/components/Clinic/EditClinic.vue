<template>
    <v-card>
        <v-card-title>
            Nutrition
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
            :headers="headers"
            :items="desserts"
            :search="search"
        >
            <template>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    export default {
        props: ['clinic'],
        data () {
            return {
                search: '',
                headers: [
                        { text: 'N Consultorio', value: 'clinic_number' },
                        { text: 'N Torre', value: 'tower_id' },
                        { text: 'Estado', value: 'status' },
                ],
                desserts: [
                    
                ],

                selectedClinic: '',
            } 
        },

        created(){
            this.desserts = this.clinic.clinic
            console.log(this.desserts);
        },
    }
</script>