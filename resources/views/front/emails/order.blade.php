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
                    <b>Payment Method</b>: {{$orderhd['paymentmethod']['nama_payment']}}<br/>
                    <b>Shipping Via</b>: {{$orderhd['orderaddress']['shippingvendor']['nama_shipping']}}
                    @if(!empty($orderhd['tracking_code']))
                        <br/><b>Tracking Code</b>: {{$orderhd['tracking_code']}}
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
                    <td><b>unit Price<b></td>
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
                            <br/>Color: {{$dt['color']}} 
                        @endif
                        @if($dt['size'] != '0')
                            <br/>Size: {{$dt['size']}}
                        @endif
                    </td>
                    <td>{{$dt['qty']}}</td>
                    <td>{{$dt['price']}}</td>
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
                    <tr>
                        <td colspan="4">Sub Total</td>
                        <td>{{$orderhd['sub_total']}}</td>
                    </tr>
                    <tr>
                        <td colspan="4">Shipping Price</td>
                        <td>{{$orderhd['total_ongkir']}}</td>
                    </tr>
                    @if(count($orderhd['ordervoucher']) > 0)
                        <tr>
                          <td><b>Discount</b></td>
                          <td>
                            ({{$orderhd['total_voucher']}})
                            @if($orderhd['ordervoucher']['voucher_fgfreeongkir'] == 'y')
                               +Free Ongkir
                            @endif
                          </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="4">Total</td>
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