<template>
    <div class="header-wrapper style-0">
        <header class="type-1">
            <div class="header-top">
                <div class="header-top-entry" v-if="globals.phone">
                    <div class="title"><i class="fa fa-phone"></i>Any question? Call Us <a :href="globals.phone"><b>{{globals.phone}}</b></a></div>
                </div>
                <div class="socials-box">
                    <a :href="sm.url" v-for="sm in social_media">
                        <i :class="sm.image"></i>
                    </a>
                </div>
                <div class="menu-button responsive-menu-toggle-class"><i class="fa fa-reorder"></i></div>
                <div class="clear"></div>
            </div>

            <div class="header-middle">
                <div class="logo-wrapper">
                    <router-link to="/"><img :src="globals.image_path+'/both/images/web/'+globals.web_logo" :alt="globals.web_name" class="img-logo" /></router-link>
                </div>

                <div class="middle-entry">
                    <div class="search-box">
                        <form>
                            <div class="search-button">
                                <i class="fa fa-search"></i>
                                <input type="submit" />
                            </div>
                            <div class="search-drop-down">
                                <div class="title"><span>All categories</span><i class="fa fa-angle-down"></i></div>
                                <div class="list">
                                    <div class="overflow">
                                        <div class="category-entry">All</div>
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
                </div>
                <div class="right-entries">
                    <a class="header-functionality-entry open-search-popup" href="#"><i class="fa fa-search"></i><span>Search</span></a>
                    <a class="header-functionality-entry" href="#"><i class="fa fa-copy"></i><span>Compare</span></a>
                    <a class="header-functionality-entry" href="#"><i class="fa fa-heart-o"></i><span>Wishlist</span></a>
                    <a class="header-functionality-entry open-cart-popup" href="#"><i class="fa fa-shopping-cart"></i><span>My Cart</span> <b>$255,99</b></a>
                </div>
            </div>

            <div class="close-header-layer"></div>
            <div class="navigation">
                <div class="navigation-header responsive-menu-toggle-class">
                    <div class="title">Navigation</div>
                    <div class="close-menu"></div>
                </div>
                <div class="nav-overflow">
                    <nav>
                        <ul>
                            <li class="full-width">
                                <router-link to="/" exact>Home</router-link>
                            </li>
                            <li class="full-width">
                                <router-link to="/contact">Contact</router-link>
                            </li>
                        </ul>

                        <ul>
                            <li><a href="#">Download Catalog</a></li>
                            <li class="fixed-header-visible">
                                <a class="fixed-header-square-button open-cart-popup"><i class="fa fa-shopping-cart"></i></a>
                                <a class="fixed-header-square-button open-search-popup"><i class="fa fa-search"></i></a>
                            </li>
                        </ul>
                        <div class="clear"></div>
                        <div class="fixed-header-visible additional-header-logo">
                            <router-link to="/"><img :src="globals.image_path+'/both/images/web/'+globals.web_logo" :alt="globals.web_name" class="img-logo" /></router-link>
                        </div>
                    </nav>
                    <div class="navigation-footer responsive-menu-toggle-class">
                        <div class="socials-box">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-google-plus"></i></a>
                            <a href="#"><i class="fa fa-youtube"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            <div class="clear"></div>
                        </div>
                        <div class="navigation-copyright">Created by <a href="#">8theme</a>. All rights reserved</div>
                    </div>
                </div>
            </div>
        </header>
        <div class="clear"></div>
        <div class="cart-box popup">
            <div class="popup-container">
                <div class="cart-entry">
                    <a class="image"><img src="img/product-menu-1.jpg" alt="" /></a>
                    <div class="content">
                        <a class="title" href="#">Pullover Batwing Sleeve Zigzag</a>
                        <div class="quantity">Quantity: 4</div>
                        <div class="price">$990,00</div>
                    </div>
                    <div class="button-x"><i class="fa fa-close"></i></div>
                </div>
                <div class="cart-entry">
                    <a class="image"><img src="img/product-menu-1_.jpg" alt="" /></a>
                    <div class="content">
                        <a class="title" href="#">Pullover Batwing Sleeve Zigzag</a>
                        <div class="quantity">Quantity: 4</div>
                        <div class="price">$990,00</div>
                    </div>
                    <div class="button-x"><i class="fa fa-close"></i></div>
                </div>
                <div class="summary">
                    <div class="subtotal">Subtotal: $990,00</div>
                    <div class="grandtotal">Grand Total <span>$1029,79</span></div>
                </div>
                <div class="cart-buttons">
                    <div class="column">
                        <a class="button style-3">view cart</a>
                        <div class="clear"></div>
                    </div>
                    <div class="column">
                        <a class="button style-4">checkout</a>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div id="product-popup" class="overlay-popup">
            <div class="overflow">
                <div class="table-view">
                    <div class="cell-view">
                        <div class="close-layer"></div>
                        <div class="popup-container">

                            <div class="row">
                                <div class="col-sm-6 information-entry">
                                    <div class="product-preview-box">
                                        <div class="swiper-container product-preview-swiper" data-autoplay="0" data-loop="1" data-speed="500" data-center="0" data-slides-per-view="1">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <div class="product-zoom-image">
                                                        <img src="img/product-main-1.jpg" alt="" data-zoom="img/product-main-1-zoom.jpg" />
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="product-zoom-image">
                                                        <img src="img/product-main-2.jpg" alt="" data-zoom="img/product-main-2-zoom.jpg" />
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="product-zoom-image">
                                                        <img src="img/product-main-3.jpg" alt="" data-zoom="img/product-main-3-zoom.jpg" />
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="product-zoom-image">
                                                        <img src="img/product-main-4.jpg" alt="" data-zoom="img/product-main-4-zoom.jpg" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pagination"></div>
                                            <div class="product-zoom-container">
                                                <div class="move-box">
                                                    <img class="default-image" src="img/product-main-1.jpg" alt="" />
                                                    <img class="zoomed-image" src="img/product-main-1-zoom.jpg" alt="" />
                                                </div>
                                                <div class="zoom-area"></div>
                                            </div>
                                        </div>
                                        <div class="swiper-hidden-edges">
                                            <div class="swiper-container product-thumbnails-swiper" data-autoplay="0" data-loop="0" data-speed="500" data-center="0" data-slides-per-view="responsive" data-xs-slides="3" data-int-slides="3" data-sm-slides="3" data-md-slides="4" data-lg-slides="4" data-add-slides="4">
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide selected">
                                                        <div class="paddings-container">
                                                            <img src="img/product-main-1.jpg" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="swiper-slide">
                                                        <div class="paddings-container">
                                                            <img src="img/product-main-2.jpg" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="swiper-slide">
                                                        <div class="paddings-container">
                                                            <img src="img/product-main-3.jpg" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="swiper-slide">
                                                        <div class="paddings-container">
                                                            <img src="img/product-main-4.jpg" alt="" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pagination"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 information-entry">
                                    <div class="product-detail-box">
                                        <h1 class="product-title">T-shirt Basic Stampata</h1>
                                        <h3 class="product-subtitle">Loremous Clothing</h3>
                                        <div class="rating-box">
                                            <div class="star"><i class="fa fa-star"></i></div>
                                            <div class="star"><i class="fa fa-star"></i></div>
                                            <div class="star"><i class="fa fa-star"></i></div>
                                            <div class="star"><i class="fa fa-star-o"></i></div>
                                            <div class="star"><i class="fa fa-star-o"></i></div>
                                            <div class="rating-number">25 Reviews</div>
                                        </div>
                                        <div class="product-description detail-info-entry">Lorem ipsum dolor sit amet, consectetur adipiscing elit, eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur.</div>
                                        <div class="price detail-info-entry">
                                            <div class="prev">$90,00</div>
                                            <div class="current">$70,00</div>
                                        </div>
                                        <div class="size-selector detail-info-entry">
                                            <div class="detail-info-entry-title">Size</div>
                                            <div class="entry active">xs</div>
                                            <div class="entry">s</div>
                                            <div class="entry">m</div>
                                            <div class="entry">l</div>
                                            <div class="entry">xl</div>
                                            <div class="spacer"></div>
                                        </div>
                                        <div class="color-selector detail-info-entry">
                                            <div class="detail-info-entry-title">Color</div>
                                            <div class="entry active" style="background-color: #d23118;">&nbsp;</div>
                                            <div class="entry" style="background-color: #2a84c9;">&nbsp;</div>
                                            <div class="entry" style="background-color: #000;">&nbsp;</div>
                                            <div class="entry" style="background-color: #d1d1d1;">&nbsp;</div>
                                            <div class="spacer"></div>
                                        </div>
                                        <div class="quantity-selector detail-info-entry">
                                            <div class="detail-info-entry-title">Quantity</div>
                                            <div class="entry number-minus">&nbsp;</div>
                                            <div class="entry number">10</div>
                                            <div class="entry number-plus">&nbsp;</div>
                                        </div>
                                        <div class="detail-info-entry">
                                            <a class="button style-10">Add to cart</a>
                                            <a class="button style-11"><i class="fa fa-heart"></i> Add to Wishlist</a>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="tags-selector detail-info-entry">
                                            <div class="detail-info-entry-title">Tags:</div>
                                            <a href="#">bootstrap</a>/
                                            <a href="#">collections</a>/
                                            <a href="#">color/</a>
                                            <a href="#">responsive</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="close-popup"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
              globals       : 'global',
		      social_media  : 'social_media',
		      auth          : 'auth',
		    })
		},
    	created(){
    		this.$http.get('booting').then((response) => {
	     		this.$store.dispatch('booting',response.data);
	     		this.$store.dispatch('auth',response.data);
	      	});
    	},
	    methods:{
	    	
	    }
   	}
</script>

<style>
	
</style>
