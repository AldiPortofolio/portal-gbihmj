<script>
	export default {
		handler:function(res){
            var status  = res.data.status;
            if(status == 'token'){
                Auth.authenticated = false;
                localStorage.removeItem('_uslgn');
                Router.go('/login');
            }else{  
                console.log('Error');
            }
        }
   	}
</script>