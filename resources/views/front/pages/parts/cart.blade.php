<div class="table-responsive">
    <table class="cart-table">
        <tr>
            <th class="column-1">Product Name</th>
            <th class="column-2">Unit Price</th>
            <th class="column-3">Qty</th>
            <th class="column-4">Subtotal</th>
            <th class="column-5"></th>
        </tr>
        @foreach($cart as $c)
            <tr>
                <td>
                    <div class="traditional-cart-entry">
                        <a href="#" class="image"><img src="{{asset('components/front/images')}}/product/{{$c['data']['id']}}/{{$c['data']['images']}}" alt="" width="50" /></a>
                        <div class="content">
                            <div class="cell-view">
                                <a href="{{url('product')}}/{{$c['data']['name_alias']}}" class="title">{{$c['data']['name']}}</a>
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
                            </div>
                        </div>
                    </div>
                </td>
                <td>{{number_format($c['data']['price']),0,',','.'}}</td>
                <td>
                    <div class="quantity-selector detail-info-entry">
                        <div class="entry number-minus">&nbsp;</div>
                        <div class="entry number">10</div>
                        <div class="entry number-plus">&nbsp;</div>
                    </div>
                </td>
                <td><div class="subtotal">{{number_format($c['data']['sub_total']),0,',','.'}}</div></td>
                <td><a class="remove-button"><i class="fa fa-times"></i></a></td>
            </tr>
        @endforeach
    </table>
</div>
<div class="cart-submit-buttons-box">
    <a class="button style-15">Continue Shopping</a>
    <a class="button style-15">Update Bag</a>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4 information-entry">
        <h3 class="cart-column-title">Discount Codes <span class="inline-label red">Promotion</span></h3>
        <form>
            <label>Enter your coupon code if you have one.</label>
            <input type="text" value="" placeholder="" class="simple-field size-1">
            <div class="button style-16" style="margin-top: 10px;">Apply Coupon<input type="submit"/></div>
        </form>
    </div>
    <div class="col-md-4 information-entry">
        <div class="cart-summary-box">
            <div class="sub-total">Subtotal: IDR {{number_format($checkout_data['sub_total'])}}</div>
            <a class="button style-10" href="{{url('checkout')}}">Proceed To Checkout</a>
        </div>
    </div>
</div>