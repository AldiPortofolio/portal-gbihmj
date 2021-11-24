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
        line-height: inherit;
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
                                <img src="{{asset('components/back/images/admin')}}/{{$web_logo}}" style="width:100%; max-width:200px;">
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
                            <td>
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
                                    $address .= 'Pengambilan Sendiri';
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
                   Subtotal: Rp. {{$subtotal}}
                </td>
            </tr>
            <tr class="item">
                <td>
                    <b>Total Shipping Amount: </b><br/>
                    <small>Total Weight : {{$order->total_weight_kg}} Kg</small><br/>
                    <small>Shipping Amount (IDR): {{number_format($order->shipping_price,0,',','.')}}</small>
                </td>
                <td>Rp. {{number_format($order->total_shipping_price,0,',','.')}}</td>
            </tr>
            @if($order->voucher_amount > 0)
                <tr class="item">
                    <td><b>Voucher Amount:</b></td>
                    <td>
                      Rp. {{number_format($order->voucher_amount,0,',','.')}}
                    </td>
                </tr>
            @endif
            <tr class="total">
                <td></td>
                <td>Total: Rp. {{number_format($order->total,0,',','.')}}</td>
            </tr>
        </table>
    </div>
</body>
</html>