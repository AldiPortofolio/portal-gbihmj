<template>
	<div>
		<form>
			<h3 class="text-center">New Restaurant</h3>
			<div class="alert alert-danger alert-dismissible" v-show="errorText">
				{{errorText}}
			</div>
			<div class="form-group">
				<label>Restaurant Name</label><span class="required">*</span>
				<input type="text" v-model="restaurant.restaurant_name" placeholder="Restaurant Name" autofocus required>
			</div>
			<div class="form-group workhours">
				<label>Work Hours</label>
				<div class="row">
					<div class="col-md-12 days-list">
						<div class="form-group">
							<div v-for="ld in listDay" class="col-xs-3 col-md-1 noselect listdayRadio" v-bind:class="{ active: listDayChecked.indexOf(ld) > -1}" @click="updateListDayChecked(ld)">
								{{ld}}
							</div>
						</div>
					</div>
				</div>
				<div class="hours-list">
					<div class="row">
						<div class="col-md-6">
							<label>From</label>
							<multiselect v-model="hours.from" :selected="hours.from" :options="listHours" :close-on-select="true" :searchable="false" :allow-empty="false" placeholder="From"></multiselect>
						</div>
						<div class="col-md-6">
							<label>To</label>
							<multiselect v-model="hours.to" :selected="hours.to" :options="listHours" :close-on-select="true" :searchable="false" :allow-empty="false" placeholder="To"></multiselect>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<button type="button" @click="addHours()">Add Hours</button>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label>Address</label><span class="required">*</span>
				<input type="text" v-model="restaurant.address" placeholder="Restaurant Address" required>
			</div>
			<div class="form-group">
				<label>Location</label>
				<gmaps v-model="restaurant.location" placeholder="Location" autocompletes="true" maps="true" :mapOptions="{dragMarker:true}" @getLatlng="updateLatLng"></gmaps>
			</div>
			<div class="form-group">
				<label>Landmark (THIS WILL HELP US LOCATE THIS OUTLET A LOT QUICKER)</label>
				<input type="text" v-model="restaurant.location_description" placeholder="Restaurant Landmark Hints">
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Restaurant Phone Number</label>
						<input type="number" v-model="restaurant.phone" placeholder="Restaurant Phone Number">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Restaurant Email</label>
						<input type="email" v-model="restaurant.email" placeholder="Restaurant Email">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Restaurant Status</label>
						<multiselect v-model="restaurant.status_condition" :selected="restaurant.status_condition" :options="status_condition" :close-on-select="true" :searchable="false" :allow-empty="false" label="name" placeholder="Restaurant Status"></multiselect>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Website</label>
						<input type="text" v-model="restaurant.website" placeholder="Restaurant Website">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="form-group">
					<label>Features</label>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-4" v-for="f in feature">
							<input type="checkbox" :id="f.id" :value="f.id" v-model="restaurant.feature"> 
							<label :for="f.id" class="noselect"><b>{{f.feature_name}}</b></label>
						</div>
					</div>
				</div>
			</div>

			<div class="clearfix"></div>
			<div class="form-group">
				<label>Tags</label>
				<multiselect :selected="restaurant.tag" :options="tag" :multiple="true" :close-on-select="false" label="tag_name" placeholder="Tag" @input="updateTag"></multiselect>
			</div>
			<div class="form-group">
				<label>Foods</label>
				<multiselect :selected="restaurant.food" :options="food" :multiple="true" :close-on-select="false" label="food_name" placeholder="Cuisines" @input="updateFood"></multiselect>
			</div>
			<button type="button" @click="save()">Save</button>
		</form>
	</div>
</template>
<script>
	import Multiselect from 'vue-multiselect'
  	import gmaps from '../../../../libs/gmaps.vue'
	export default {
	    data(){
	    	return{
	    		restaurant:{
	    			restaurant_name:'',
	    			address:'',
	    			location:'',
	    			locationlatlng:'',
	    			location_description:'',
	    			phone:'',
	    			email:'',
	    			website:'',
	    			feature:[],
	    			food:'',
	    			tag:'',
	    			work_hours:[],
	    			status_condition:{id:1,name:'Open'}
	    		},
				feature: [],
				food:[],
				tag:[],
				listDay:[],
				listDayChecked:[],
				listHours:[],
				hours:{
					from:'',
					to:''
				},
				errorText:'',
	    	}
	    },components: { 
	    	Multiselect,
	    	gmaps
	    },watch:{
	    	listDayChecked(){
	    	}
	    },created(){
	    	this.$http.get('my-restaurant/create').then((response) => {
	    		this.feature 	= response.data.feature;
	    		this.food 		= response.data.food;
	    		this.tag 		= response.data.tag;
	    		this.listDay	= response.data.listDay;
	    		this.listHours 	= response.data.listHours;
	      	});
	      	this.status_condition 	= [
	      		{id:1,name:'Open'},
	      		{id:2,name:'Opening Soon'}
	      	];
	    },methods:{
	    	updateListDayChecked(val){
	    		if(this.listDayChecked.indexOf(val) < 0){
	    			this.listDayChecked.push(val);
	    		}else{	    			
	    			var that 	= this;
		    		this.listDayChecked.forEach(function(v,i){
		    			if(val == v){
		    				that.listDayChecked.splice(i,1);
		    				return;
		    			}
		    		});
	    		}
	    	},addHours(){
	    		var that 	= this;
	    		this.listDayChecked.forEach(function(v,i){
	    			console.log(that.restaurant.work_hours[v]);
	    			console.log(v);
	    		});
	    		console.log(this.listDayChecked);
	    	},updateTag (data) {
	    		this.restaurant.tag 	= data;
		    },updateFood (data) {
	    		this.restaurant.food 	= data;
		    },updateLatLng(val){
		    	this.restaurant.locationlatlng = val;
		    },save(){
		    	var restaurant 		= this.restaurant;
		    	this.errorText 		= '';
		    	if(restaurant.restaurant_name == ''){
		    		this.errorText	= 'Restaurant name is required';
		    	}else if(restaurant.address == ''){
		    		this.errorText	= 'Restaurant address is required';
		    	}
	    		console.log(this.hours);
	    		if(!this.errorText){
		    		this.$http.post('my-restaurant/store',this.restaurant).then((response) => {
			    		
			      	});
			    }else{
		    		window.scrollTo(500, 0);
			    }
			    
		    }
	    }
	};
</script>
<style>

</style>