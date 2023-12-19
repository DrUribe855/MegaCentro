<template>
    <div>
        <v-app>
            <v-main>
                <v-card-title>
                    {{ infoUser.document }}
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Buscar"
                        single-line
                        hide-details    
                    ></v-text-field>
                </v-card-title>
                <template>
                    <v-data-table
                        v-model="selected"
                        :search="search"
                        :headers="headers"
                        :items="desserts"
                        item-key=""
                        show-select
                        class="elevation-1">
                    </v-data-table>
                </template>
            </v-main>
        </v-app>
    </div>
</template>

<script>
    export default {
        props: ['responsible'],
        data () {
            return {
                search: '',
                infoUser: '',
                singleSelect: false,
                selected: [],
                headers: [
                    { text: 'N Consultorio', value: '' },
                    { text: 'Peso recolectado', value: '' },
                    { text: 'Total a pagar', value: '' },
                    { text: 'Estado factura', value: '' },
                ],
                desserts: [],
            } 
        },

        created(){
            this.infoUser = this.responsible,
            this.initialize()
        },

        methods: {
            initialize(){
                console.log(this.infoUser);
            },

            generateUniqueId: function() {
                return Math.random().toString(36).substr(2, 20);
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