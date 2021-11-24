<template>
	<div class="container user-side-menu">
		<div class="col-md-2">
			<ul class="main-menu">
				<router-link to="/my-profile/dashboard"><li>Dashboard</li></router-link>
				<router-link to="/my-profile/settings"><li>Settings</li></router-link>
			</ul>
		</div>
		<div class="col-md-10 content-menu">
		   <router-view></router-view>
		</div>
	</div>
</template>

<script>
	export default {
		data(){
			return{
			}
		},
	    created(){
	    	
	    }
   	}
</script>