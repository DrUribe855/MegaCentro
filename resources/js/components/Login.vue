<template>
	<div>
		<div class="container">
			<h1>Login</h1>
			<form>
				<input type="email" v-model="email" name="email" placeholder="Email" class="form-control">
				<input type="password" v-model="password" name="password" placeholder="Contraseña" class="form-control">
				<button type="button" class="btn btn-primary" @click="onSubmit()">Iniciar sesion</button>
			</form>
		</div>
	</div>
	
</template>
<script>
export default {
    data() {
        return {
            email: '',
            password: '',
            error: false,
        };
    },
    methods: {
        onSubmit() {
            // Enviar los datos de inicio de sesión al controlador
            axios.post('/login2', {
                email: this.email,
                password: this.password,
            }).then(response => {
                console.log(response.data);
                if (response.data.status === 'success') {
                    // El usuario se ha autenticado correctamente
                    // Redireccionar al usuario a la página de inicio
                    window.location.href = '/inicio';
                } else {
                    console.log("La informacion ingresada es incorrecta");

                    this.error = true;
                }
            }).catch(error => {
            	console.log("Error en Login");
                console.log(error.response);
            });
        },
    },
};
</script>