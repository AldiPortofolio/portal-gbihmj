<template>
	<div>
		<input
			type = "text"
			ref = "input"
			@input = "updateValue()"
			v-bind:value="value"
			v-bind:placeholder = "placeholder"
			v-if="autocompletes"
		>
		<div id="map" v-if="maps"></div>
	</div>
</template>
<script>
	export default {
		props: [
					'class', 
					'placeholder',
					'value',
					'autocompletes',
					'maps',
					'mapLat',
					'mapLng',
					'mapOptions'
		],
		data: function (){
			return {
				api: {
					domain: 'https://maps.googleapis.com/maps/api/js',
					key: window.GOOGLE_AUTOCOMPLETE_KEY,
					libraries: 'places',
				},
				map:'',
				marker:''
			}
		},
		mounted: function (){
			window.onload = this.bindData();
		},
		methods:{
			bindData:function(){
				if(this.autocompletes){
					this.bindAutocomplete();
				}
				if(this.maps){
					this.bindMaps();
				}
			},bindAutocomplete: function (){
				this.autocomplete = new google.maps.places.Autocomplete(
			        this.$refs.input,{
			            types: ['geocode']
			        }
			    );
			    this.autocomplete.addListener('place_changed', this.pipeAddress);
			},updateValue:function(){
				this.$emit('input',this.$refs.input.value);
			},pipeAddress: function (){
				var data  		= {};
				var place 		= this.autocomplete.getPlace();
				if(place.formatted_address !== undefined){
					var location 	= {lat:place.geometry.location.lat(),lng:place.geometry.location.lng()};
					if(this.maps){
						this.mapsUpdateMarkerSearch(location);
					}
					this.$emit('input',place.formatted_address);
					this.$emit('getLatlng',location);
				}
			},bindMaps:function(){
				var mapDiv = document.getElementById('map');
		        this.map = new google.maps.Map(mapDiv, {
		          zoom: 15,
		          zoomControl: this.mapOptions ? this.mapOptions.zoomControl ? false : true : true, 
		          scrollwheel: this.mapOptions ? this.mapOptions.scrollwheel ? false : true : true, 
		          disableDoubleClickZoom: this.mapOptions ? this.mapOptions.disableDoubleClickZoom ? false : true : true, 
		          draggable: this.mapOptions ? this.mapOptions.draggable ? false : true : true
		        });
		        if(this.mapLat && this.mapLng){
			        var pos = {
	              		lat: this.mapLat,
	              		lng: this.mapLng
	            	};
	            	this.setMarker(pos);
		        }else{
		        	if (navigator.geolocation) {
		        		var that 	= this;
			          	navigator.geolocation.getCurrentPosition(function(position) {
			            	var pos = {
			              		lat: position.coords.latitude,
			              		lng: position.coords.longitude
			            	};
			            	that.setMarker(pos);
			          	}, function() {
			            	alert('your geolocation is not activated');
			         	});
			        } else {
			          	alert('Your browser us not support geolocation');
			        }
		        }
			},setMarker:function(location){
            	this.map.setCenter(location);
            	this.marker = new google.maps.Marker({
              		position: location,
	            	map: this.map,
	            	animation: google.maps.Animation.DROP,
	            	title: 'You are here',
	       	        draggable:this.mapOptions ? this.mapOptions.dragMarker ? true : false : false
	            });
				this.$emit('getLatlng',location);
	            this.marker.addListener('dragend',this.mapsUpdateMarker);
			},mapsUpdateMarker:function(event){
				var location 	= {lat:event.latLng.lat(),lng:event.latLng.lng()};
	            this.map.setCenter(location);
				this.$emit('getLatlng',location);
			},mapsUpdateMarkerSearch:function(location){
				this.marker.setPosition(location);
				this.map.panTo(this.marker.position);
	            this.map.setCenter(location);
				this.$emit('getLatlng',location);
			}
		}
	};
</script>
<style>
  #map{
    height:400px;
    margin-top:10px;
  }
</style>