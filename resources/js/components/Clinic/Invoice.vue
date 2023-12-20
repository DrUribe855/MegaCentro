<template>
    <div>
        <v-app>
            <v-main>
                <h5 class="mx-4 mt-4 mb-0">{{ title }}</h5>
                <v-card-title>
                    {{ infoUser.document }}
                    <v-spacer></v-spacer>
                    <v-select
                        v-model="selectedFilter"
                        class="mb-2 mr-5 complet"
                        auto-select-first
                        label="Ver por"
                        prepend-icon="mdi-filter"
                        :items="items"
                        :onchange="changeFilter()">   
                    </v-select>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Buscar"
                        single-line
                        hide-details    
                    ></v-text-field>
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
                selectedFilter: '',
                singleSelect: false,
                showBtn: true,
                price: '',
                position: '',
                selected: [],
                headers: [
                    { text: 'N Consultorio', value: 'clinic_number' },
                    { text: 'Peso recolectado', value: 'totalWeight' },
                    { text: 'Pago total', value: 'totalInvoice' },
                    { text: 'Estado factura', value: 'invoice_status' },
                    { text: 'Fecha recoleccion', value: 'created_at' },
                ],
                items: ['Facturas pagadas', 'Facturas por recolecciones'],
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
                for (let i = 0; i < this.infoUser.clinic_user.length; i++) {
                    if (this.infoUser.clinic_user[i].clinic.collection_log.length == 0) {
                    }
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
                            totalWeight = totalWeight + this.infoUser.clinic_user[i].clinic.collection_log[j].waste_collection[l].weight; 
                            totalInvoice = totalInvoice + (this.infoUser.clinic_user[i].clinic.collection_log[j].waste_collection[l].residues.price * this.infoUser.clinic_user[i].clinic.collection_log[j].waste_collection[l].weight)
                        }
                        dessertsTemp.totalInvoice = this.foramat(totalInvoice);
                        dessertsTemp.totalWeight = totalWeight;
                        this.desserts.push(dessertsTemp);
                    }
                }
            },

            payBill(){// Pagar factura
                console.log(this.selected);
                let alert = true;
                this.cleanData = this.selected.length;
                if (this.selected.length != 0) {
                    for (let i = 0; i < this.selected.length; i++) {
                        axios.post(`/residue/payBill/${this.selected[i].id}`).then(res => {
                            this.price = this.foramat(this.price.replace(/\D/g, '') - this.selected[i].totalInvoice.replace(/\D/g, ''));
                            if (this.price <= '$1') {
                                console.log(this.price ," -- ", this.selected[i].totalInvoice);
                                this.price = 0;
                            }
                            if (alert) {
                                this.title = 'Facturas pagadas';
                                this.showBtn = false;
                                this.alertTrue("La factura fue pagada correctamente");
                                this.billsPaid('Pago');
                            }
                            this.cleanData--;
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
                number = accounting.formatMoney(number, {
                    symbol: '$',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
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

            changeFilter(){
                if (this.selectedFilter == 'Facturas pagadas' ) {
                    this.selectedFilter = 'Ver por'
                    this.title = 'Facturas pagadas'
                    this.showBtn = false;
                    this.billsPaid('Pago');
                }else if (this.selectedFilter == 'Facturas por recolecciones'){
                    this.infoUser = this.infoUserTemp;
                    this.title = 'Facturas por recolecciones';
                    this.showBtn = true;
                    this.billsPaid('Debe');
                    this.selectedFilter = 'Ver por'
                }
            }
        }
    }
</script>
