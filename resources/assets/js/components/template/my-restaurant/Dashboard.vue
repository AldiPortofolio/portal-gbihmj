<template>
	<div class="restaurant-dashboard">
		<div v-if="hasData">
			My Restaurant
		</div>
		<div v-if="!hasData" class="no-data">
			<div class="title">Opps! Looks like you doesn't have any restaurant.</div>
			<router-link to="/my-restaurant/restaurant/create"><button class="create-restaurant">Add New One</button></router-link>
		</div>
	</div>
</template>
<script>
	export default {
	    data(){
	    	return{
	    		hasData:false
	    	}
	    },created(){
	    	this.$http.get('my-restaurant/dashboard').then((response) => {
	    		if(response.data.status == 'no_data'){
	    			this.hasData 	= false;
	    		}else{
	    			this.hasData 	= true;
	    		}
	      	});
	    }
	};
</script>
<style>

</style>