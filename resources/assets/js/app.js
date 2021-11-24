import Vue from 'vue'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'
import VueCookie from 'vue-cookie'
import VueHead from 'vue-head'
import Header from './components/include/Header.vue'
import Footer from './components/include/Footer.vue'
import Index from './components/template/Index.vue'
import Contact from './components/template/Contact.vue'
import RestaurantSideMenu from './components/template/my-restaurant/RestaurantSideMenu.vue'
import DashboardRestaurant from './components/template/my-restaurant/Dashboard.vue'
import ListRestaurant from './components/template/my-restaurant/restaurant/Index.vue'
import CreateRestaurant from './components/template/my-restaurant/restaurant/Create.vue'
import UserSideMenu from './components/template/my-profile/UserSideMenu.vue'
import DashboardProfile from './components/template/my-profile/Dashboard.vue'
import Settings from './components/template/my-profile/Settings.vue'
import store from './store'

Vue.use(VueResource)
Vue.use(VueRouter)
Vue.use(VueCookie)
Vue.use(VueHead)
window.GOOGLE_AUTOCOMPLETE_KEY = "AIzaSyBLtKuRbdGLarZxq45Neg8jPRpLr7e2mEc"
//AIzaSyBLtKuRbdGLarZxq45Neg8jPRpLr7e2mEc

const routes = [
    {
        path: '/',
        component: Index
    },{
        path: '/contact',
        component: Contact
    },{
        path: '/my-restaurant/:id',
        component: RestaurantSideMenu,
        meta: { 
            auth: true
        },
        children: [
            {
                path: '/my-restaurant/dashboard',
                component: DashboardRestaurant,
                meta: { 
                    auth: true
                }
            },{
                path: '/my-restaurant/restaurant',
                component: ListRestaurant,
                meta: { 
                    auth: true
                }
            },{
                path: '/my-restaurant/restaurant/create',
                component: CreateRestaurant,
                meta: { 
                    auth: true
                }
            },{
                path:'',
                component:Index
            }
        ]
    },{
        path: '/my-profile/:id',
        component: UserSideMenu,
        meta: { 
            auth: true
        },
        children: [
            {
                path: '/my-profile/dashboard',
                component: DashboardProfile,
                meta: { 
                    auth: true
                }
            },{
                path: '/my-profile/settings',
                component: Settings,
                meta: { 
                    auth: true
                }
            },{
                path: '',
                component:Index
            }
        ]
    },{
        path: '*',
        component:Index
    }
]
const router = new VueRouter({
  	hashbang: false,
	history: true,
	mode: 'history',
	linkActiveClass: 'active',
	base: 'kyubi_ecommerce',
    routes
})

window.Router       = router;
window.Vue          = Vue;
Vue.http.options.root = '/kyubi_ecommerce/api';
Vue.http.interceptors.push((request, next) => {
    var qwerty  = Vue.cookie.get('qwerty');
    if(qwerty){
        Vue.http.headers.common['Authorization'] = 'Bearer '+qwerty;
    }
    next((response) => {
        var res     = response.body;
        if(res.user != undefined){
            if(res.user.status == 'user_not_found' || res.user.status == 'token_expired' || res.user.status == 'token_invalid' || res.user.status == 'token_absent'){
              router.push('/');
            }
        }
    });
});

router.beforeEach((to, from, next) => {
    console.log(window);
    var qwerty  = Vue.cookie.get('qwerty');
    if (to.matched.some(record => record.meta.auth)) {
        if(!qwerty){
            next({
                path: '/',
            })    
        }else{
            next();
        }
    }else{
        next();
    }
});

const app = new Vue({
    router,
    store,
    components:{
        'header-menu': Header,
        'footer-menu': Footer
    }
}).$mount('#app');