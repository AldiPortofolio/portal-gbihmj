<template>
	<footer class="type-1">
        <div class="footer-columns-entry">
            <div class="row">
                <div class="col-md-3">
                    <router-link to="/"><img :src="globals.image_path+'/both/images/web/'+globals.web_logo" :alt="globals.web_name" class="footer-logo" /></router-link>
                    <div class="footer-description" v-if="globals.web_description">{{globals.web_description}}</div>
                    <div class="footer-address" v-if="globals.address">{{globals.address}}<br/>
                        Phone: {{globals.phone}}<br/>
                        Email: <a :href="globals.email">{{globals.web_email}}</a><br/>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <h3 class="column-title">Helpful Links</h3>
                    <ul class="column">
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Order History</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Custom Service</a></li>
                        <li><a href="#">Terms &amp; Condition</a></li>
                        <li><a href="#">Order History</a></li>
                        <li><a href="#">Returns</a></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <h3 class="column-title">Popular Products</h3>
                    <ul class="column">
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Order History</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Custom Service</a></li>
                        <li><a href="#">Terms &amp; Condition</a></li>
                        <li><a href="#">Order History</a></li>
                        <li><a href="#">Returns</a></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <h3 class="column-title">Recent Posts</h3>
                    <ul class="column">
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Order History</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Custom Service</a></li>
                        <li><a href="#">Terms &amp; Condition</a></li>
                        <li><a href="#">Order History</a></li>
                        <li><a href="#">Returns</a></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="clearfix visible-sm-block"></div>
                <div class="col-md-3">
                    <h3 class="column-title">Company working hours</h3>
                    <div class="footer-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</div>
                    <div class="footer-description">
                        <b>Monday-Friday:</b> 8.30 a.m. - 5.30 p.m.<br/>
                        <b>Saturday:</b> 9.00 a.m. - 2.00 p.m.<br/>
                        <b>Sunday:</b> Closed
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-navigation">
            <div class="cell-view">
                <div class="footer-links">
                    <a href="#">Site Map</a>
                    <a href="#">Search</a>
                    <a href="#">Terms</a>
                    <a href="#">Advanced Search</a>
                    <a href="#">Orders and Returns</a>
                    <a href="#">Contact Us</a>
                </div>
                <div class="copyright">Created with by <a href="http://bookme.co.id">Bookme</a>. All right reserved</div>
            </div>
            <div class="cell-view">
                <div class="payment-methods">
                    <img v-for="pm in payment_methods" class="footer-payment" :src="globals.image_path+'/front/images/payment_method/'+pm.image" :alt="pm.payment_method_name" />
                </div>
            </div>
        </div>
        <div class="search-box popup">
	        <form>
	            <div class="search-button">
	                <i class="fa fa-search"></i>
	                <input type="submit" />
	            </div>
	            <div class="search-drop-down">
	                <div class="title"><span>All categories</span><i class="fa fa-angle-down"></i></div>
	                <div class="list">
	                    <div class="overflow">
	                        <div class="category-entry">Category 1</div>
	                        <div class="category-entry">Category 2</div>
	                        <div class="category-entry">Category 2</div>
	                        <div class="category-entry">Category 4</div>
	                        <div class="category-entry">Category 5</div>
	                        <div class="category-entry">Lorem</div>
	                        <div class="category-entry">Ipsum</div>
	                        <div class="category-entry">Dollor</div>
	                        <div class="category-entry">Sit Amet</div>
	                    </div>
	                </div>
	            </div>
	            <div class="search-field">
	                <input type="text" value="" placeholder="Search for product" />
	            </div>
	        </form>
	    </div>
    </footer>
</template>

<script>
	import { mapGetters,mapActions } from 'vuex'
	export default {
		data(){
			return{
			}
		},
		computed: {
		    ...mapGetters({
		      globals			: 'global',
		      payment_methods	: 'payment_methods',
		    })
		}
   	}
</script>

<style>
	
</style>
