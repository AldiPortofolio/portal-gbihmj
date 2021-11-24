@if($cart != 'no_data')
	<div class="cart-product-data">
		@foreach($cart as $q => $c)
			<div class="cart-entry">
			    <a class="image"><img src="{{asset('components/front/images')}}/product/{{$c['data']['id']}}/{{$c['data']['images']}}" alt="" width="50" /></a>
			    <div class="content">
			        <a class="title" href="{{url('product')}}/{{$c['data']['name_alias']}}">{{$c['data']['name']}}</a>
			       	<div class="cart-attribute">
				       	<div class="size-selector detail-info-entry">
				            <div class="detail-info-entry-title"></div>
					       	@foreach($c['data']['attribute'] as $da)
					       		@if($da['type'] == 'select')
									<div class="entry product-attr">{{$da['value']}}</div>
					       		@else
									<div class="entry product-attr" style="background-color: {{$da['value']}};">&nbsp;</div>
					       		@endif
					       	@endforeach
				            <div class="spacer"></div>
				        </div>
				    </div>
			        <div class="quantity">Quantity: {{$c['qty']}}</div>
			        <div class="price">IDR {{number_format($c['data']['price']),0,',','.'}}</div>
			    </div>
			    <div class="button-x remove-mini-cart" data-id="{{$q}}"><i class="fa fa-close"></i></div>
			</div>
		@endforeach
	</div>
	<div class="summary">
	    <div class="grandtotal">Sub Total <span>{{number_format($checkout_data['sub_total'])}}</span></div>
	</div>
	<div class="cart-buttons">
	    <div class="column">
	        <a class="button style-3" href="{{url('cart')}}">view cart</a>
	        <div class="clear"></div>
	    </div>
	    <div class="column">
	        <a class="button style-4" href="{{url('checkout')}}">checkout</a>
	        <div class="clear"></div>
	    </div>
	    <div class="clear"></div>
	</div>
@else
	Cart is empty
@endif