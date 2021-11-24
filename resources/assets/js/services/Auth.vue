<script>
	export default {
		authenticated: false,
		computed: {
	        auth: function() {
	            return Auth;
	        }
	    },
    	checkAuth() {
        	if (localStorage._uslgn) {
                this._uslgn = JSON.parse(localStorage._uslgn);
                Vue.http.headers.common['Authorization'] = this._uslgn.token;
                Auth.authenticated = true;
            }
        },
        register(data){
            localStorage.setItem('_uslgn', JSON.stringify(data));
        },
        login(data){
            localStorage.setItem('_uslgn', JSON.stringify(data));
        },
        logout(path) {
            var usr     = JSON.parse(localStorage.getItem('_uslgn'));
            var data    = {token:usr.token};
            Vue.http.post('api/account/logout',data).then((response) => {
                Auth.authenticated = false;
                localStorage.removeItem('_uslgn');
                Router.go(path);
            }, (response) => {
                Http.handler(response);
            });
        }
   	}
</script>