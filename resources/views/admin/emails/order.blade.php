@extends('emails.master')
@section('content')
<tr height="60px" align="center">
    <td colspan="8" style="background:#000000;color:#ffffff">
        {{$orderstatus['title']}}
    </td>
</tr>
<tr>
    <td colspan="8" style="padding-left: 40px;padding-right:40px;font-size:10pt;color:#000000;">
        <br><br>
        @if($email_status == 'cust')
            {!!$orderstatus['isi_email_cust']!!}
        @else
            {!!$orderstatus['isi_email_admin']!!}
        @endif
        <hr/>
        <table width="50%" cellspacing="0" cellpadding="0" align="left">
            <tr>
                <td>
                    <b>No. Order</b>: {{$orderhd['order_id']}}<br/>
                    <b>Order Date</b>: {{date_format(date_create($orderhd['created_at']),'d-m-y H:i:s')}}
                </td>
            </tr>
        </table>
        <table width="50%" cellspacing="0" cellpadding="0" align="right">
            <tr align="right">
                <td>
                    <b>Status Order</b>: {{$orderhd['orderstatus']['nama_order_status']}}
                </td>
            </tr>
        </table>
        <div style="clear:both"></div>
        <hr/>
        <table width="50%" cellspacing="0" cellpadding="0" align="left">
            <tr>
                <td>From: <hr/></td>
            </tr>
            <tr>
                <td><b>{{$orderbilling['first_name']}} {{$orderbilling['last_name']}}</b></td>
            </tr>
            <tr>
                <td>{{$orderbilling['phone']}}</td>
            </tr>
            <tr>
                <td>{{$orderbilling['address']}}</td>
            </tr>
            <tr>
                <td>{{$orderbilling['province']}}, {{$orderbilling['city']}} - {{$orderbilling['district']}}</td>
            </tr>
            <tr>
                <td>{{$orderbilling['country']}}</td>
            </tr>
            <tr>
                <td>{{$orderbilling['postal_code']}}</td>
            </tr>
        </table>
        <table width="50%" cellspacing="0" cellpadding="0" align="right">
            <tr>
                <td>To: <hr/></td>
            </tr>
            <tr>
                <td><b>{{$orderaddress['first_name']}} {{$orderaddress['last_name']}}</b></td>
            </tr>
            <tr>
                <td>{{$orderaddress['phone']}}</td>
            </tr>
            <tr>
                <td>{{$orderaddress['address']}}</td>
            </tr>
            <tr>
                <td>{{$orderaddress['province']}}, {{$orderaddress['city']}} - {{$orderaddress['district']}}</td>
            </tr>
            <tr>
                <td>{{$orderaddress['country']}}</td>
            </tr>
            <tr>
                <td>{{$orderaddress['postal_code']}}</td>
            </tr>
        </table>
        <div style="clear:both"></div>
        <hr/>
        <table width="50%" cellspacing="0" cellpadding="0" align="left">
            <tr>
                <td>
                    <b>Payment Method</b>: <br/>
                    {{$orderhd['paymentmethod']['nama_payment']}}<br/>
                    @if($orderhd['fkpaymentmethodid'] == 1)
                        {{json_decode($orderhd['bank_info'])->nama_bank}} - {{json_decode($orderhd['bank_info'])->branch}}<br/>
                        {{json_decode($orderhd['bank_info'])->account_name}} - {{json_decode($orderhd['bank_info'])->account_number}}
                    @endif
                </td>
            </tr>
        </table>
        <table width="50%" cellspacing="0" cellpadding="0" align="right">
            <tr>
                <td>
                    <b>Shipping Information</b>: <br/>
                    @if($orderaddress['fgcourier'] == 'n')
                        Shipping Vendor: {{$orderhd['orderaddress']['shippingvendor']['nama_shipping']}}<br/>
                    @else
                        Shipping Method: Courier<br/>
                        Shipping Vendor: {{json_decode($orderaddress['courier_data'])->nama_courier}}<br/>
                        Shipping Date  : {{date_format(date_create(json_decode($orderaddress['courier_data'])->delivery_date),'d-m-Y')}}<br/>
                        Shipping Time  : {{json_decode($orderaddress['courier_data'])->delivery_send_from}} - {{json_decode($orderaddress['courier_data'])->delivery_send_to}}<br/>
                    @endif
                    Shipping Price : {{$orderhd['total_ongkir']}}<br/>
                    @if(!empty($orderhd['tracking_code']))
                        <b>Tracking Code</b>: {{$orderhd['tracking_code']}}
                    @endif
                </td>
            </tr>
        </table>
        <div style="clear:both"></div>
        <hr/>
        <center>
            <table width="100%">
                <thead>
                  <tr>
                    <td><b>Code Product<b></td>
                    <td><b>Description<b></td>
                    <td><b>Qty<b></td>
                    <td><b>Unit Price<b></td>
                    <td><b>Points<b></td>
                    <td><b>Sub Total<b></td>
                  </tr>
                  <tr><td colspan="8"><hr/></td></tr>
                </thead>
                <tbody>
                  @foreach ($orderdt as $dt)
                  <tr>
                    <td>{{$dt['code_product']}}</td>
                    <td>
                        {{$dt['nama_product']}}
                        @if($dt['color'] != '0')
                            <br/>
                            <div class="color-attr" style="width: 15px;height: 15px;border: 1px solid black;background:{{$dt['color']}}"></div>
                        @endif
                        @if($dt['size'] != '0')
                            Size: {{$dt['size']}}
                        @endif
                    </td>
                    <td>{{$dt['qty']}}</td>
                    <td>{{$dt['price']}}</td>
                    <td>{{$dt['point']}}</td>
                    <td>{{$dt['total']}}</td>
                  </tr>
                  <tr><td colspan="8"><hr/></td></tr>
                  @endforeach
                    @if($bonusitem)
                      <tr>
                        <td><img src="{{asset('components/_front/images/checkout_config')}}/{{$bonusitem['thumbnail']}}" width="150px"></a></td>
                        <td>{{$bonusitem['description']}}</td>
                        <td colspan="3">Free (Bonus Item)</td>
                      </tr>
                      <tr><td colspan="8"><hr/></td></tr>
                    @endif
                    @if(!empty($orderhd['gift_wrap_message']) || $orderhd['total_gift_wrap'] != 0)
                        <tr>
                            <td>Gift Wrap Message</td>
                            <td colspan="4">{!!$orderhd['gift_wrap_message']!!}</td>
                        </tr>
                        <tr><td colspan="8"><hr/></td></tr>
                    @endif
                    <tr>
                        <td colspan="5">Sub Total</td>
                        <td>{{$orderhd['sub_total']}}</td>
                    </tr>
                    @if(!empty($orderhd['gift_wrap_message']) || $orderhd['total_gift_wrap'] != 0)
                    <tr>
                        <td colspan="5">Gift Wrap Fee</td>
                        <td>{{$orderhd['total_gift_wrap']}}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="5">Shipping Price</td>
                        <td>{{$orderhd['total_ongkir']}}</td>
                    </tr>
                    @if($orderhd['discount_type'] == 'voucher')
                        <tr>
                          <td colspan="5"><b>Discount (Voucher)</b></td>
                          <td>
                            {{$orderhd['total_voucher']}}
                            @if($orderhd['ordervoucher']['voucher_fgfreeongkir'] == 'y')
                               +Free Ongkir
                            @endif
                          </td>
                        </tr>
                    @elseif($orderhd['discount_type'] == 'point')
                        <tr>
                          <td colspan="5"><b>Discount (Point)</b></td>
                          <td>
                            ({{$orderhd['total_point']}})
                          </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="5">Total Point</td>
                        <td>{{$orderhd['total_point_order']}} Point</td>
                    </tr>
                    <tr>
                        <td colspan="5">Total</td>
                        <td>{{$orderhd['total']}}</td>
                    </tr>
                    <tr><td colspan="8"><hr/></td></tr>
                </tbody>
            </table>
        </center>
        <br/><br/>
        @include('emails.body-info')
    </td>
</tr>
@endsection