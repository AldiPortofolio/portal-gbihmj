<template>
	<transition name="fade">
	    <div class="login modal-mask">
	      <div class="modal-wrapper">
	        <div class="modal-container">
	          	<div class="modal-body">
	          		<div class="alert alert-danger alert-dismissible" v-show="errorText">
						{{errorText}}
					</div>
	          		<div v-if="action=='login'">
		          		<form v-on:submit.prevent="vlogin()">
		          			<h3>Login</h3>
			          		<div class="form-group">
						        <input type="email" v-model="login.email" placeholder="Email" required autofocus>
							</div>
							<div class="form-group">
						        <input type="password" v-model="login.password" placeholder="Password" required>
							</div>
							<div class="form-group">
								<button type="submit" class="login-button">Login</button>
							</div>
						</form>
						<div class="row change-action">
							<div class="col-xs-6 text-left">
								<a @click="openRegister">Register</a>
							</div>
							<div class="col-xs-6 text-right">
								<a @click="openPassword">Forgot Password</a>
							</div>
						</div>
					</div>
					<div v-if="action=='register'">
		          		<form v-on:submit.prevent="vregister()">
		          			<h3>Register</h3>
		          			<div class="form-group">
						        <input type="text" placeholder="Full Name" v-model="register.name" required autofocus>
							</div>
			          		<div class="form-group">
						        <input type="email" placeholder="Email" v-model="register.email" required>
							</div>
							<div class="form-group">
						        <input type="password" placeholder="Password" v-model="register.password" required>
							</div>
							<div class="form-group">
								<button type="submit" class="login-button">Register</button>
							</div>
							<div class="row change-action">
								<div class="col-xs-6 text-left">
									<a @click="openLogin">Login</a>
								</div>
								<div class="col-xs-6 text-right">
									<a @click="openPassword" class="forgot-password-action">Forgot Password</a>
								</div>
							</div>
						</form>
					</div>
					<div v-if="action=='password'">
		          		<form v-on:submit.prevent="vpassword()">
		          			<h3>Forgot Password</h3>
			          		<div class="form-group">
						        <input type="email" placeholder="Email" v-model="password.email" required>
							</div>
							<div class="form-group">
								<button type="submit" class="login-button">Login</button>
							</div>
							<div class="row change-action">
								<div class="col-xs-6 text-left">
									<a @click="openLogin">Login</a>
								</div>
								<div class="col-xs-6 text-right">
									<a @click="openRegister">Register</a>
								</div>
							</div>
						</form>
					</div>
		        </div>
	          	<div class="modal-footer">
	            	<a @click="closeLogin" class="login-close"><i class="fa fa-times fa-2x"></i></a>
	          	</div>
	        </div>
	      </div>
	    </div>
	</transition>
</template>

<script>

	export default {
		data(){
			return{
				action: 'login',
				errorText:'',
				login:{
					'email': '',
					'password':''
				},
				register:{
					'name':'',
					'email': '',
					'password':''
				},
				password:{
					'email': ''
				}
			}
		},
		props:['actions'],
		created(){
			this.action 	= this.actions;
		},
	    methods:{
	    	closeLogin: function () {
	    		this.$emit('close');
			},openRegister:function(){
				this.action 	= 'register';
			    this.errorText	= '';
			},openLogin:function(){
				this.action 	= 'login';
			    this.errorText	= '';
			},openPassword:function(){
				this.action 	= 'password';
			    this.errorText	= '';
			},vlogin(){
				this.$http.post('account/login',this.login).then((response) => {
		      		var res 	= response.data;
		      		if(res.user.status == 'success'){
			      		this.$cookie.set('qwerty', res.user.token, { expires: '1d' });
			     		this.$store.dispatch('auth',res);
			     		this.closeLogin();
					}else{
						this.errorText		= res.user.text;
					}
		      	});
			},vregister(){
				this.$http.post('account/register',this.register).then((response) => {
		      		var res 	= response.data;
		      		if(res.user.status == 'success'){
			      		this.$cookie.set('qwerty', res.user.token, { expires: '1d' });
			     		this.$store.dispatch('auth',res);
			     		this.closeLogin();
					}else{
						this.errorText		= res.user.text;
					}
		      	});
			},vpassword(){
				console.log(this.password);
			}
		}
   	}
</script>