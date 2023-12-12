<template>
    <div class="bg-white" style="height: 100vh; overflow-y: scroll"> 
        <v-app>
            <v-main>
                <template>
                    <div class="my-6">
                        <h1 class="text-center">Tarifas de los residuos (KG)</h1>
                        <v-card-text>
                            <div>RESIDUO NO PELIGROSO</div>
                            <div class="row text--primary mt-6">
                                <div class="col-m-2 col-sm-3 text-center">
                                    <h6>Biodegradable</h6>
                                    <v-text-field
                                        v-model="biodegradable"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-2 col-sm-3 text-center">
                                    <h6>Reciclables</h6>
                                    <v-text-field
                                        v-model="reciclables"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-2 col-sm-3 text-center">
                                    <h6>Inertes</h6>
                                    <v-text-field
                                        v-model="inertes"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-2 col-sm-3 text-center">
                                    <h6>Oridinarios-Comunes</h6>
                                    <v-text-field
                                        v-model="oridinarios"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                            </div>
                        </v-card-text>
                        <v-card-text>
                            <div>RESIDUO PELIGROSO</div>
                            <p class="text-h4 text--primary text-center">
                                Infeccioso o riesgo biologico
                            </p>
                            <div class="row text--primary mt-6">
                                <div class="col-m-2 col-sm-3 text-center">
                                    <h6>Biosanitarios</h6>
                                    <v-text-field
                                        v-model="biosanitarios"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-2 col-sm-3 text-center">
                                    <h6>Anatomopatologicos</h6>
                                    <v-text-field
                                        v-model="anatomopatologicos"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-2 col-sm-3 text-center">
                                    <h6>Cortopunzantes</h6>
                                    <v-text-field
                                        v-model="cortopunzantes"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-2 col-sm-3 text-center">
                                    <h6>Aniamales</h6>
                                    <v-text-field
                                        v-model="aniamales"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                            </div>
                        </v-card-text>
                        <v-card-text>
                            <div>RESIDUO PELIGROSO</div>
                            <p class="text-h4 text--primary text-center">
                                Quimicos
                            </p>
                            <div class="row text--primary mt-6">
                                <div class="col-m-3 col-sm-4 text-center">
                                    <h6>Farmacos</h6>
                                    <v-text-field
                                        v-model="farmacos"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-3 col-sm-4 text-center">
                                    <h6>Citotóxicos</h6>
                                    <v-text-field
                                        v-model="citotóxicos"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-3 col-sm-4 text-center">
                                    <h6>Metales pesados</h6>
                                    <v-text-field
                                        v-model="metales"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-3 col-sm-4 text-center">
                                    <h6>Reactivos</h6>
                                    <v-text-field
                                        v-model="reactivos"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-3 col-sm-4 text-center">
                                    <h6>Contenedores presurizados</h6>
                                    <v-text-field
                                        v-model="contenedores"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-3 col-sm-4 text-center">
                                    <h6>Aceites usados</h6>
                                    <v-text-field
                                        v-model="aceites"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                            </div>
                        </v-card-text>
                        <v-card-text>
                            <div>RESIDUO PELIGROSO</div>
                            <p class="text-h4 text--primary text-center">
                                Radiactivos
                            </p>
                            <div class="row text--primary mt-6">
                                <div class="col-m-4 col-sm-6 text-center">
                                    <h6>Fuentes abiertas</h6>
                                    <v-text-field
                                        v-model="fuentesA"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                                <div class="col-m-4 col-sm-6 text-center">
                                    <h6>Fuentes cerradas</h6>
                                    <v-text-field
                                        v-model="fuentesC"
                                        type="text"
                                        value=""
                                        prefix="$"
                                        @input="formater"
                                    ></v-text-field>
                                </div>
                            </div>
                        </v-card-text>
                        <v-btn
                            class="mr-6"
                            block
                            depressed
                            color="primary"
                            @click="dataArray">
                            Confirmar cambios
                            <v-icon dark>
                                mdi-plus
                            </v-icon>
                        </v-btn>
                    </div>
                </template>
            </v-main>
        </v-app>
    </div>
</template>

<script>
    import accounting from 'accounting'

    export default {
        data: () => ({
            data:[],
            biodegradable: "0",
            reciclables: "0",
            inertes: "0",
            oridinarios: "0",
            biosanitarios: "0",
            anatomopatologicos: "0",
            cortopunzantes: "0",
            aniamales: "0",
            farmacos: "0",
            citotóxicos: "0",
            metales: "0",
            reactivos: "0",
            contenedores: "0",
            aceites: "0",
            fuentesA: "0",
            fuentesC: "0",
        }),

        methods: {
            dataArray(){
                this.data[0] = this.biodegradable;                        
                this.data[1] = this.reciclables;                    
                this.data[2] = this.inertes;                    
                this.data[3] = this.oridinarios;                    
                this.data[4] = this.biosanitarios;                    
                this.data[5] = this.anatomopatologicos;                    
                this.data[6] = this.cortopunzantes;                    
                this.data[7] = this.aniamales;                    
                this.data[8] = this.farmacos;                    
                this.data[9] = this.citotóxicos;                    
                this.data[10] = this.metales;                    
                this.data[11] = this.reactivos;                    
                this.data[12] = this.contenedores;                    
                this.data[13] = this.aceites;                    
                this.data[14] = this.fuentesA;                    
                this.data[15] = this.fuentesC; 
                console.log(this.data);     
                this.register();              
            },
            
            register(){
                if (this.data.length != 0) {
                    for (let i = 0; i < this.data.length; i++) {
                        if (this.data[i] != 0) {
                            axios.put(`/manager/registerPrice/${i+1}`, {data: this.data[i]}).then(res =>{
                                console.log("Respuesta del servidor");
                                console.log(res.data);
                            }).catch(error =>{
                                console.log("Error en servidor");
                                console.log(error);
                                console.log(error.response);
                            });
                        }
                    }
                }else{
                    
                }
            },

            formater(){
                this.biodegradable = accounting.formatMoney(this.biodegradable, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.reciclables = accounting.formatMoney(this.reciclables, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.inertes = accounting.formatMoney(this.inertes, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.oridinarios = accounting.formatMoney(this.oridinarios, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.biosanitarios = accounting.formatMoney(this.biosanitarios, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.anatomopatologicos = accounting.formatMoney(this.anatomopatologicos, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.cortopunzantes = accounting.formatMoney(this.cortopunzantes, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.aniamales = accounting.formatMoney(this.aniamales, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.farmacos = accounting.formatMoney(this.farmacos, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.citotóxicos = accounting.formatMoney(this.citotóxicos, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.metales = accounting.formatMoney(this.metales, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.reactivos = accounting.formatMoney(this.reactivos, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.contenedores = accounting.formatMoney(this.contenedores, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.aceites = accounting.formatMoney(this.aceites, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.fuentesA = accounting.formatMoney(this.fuentesA, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
                
                this.fuentesC = accounting.formatMoney(this.fuentesC, {
                    symbol: '',
                    precision: '',
                    thousand: ',',
                    decimal: '.'
                });
            },
        }
    }
</script>