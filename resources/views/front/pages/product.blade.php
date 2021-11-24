@extends($view_path.'.layouts.master')
@section('content')
<div class="content-push">
	<div class="breadcrumb-box">
	    <a href="{{url('/')}}">Home</a>
	    @if($product->product_category->category)
	    	<a href="{{url($product->product_category->category->category_name_alias)}}">{{$product->product_category->category->category_name}}</a>
		    <a href="{{url($product->product_category->category->category_name_alias)}}/{{$product->product_category->category_name_alias}}">{{$product->product_category->category_name}}</a>
	    @else
		    <a href="{{url($product->product_category->category_name_alias)}}">{{$product->product_category->category_name}}</a>
	    @endif
	    <a href="{{url('product')}}/{{$product->name_alias}}">{{$product->name}}</a>                         
	</div>

	<div class="information-blocks">
	    <div class="row">
	        <div class="col-sm-6 information-entry">
	            <div class="product-preview-box">
	                <div class="swiper-container product-preview-swiper" data-autoplay="0" data-loop="1" data-speed="500" data-center="0" data-slides-per-view="1">
	                    <div class="swiper-wrapper">
	                    	@foreach($product->product_data_images as $pdi)
		                        <div class="swiper-slide">
		                            <div class="product-zoom-image">
		                            	<img src="{{asset('components/front/images/product')}}/{{$product->id}}/{{$pdi->images_name}}" />
		                            </div>
		                        </div>
		                    @endforeach
	                    </div>
	                    <div class="pagination"></div>
	                </div>
	                <div class="swiper-hidden-edges">
	                    <div class="swiper-container product-thumbnails-swiper" data-autoplay="0" data-loop="0" data-speed="500" data-center="0" data-slides-per-view="responsive" data-xs-slides="3" data-int-slides="3" data-sm-slides="3" data-md-slides="4" data-lg-slides="4" data-add-slides="4">
	                        <div class="swiper-wrapper">
	                        	@foreach($product->product_data_images as $pdi)
			                        <div class="swiper-slide {{$loop->first ? 'selected' : ''}}">
			                            <div class="paddings-container">
			                            	<img src="{{asset('components/front/images/product')}}/{{$product->id}}/{{$pdi->images_name}}" />
			                            </div>
			                        </div>
			                    @endforeach
	                        </div>
	                        <div class="pagination"></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="col-sm-6 information-entry">
	            <div class="product-detail-box">
	                <h1 class="product-title">{{$product->name}}</h1>
	                <h3 class="product-subtitle">{{$product->product_category->category_name}}</h3>
	                <div class="product-description detail-info-entry">{{$product->description}}</div>
	                <div class="price detail-info-entry">
	                    <div class="current">IDR {{number_format($product->price),0,',','.'}}</div>
	                </div>
	                @foreach($product_attribute as $q => $pa)
	                	@if($pa['type'] == 'color')
	                		<div class="color-selector detail-info-entry">
			                    <div class="detail-info-entry-title">{{$q}}</div>
			                    @foreach($pa['data'] as $pad)
									<div class="entry product-attr {{$loop->first ? 'active' : ''}}" data-id="{{$pad['id']}}" style="background-color: {{$pad['value']}};">&nbsp;</div>
			                    @endforeach
			                    <div class="spacer"></div>
			                </div>
	                	@else
	                		<div class="size-selector detail-info-entry">
			                    <div class="detail-info-entry-title">{{$q}}</div>
			                    @foreach($pa['data'] as $pad)
									<div class="entry product-attr {{$loop->first ? 'active' : ''}}" data-id="{{$pad['id']}}">{{$pad['value']}}</div>
			                    @endforeach
			                    <div class="spacer"></div>
			                </div>
	                	@endif
	                @endforeach
	               
	                <div class="quantity-selector detail-info-entry">
	                    <div class="detail-info-entry-title">Quantity</div>
	                    <div class="entry number-minus">&nbsp;</div>
	                    <div class="entry number product-qty">1</div>
	                    <div class="entry number-plus">&nbsp;</div>
	                </div>
	                <div class="detail-info-entry">
	                    <button class="button style-10 add-cart" data-id="{{$product->id}}">Add to cart</button>
	                    <button class="button style-11 add-wishlist" data-id="{{$product->id}}"><i class="fa fa-heart"></i> Add to Wishlist</button>
	                    <div class="clear"></div>
	                </div>
	                <div class="share-box detail-info-entry">
	                    <div class="title">Share in social media</div>
	                    <div class="socials-box">
	                        <a href="#"><i class="fa fa-facebook"></i></a>
	                        <a href="#"><i class="fa fa-twitter"></i></a>
	                    </div>
	                    <div class="clear"></div>
	                </div>
	            </div>
	            <div class="accordeon">
	                <div class="accordeon-title active">Product description</div>
	                <div class="accordeon-entry" style="display: block;">
	                    <div class="article-container style-1">
	                        {!!$product->content!!}
	                    </div>
	                </div>
	                <div class="accordeon-title">shipping &amp; Returns</div>
	                <div class="accordeon-entry">
	                    <div class="article-container style-1">
	                        {!!$shipping_returns_product!!}
	                    </div>
	                </div>
	                <div class="accordeon-title">Terms & Condition</div>
	                <div class="accordeon-entry">
	                    <div class="article-container style-1">
	                        {!!$terms_condition_product!!}
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="information-blocks">
	    <div class="tabs-container">
	        <div class="swiper-tabs tabs-switch">
	            <div class="title">Products</div>
	            <div class="list">
	                <a class="block-title tab-switcher active">Recomended Product</a>
	                <div class="clear"></div>
	            </div>
	        </div>
	        <div>
	            <div class="tabs-entry">
	                <div class="products-swiper">
	                    <div class="swiper-container" data-autoplay="0" data-loop="0" data-speed="500" data-center="0" data-slides-per-view="responsive" data-xs-slides="2" data-int-slides="2" data-sm-slides="3" data-md-slides="4" data-lg-slides="5" data-add-slides="5">
	                        <div class="swiper-wrapper">
	                            <div class="swiper-slide"> 
	                                <div class="paddings-container">
	                                    <div class="product-slide-entry shift-image">
	                                        <div class="product-image">
	                                            <img src="img/product-minimal-5.jpg" alt="" />
	                                            <img src="img/product-minimal-11.jpg" alt="" />
	                                            <a class="top-line-a right"><i class="fa fa-expand"></i> <span>Quick View</span></a>
	                                            <div class="bottom-line">
	                                                <div class="right-align">
	                                                    <a class="bottom-line-a square"><i class="fa fa-retweet"></i></a>
	                                                    <a class="bottom-line-a square"><i class="fa fa-heart"></i></a>
	                                                </div>
	                                                <div class="left-align">
	                                                    <a class="bottom-line-a"><i class="fa fa-shopping-cart"></i> Add to cart</a>
	                                                </div>
	                                            </div>
	                                        </div>
	                                        <a class="tag" href="#">Men clothing</a>
	                                        <a class="title" href="#">Blue Pullover Batwing Sleeve Zigzag</a>
	                                        <div class="rating-box">
	                                            <div class="star"><i class="fa fa-star"></i></div>
	                                            <div class="star"><i class="fa fa-star"></i></div>
	                                            <div class="star"><i class="fa fa-star"></i></div>
	                                            <div class="star"><i class="fa fa-star"></i></div>
	                                            <div class="star"><i class="fa fa-star"></i></div>
	                                        </div>
	                                        <div class="price">
	                                            <div class="prev">$199,99</div>
	                                            <div class="current">$119,99</div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="pagination"></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<hr/>
	<div class="clear"></div>
</div>
@endsection