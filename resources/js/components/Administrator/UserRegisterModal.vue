<template>
	<div>
		<div class="modal fade" id="userRegisterModal" tabindex="-1" role="dialog" aria-labelledby="registroModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="registroModalLabel">Formulario de Registro</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					    </button>
					</div>
					<div class="modal-body">
					    <form>
					        <div class="form-row">
					            <div class="form-group col-md-6">
						            <label for="document">Documento</label>
						            <input type="number" class="form-control" v-model="documento" id="number" placeholder="Documento" required>
					            </div>
					            <div class="form-group col-md-6">
					                <label for="name">Nombre</label>
					                <input type="text" class="form-control" v-model="name" id="name" placeholder="Nombre" required>
					            </div>
					        </div>
					        <div class="form-row">
						        <div class="form-group col-md-6">
						            <label for="lastname">Apellido</label>
						            <input type="text" class="form-control" v-model="lastname" id="lastname" placeholder="Apellido" required>
						        </div>
						        <div class="form-group col-md-6">
						            <label for="phone">Teléfono</label>
						            <input type="number" class="form-control" v-model="phone" id="phone" placeholder="Teléfono" required>
					            </div>
					        </div>
					        <div class="form-row">
						        <div class="form-group col-md-6">
							            <label for="email">Correo electronico</label>
							            <input type="email" class="form-control" v-model="email" id="email" placeholder="Correo electronico" required>
						        </div>
						        <div class="form-group col-md-6">
						            <label for="role">Cargo</label>
						            <select v-model="role" class="custom-select">
						            	<option value="Administrador">Administrador</option>
						            	<option value="Auxiliar contable">Auxiliar contable</option>
						            	<option value="Recolector">Recolector</option>
						            </select>
						        </div>
					        </div>
					        <div class="form-row">
					        	<div class="form-group col-md-12">
					        		<label for="password">Contraseña</label>
					        		<input type="password" class="form-control" v-model="password" id="password" required>
					        	</div>
					        </div>
					    </form>
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					  <button type="button" class="btn btn-primary" @click="registerEmployees()">Registrarse</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>

	export default{
		props: [],
		components:{
		},
		data(){
			return {
				// Si es true carga el formulario de usuario, si es false carga el de la tienda
				visibilityForm: true,
				documento: '',
				name: '',
				lastname: '',
				phone: '',
				email: '',
				role: '',
				password: '',
			}
		},
		created(){
			
		},
		methods: {
			registerEmployees(){

				const information = {
					documento : this.documento,
					name : this.name,
					lastname : this.lastname,
					phone : this.phone,
					email : this.email,
					role : this.role,
					password : this.password,
				};

				console.log(information.documento);
				axios.post('administrator/registerEmployees', information).then(resp => {
					console.log('Datos enviados correctamente: ', resp.data);
					this.$parent.getEmployees();
					this.$parent.closeRegisterModal();
				}).catch(error => {
					console.log("Error en axios");
					console.log(error);
					console.log(error.response);
				})

			}
		}
			
	}
</script>