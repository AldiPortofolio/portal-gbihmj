<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
    
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: 24px;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
        text-align: left;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }

    .description{
        font-size: 14px
    }

    .description_2{
        text-align: right;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <!-- <img src="https://www.sparksuite.com/images/logo.png" style="width:100%; max-width:300px;"> -->
                                <img src="data:image/png;base64,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" style="width:200px; max-width:200px;">
                                <!-- <span style="vertical-align: text-top;">V-Tal</span> -->
                            </td>
                            
                            <td>
                                Order No.: {{$order->order_no}}<br>
                                Created: {{$order->created_at}}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="text-align: left;">
                                @php
                                    $country2 = '-';
                                    $subtotal_amount = 0;
                                    if(count($country) > 0){
                                        $country2 = '';
                                        foreach($country as $c){
                                            if($country2 != ''){
                                                $country2 .= ', ';
                                            }
                                            $country2 .= $c->country_name;
                                        }
                                    }

                                    $date_from = strtotime($order->go_from_indonesia);
                                    $date_to = strtotime($order->arrival_in_indonesia);
                                    $datediff = $date_to - $date_from;

                                    $day_between_date = round($datediff / (60 * 60 * 24));

                                @endphp
                                <b>Country</b>: {{$country2}}<br/>
                                <b>Start</b>: {{date_format(date_create($order->go_from_indonesia),'d-m-Y')}}<br/>
                                <b>End</b>: {{date_format(date_create($order->arrival_in_indonesia),'d-m-Y')}}<br/><br/>
                                <b>Rent Days</b>: {{$day_between_date}}<br/><br/>
                            </td>
                            
                            <td>
                                @php
                                $address            = '';

                                if($order->delivery_method_id == 2){
                                    ($order->delivery_address) ? $address .= $order->delivery_address.'<br>' : '';
                                    ($order->delivery_city) ? $address .= $order->delivery_city->name.'<br>' : '';
                                    ($order->delivery_province) ? $address .= $order->delivery_province->name.'<br>' : '';
                                    ($order->post_code) ? $address .= $order->post_code->name.'<br>' : '';
                                    
                                }elseif($order->delivery_method_id == 3){
                                    $address .= 'Pengambilan Sendiri<br>'.$order->delivery_area->description;
                                }
                            @endphp
                                <b>{!! $address !!}</b>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
           <!--  <tr class="heading">
                <td>
                    Payment Method
                </td>
                
                <td>
                    Check #
                </td>
            </tr> -->
            
            <!-- <tr class="details">
                <td>
                    Check
                </td>
                
                <td>
                    1000
                </td>
            </tr> -->
            
            <tr class="heading">
                <td>
                    Item
                </td>
                
                <td>
                    Price
                </td>
            </tr>
            @php
                $subtotal = 0;
            @endphp
            @foreach ($order->order_dt as $dt)
                 @php
                    $rent_price = 0;
                    $deposit_price = 0;
                    $modem_name = '';
                    $serial_no = '';
                    if(count($modem) > 0){
                        foreach($modem as $m){
                            if($m->id == $dt->modem_id){
                                $rent_price = $m->rent_price;
                                $deposit_price = $m->deposit_price;
                                $modem_name = $m->modem_name;
                                $serial_no = $m->serial_no;
                            }
                        }
                    }
                    $subtotal = ($day_between_date * $rent_price) + $deposit_price;
                    $subtotal_amount += $subtotal;
                @endphp
                <tr class="item">
                    <td>
                        {{$modem_name}}{{' x1'}}
                    </td>
                    
                    <td>
                        Rp. {{number_format($rent_price,0,',','.')}}
                    </td>
                </tr>
                <tr class="item">
                    <td>
                        Deposit Price
                    </td>
                    
                    <td>
                        Rp. {{number_format($deposit_price,0,',','.')}}
                    </td>
                </tr>
            @endforeach
           <!--  <tr class="item">
                <td>
                    Website design
                </td>
                
                <td>
                    $300.00
                </td>
            </tr> -->
            
            <!-- <tr class="item">
                <td>
                    Hosting (3 months)
                </td>
                
                <td>
                    $75.00
                </td>
            </tr> -->
            
           <!--  <tr class="item last">
                <td>
                    Domain name (1 year)
                </td>
                
                <td>
                    $10.00
                </td>
            </tr> -->
            
            <tr class="item">
                <td></td>
                
                <td>
                   Subtotal: Rp. {{number_format($subtotal_amount,0,',','.')}}
                </td>
            </tr>
            <tr class="item">
                <td>
                    <b>Total Shipping Amount: </b><br/>
                    <small>Total Weight : {{$order->total_weight_kg}} Kg</small><br/>
                    <small>Shipping Amount (IDR): {{number_format($order->shipping_price,0,',','.')}}</small>
                    @if($order->total_return_shipping_price != 0)
                         <br><small>Return shipping Amount (IDR): {{number_format($order->return_shipping_price,0,',','.')}}</small>
                    @endif
                </td>
                @php
                    $total_shipping_price = $order->total_shipping_price;
                    if($order->total_return_shipping_price != 0){
                        $total_shipping_price += $order->total_return_shipping_price;
                    }
                @endphp
                <td>Rp. {{number_format($total_shipping_price,0,',','.')}}</td>
            </tr>
            @if($order->voucher_amount > 0)
                <tr class="item">
                    <td><b>Voucher Amount:</b></td>
                    <td>
                      Rp. {{number_format($order->voucher_amount,0,',','.')}}
                    </td>
                </tr>
            @endif
            @if($order->pass_rent_day != 0)
                <tr class="item">
                    <td><b>Fine Cost:</b>
                        <small>Total Modem : {{count($order->order_dt)}}</small><br/>
                        <small>Passed Return Day : {{$order->pass_rent_day}}</small><br/>
                        <small>Fine Cost Per Day : {{$fine_cost_per_day}}</small><br/>
                    </td>
                    <td>
                      Rp. {{number_format($order->fine_cost,0,',','.')}}
                    </td>
                </tr>
            @endif
            <tr class="total">
                <td></td>
                <td>Total: Rp. {{number_format($order->total + $order->fine_cost,0,',','.')}}</td>
            </tr>
        </table>
        <div class="description">
            <br>
            Terbilang:
            <b>{{$terbilang}}</b>
            <br>
            Keterangan: {{$keterangan}}
            <br>
            Bank Account:<br>
            {{$bank_account}}
        </div>
        <div class="description_2">
            <div style="margin-right: 35px;"><b>JETFI INDONESIA</b></div>
            <div style="margin-right: 30px;margin-top: 10px;"><img src="{{asset('components/both/images/web')}}/{{$logo_signature}}" style="width:100%; max-width:150px;"></div>
            <div style="margin-right: 65px;margin-top: 10px;">FINANCE</div>
        </div>
    </div>
</body>
</html>