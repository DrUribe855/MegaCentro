<template>
	<div>
        <v-app>
            <v-main>
        		<div class="container mt-5">
                    <div class="row justify-content-center mt-5">
                        <div class="col-md-9 col-lg-10 mt-5">
                            <v-card class="pa-4 text-center" elevation="5">
                            <div class="text-center mb-4 mt-3">
                                <img src="./img/logo-megacentro.png" alt="Logo Megacentro" width="250em" class="img-fluid">
                            </div>
                    			<form>
                                    <div class="form-group">
                                        <v-text-field
                                          label="Correo electronico"
                                          :rules="rules"
                                          v-model="email"
                                          name="email"
                                          type="email"
                                          hide-details="auto"
                                          class="mb-3"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-group">
                    				    <v-text-field
                                          label="Contraseña"
                                          :rules="rules"
                                          v-model="password"
                                          name="password"
                                          type="password"
                                          hide-details="auto"
                                          class="mb-3"
                                        ></v-text-field>
                                    </div>
                    				<v-btn 
                                    class="mt-4 mb-2 block mx-auto"
                                    color="primary"
                                    @click="onSubmit()">
                                      Iniciar sesión
                                    </v-btn>
                    			</form>
                            </v-card>
                        </div>
                    </div>
        		</div>
            </v-main>
        </v-app>
	</div>
</template>
<script>
    
import swal from 'sweetalert';

export default {
    data() {
        return {
            email: '',
            password: '',
            error: false,
            rules: [
                value => !!value || 'Obligatorio',
            ],
        };
    },
    methods: {
        onSubmit() {
            axios.post('/login2', {
                email: this.email,
                password: this.password,
            }).then(response => {
                console.log(response.data);
                if (response.data.status === true) {
                    window.location.href = '/index';
                } else if(response.data.status === false && response.data.message === 'No registrado') {
                    this.showAlert('Error', 'El usuario no se encuentra registrado en el sistema', 'error');
                    this.error = true;
                }else if(response.data.status === false && response.data.message === 'Cuenta desactivada'){
                    this.showAlert('Error', 'El usuario se encuentra desactivado','error');
                }else if(response.data.status === false && response.data.message === 'Datos incorrectos'){
                    this.showAlert('Error', 'Alguno de los datos ingresados es incorrecto','error');
                }
            }).catch(error => {
                this.showAlert('Error', 'Error no identificado', 'error');
            	console.log("Error en Login");
                console.log(error.response);
            });
        },
        showAlert(title, text, icon){
            swal({
                title: title,
                text: text,
                icon: icon,
            });
        },
    },
};
</script>