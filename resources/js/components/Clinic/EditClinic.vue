<template>
    <div>
        <div class="row">
            <div class="col-6 col-sm-11 col-md-6">
                <div class="d-flex justify-content-end">
                    <h1>{{ infoUser.document }}2384</h1>
                </div>
                <div id="emailHelp" class="form-text d-flex justify-content-end mr-3">Documento del responsable</div>
            </div>
            <div class="col-6 col-sm-1 col-md-6">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary my-4 mr-4" @click="$parent.goToBack()">
                        Volver
                    </button>
                </div>
            </div>
        </div>
        <div class="col-12 m-0 p-0 row">
            <div class="col-6 col-sm-4 col-lg-2 m-0 p-2" v-for="clinic in selectedClinic">
                <div class="col card w-80 h-100 m-0">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="col-12 card-title mb-0">
                            <h5 class="m-0 me-2 text-dark text-uppercase text-center" v-if="clinic.clinic_tower && clinic.clinic_tower[0]"><b>{{ clinic.clinic_tower[0].tower_id  }}</b></h5> 
                                <div id="emailHelp" class="form-text">Numero De Torre.</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mt-2 mb-3" style="position: relative;">
                            <div class="d-flex flex-column align-items-center">
                                <!-- <h2 class="mb-0" v-if="clinic.clinic_tower && clinic.clinic_tower[0]"> {{ clinic.clinic_tower[0].tower_id }} </h2> -->
                                <h2 class="mb-0"> {{ clinic.clinic_number }} </h2>
                                <span>Numero Clinica</span>
                            </div>
                        </div>
                        <div class="col-12 m-0 p-0 text-end">
    						<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#edit" >EDITAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['clinic'],

        components: {},
        
        data: () => ({
            selectedClinic: [],
            infoUser: {},
            tower: [],
        }),

        created () {
            this.selectedClinic = this.clinic.clinic
            this.infoUser = this.clinic
            console.log(this.selectedClinic);
        },

        methods: {},
    }
</script>