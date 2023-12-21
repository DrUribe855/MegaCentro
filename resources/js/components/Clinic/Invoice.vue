<template>
    <div>
        <v-app>
            <v-main>
                <h5 class="mx-4 mt-4 mb-0">{{ title }}</h5>
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
                    <v-btn
                        class="mt-3 ml-4"
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
                </v-card-title>
                <template>
                    <!-- Tabla de las facturas no pagadas -->
                    <v-data-table
                        v-if="showBtn"
                        v-model="selected"
                        :search="search"
                        :headers="headers"
                        :items="desserts"
                        item-key="positionId"
                        show-select
                        class="elevation-1 mt-5"
                        sort-by="calories">
                        <template v-slot:top>
                            <v-toolbar flat>
                                <v-toolbar-title>Total: {{ price }}</v-toolbar-title>
                                <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                ></v-divider>
                                <v-spacer></v-spacer>
                                <v-btn
                                    v-if="showBtn"
                                    color="primary"
                                    dark
                                    class="mb-2 ml-4"
                                    @click="payBill">
                                    <v-icon dark>
                                        mdi-currency-usd
                                    </v-icon>
                                    Pagar
                                </v-btn>
                            </v-toolbar>
                        </template>
                    </v-data-table>
                    <!-- Tabla de pagos -->
                    <v-data-table
                        v-if="!showBtn"
                        v-model="selected"
                        :search="search"
                        :headers="headers"
                        :items="desserts"
                        class="elevation-1 mt-5">
                        <template v-slot:top>
                            <v-toolbar flat>
                                <v-toolbar-title>Total: {{ price }}</v-toolbar-title>
                                <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                ></v-divider>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                        </template>
                    </v-data-table>
                </template>
                <v-card-actions class="d-flex justify-left align-left">
                    <v-btn
                        depressed
                        color="primary"
                        @click="$parent.goToBackInvoice(price, position)">
                        volver
                    </v-btn> 
                </v-card-actions>
            </v-main>
        </v-app>
    </div>
</template>

<style scoped>
    .complet {
        max-width: 280px;
        font-size: 15px;
        height: 30px;
    }
</style>


<script>
    import accounting from 'accounting'
    export default {
        props: ['responsible'],
        data () {
            return {
                title: 'Facturas por recolecciones',
                search: '',
                infoUser: '',
                infoUserTemp: '',
                changeTable: 'Facturas pagas',
                price: '',
                position: '',
                singleSelect: false,
                showBtn: true,
                selected: [],
                headers: [
                    { text: 'N Consultorio', value: 'clinic_number' },
                    { text: 'Peso recolectado', value: 'totalWeight' },
                    { text: 'Pago total', value: 'totalInvoice' },
                    { text: 'Estado factura', value: 'invoice_status' },
                    { text: 'Fecha recoleccion', value: 'created_at' },
                ],
                desserts: [],
                cleanData: -1,
            } 
        },

        created(){
            this.infoUser = this.responsible,
            this.infoUserTemp = this.responsible,
            this.price = this.infoUser.invoice,
            this.position = this.infoUser.position,
            this.billsPaid('Debe')
        },

        methods: {
            initialize(){
                this.desserts = [];
                console.log(this.infoUser);
                let prueba = 0;
                for (let i = 0; i < this.infoUser.clinic_user.length; i++) {
                    for (let j = 0; j < this.infoUser.clinic_user[i].clinic.collection_log.length; j++) {
                        let totalWeight = 0;
                        let totalInvoice = 0;
                        let dessertsTemp = {...this.infoUser.clinic_user[i].clinic.collection_log[j]}; 
                        dessertsTemp.clinic_number = this.infoUser.clinic_user[i].clinic.clinic_number;
                        dessertsTemp.totalWeight = 0;
                        dessertsTemp.totalInvoice = 0;
                        dessertsTemp.positionId = this.generateUniqueId();
                        // Formatear fecha
                        const originalDate = new Date(dessertsTemp.created_at);
                        const year = originalDate.getFullYear();
                        const month = String(originalDate.getMonth() + 1).padStart(2, '0');
                        const day = String(originalDate.getDate()).padStart(2, '0');
                        dessertsTemp.created_at = `${year}/${month}/${day}`;
                        for (let l = 0; l < this.infoUser.clinic_user[i].clinic.collection_log[j].waste_collection.length; l++) {
                            totalWeight += this.infoUser.clinic_user[i].clinic.collection_log[j].waste_collection[l].weight; 
                            totalInvoice += this.infoUser.clinic_user[i].clinic.collection_log[j].waste_collection[l].residues.price * this.infoUser.clinic_user[i].clinic.collection_log[j].waste_collection[l].weight;
                            prueba += totalInvoice;
                        }
                        totalWeight = accounting.formatMoney(totalWeight, {
                            symbol: '',
                            precision: '',
                            thousand: '.',
                            decimal: '.'
                        });
                        dessertsTemp.totalInvoice = this.foramat(totalInvoice);
                        dessertsTemp.totalWeight = totalWeight+(' (Kg)');
                        this.desserts.push(dessertsTemp);
                    }
                }
                console.log(prueba);
            },

            payBill(){// Pagar factura
                console.log(this.selected);
                let alert = true;
                this.cleanData = this.selected.length;
                if (this.selected.length != 0) {
                    for (let i = 0; i < this.selected.length; i++) {
                        axios.post(`/residue/payBill/${this.selected[i].id}`).then(res => {
                            this.price = this.foramat(this.price.replace(/\D/g, '') - this.selected[i].totalInvoice.replace(/\D/g, ''));
                            console.log(this.price ," -- ",this.selected[i].totalInvoice);
                            this.cleanData--;
                            if (alert) {
                                this.title = 'Facturas pagadas';
                                this.changeTable = 'Facturas recolecion'
                                this.showBtn = false;
                                this.alertTrue("La factura fue pagada correctamente");
                                this.billsPaid('Pago');
                            }
                            alert = false;
                        }).catch(error => {
                            console.log(error);
                            console.log(error.response);
                            this.alertFalse("Parece que algo salio mal");
                        });
                    }
                }else{
                    this.alertFalse("Debe seleccionar lo que desea pagar");
                }
            },

            billsPaid(status){
                console.log(this.cleanData); 
                if (this.cleanData == 0) {
                    this.selected = [];
                }
                axios.get(`/residue/showInvoince/${this.infoUser.id}/${status}`).then(res => {
                    this.infoUser = [];
                    this.infoUser = res.data.responsible[0];
                    this.initialize();
                }).catch(error => {
                    console.log(error);
                    console.log(error.response);
                    this.alertFalse("Parece que algo salio mal");
                });
            },

            foramat(number){
                if (number < 1000) {
                    number = accounting.formatMoney(number, {
                        symbol: '$',
                        precision: '3',
                        thousand: '.',
                        decimal: '.'
                    });
                }else{
                    number = accounting.formatMoney(number, {
                        symbol: '$',
                        precision: '',
                        thousand: '.',
                        decimal: '.'
                    });
                }
                return number;
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
                
            changeView(){
                console.log(this.changeTable);
                if (this.changeTable == 'Facturas pagas' ) {
                    this.title = 'Facturas pagadas';
                    this.showBtn = false;
                    this.changeTable = 'Facturas recolecion';
                    this.billsPaid('Pago');
                }else if (this.changeTable == 'Facturas recolecion'){
                    this.infoUser = this.infoUserTemp;
                    this.title = 'Facturas por recolecciones';
                    this.changeTable = 'Facturas pagas';
                    this.showBtn = true;
                    this.billsPaid('Debe');
                }
            },
        }
    }
</script>
