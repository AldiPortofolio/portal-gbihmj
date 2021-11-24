<script>
	export default {
		data () {
	    	return {
	    		status:'',
	        	errorText: ''
	      	};
	    },getArraySingleData:function(data,name,val){
        	var result = '';
        	data.forEach(function(v,i){
				if(v['name'] == val){
					result = v; 
				}
			});
			return result;
        },ucwords:function (str) {
		    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
		        return $1.toUpperCase();
		    });
		},strtolower:function (str) {
		    return (str+'').toLowerCase();
		},checkEmailFormat:function(value){
			var regex 		= /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
			if (value.search(regex) == -1){
				this.status 		= false;
			    this.errorText 		= 'Please enter a valid email address.';
			}else{
				this.status 		= true;
			    this.errorText 		= '';
			}
			var data 		= {status:this.status,text:this.errorText};
    		return data;
		},checkRequired:function(value,label){
			if(value == '' || value == undefined){
				this.errorText = this.ucwords(label)+' is required';
			    this.status = false;
			}else{
			    this.errorText = '';
			    this.status = true;
			}
			var data 		= {status:this.status,text:this.errorText};
    		return data;
		},checkLength:function(value,minLength){
			if(value.length < minLength){
				this.errorText 	= 'Minimal length is '+minLength;
			    this.status 	= false;
			}else{
			    this.errorText 	= '';
			    this.status 	= true;
			}
			var data 		= {status:this.status,text:this.errorText};
    		return data;
		}
   	}
</script>