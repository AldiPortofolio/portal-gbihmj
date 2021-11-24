<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
    
    <style>
    /*.invoice-box {
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
    
    
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }*/
    </style>
</head>

<body>
    <div class="invoice-box" style="max-width:800px;margin-top:auto;margin-bottom:auto;margin-right:auto;margin-left:auto;padding-top:30px;padding-bottom:30px;padding-right:30px;padding-left:30px;border-width:1px;border-style:solid;border-color:#eee;box-shadow:0 0 10px rgba(0, 0, 0, .15);font-size:16px;line-height:24px;font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;color:#555;" >
        <table cellpadding="0" cellspacing="0" style="width:100%;line-height:inherit;text-align:left;" >
            <tr class="top">
                <td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;" >
                    <table style="width:100%;line-height:inherit;text-align:left;" >
                        <tr>
                            <td class="title" style="padding-top:5px;padding-right:5px;padding-left:5px;vertical-align:top;padding-bottom:20px;font-size:45px;line-height:45px;color:#333;" >
                                <!-- <img src="https://www.sparksuite.com/images/logo.png" style="width:100%;max-width:300px;" > -->
                                <img src="{{asset('components/back/images/admin')}}/{{$web_logo}}" style="width:100%;max-width:200px;" >
                                <!-- <span style="vertical-align:text-top;" >V-Tal</span> -->
                            </td>
                            
                            <td style="padding-top:5px;padding-right:5px;padding-left:5px;vertical-align:top;padding-bottom:20px;" >
                                Order No.: {{$order->order_no}}<br>
                                Created: {{$order->created_at}}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;" >
                    <table style="width:100%;line-height:inherit;text-align:left;" >
                        <tr>
                            <td style="padding-top:5px;padding-right:5px;padding-left:5px;vertical-align:top;padding-bottom:40px;" >
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
                            
                            <td style="padding-top:5px;padding-right:5px;padding-left:5px;vertical-align:top;padding-bottom:40px;" >
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
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;background-color:#eee;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#ddd;font-weight:bold;" >
                    Payment Method
                </td>
                
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;background-color:#eee;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#ddd;font-weight:bold;" >
                    Check #
                </td>
            </tr> -->
            
            <!-- <tr class="details">
                <td style="padding-top:5px;padding-right:5px;padding-left:5px;vertical-align:top;padding-bottom:20px;" >
                    Check
                </td>
                
                <td style="padding-top:5px;padding-right:5px;padding-left:5px;vertical-align:top;padding-bottom:20px;" >
                    1000
                </td>
            </tr> -->
            
            <tr class="heading">
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;background-color:#eee;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#ddd;font-weight:bold;" >
                    Item
                </td>
                
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;background-color:#eee;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#ddd;font-weight:bold;" >
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
                    <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                        {{$modem_name}}{{' x1'}}
                    </td>
                    
                    <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                        Rp. {{number_format($rent_price,0,',','.')}}
                    </td>
                </tr>
                <tr class="item">
                    <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                        Deposit Price
                    </td>
                    
                    <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                        Rp. {{number_format($deposit_price,0,',','.')}}
                    </td>
                </tr>
            @endforeach
           <!--  <tr class="item">
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                    Website design
                </td>
                
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                    $300.00
                </td>
            </tr> -->
            
            <!-- <tr class="item">
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                    Hosting (3 months)
                </td>
                
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                    $75.00
                </td>
            </tr> -->
            
           <!--  <tr class="item last">
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                    Domain name (1 year)
                </td>
                
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                    $10.00
                </td>
            </tr> -->
            
            <tr class="item">
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" ></td>
                
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                   Subtotal: Rp. {{$subtotal}}
                </td>
            </tr>
            <tr class="item">
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                    <b>Total Shipping Amount: </b><br/>
                    <small>Total Weight : {{$order->total_weight_kg}} Kg</small><br/>
                    <small>Shipping Amount (IDR): {{number_format($order->shipping_price,0,',','.')}}</small>
                </td>
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >Rp. {{number_format($order->total_shipping_price,0,',','.')}}</td>
            </tr>
            @if($order->voucher_amount > 0)
                <tr class="item">
                    <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" ><b>Voucher Amount:</b></td>
                    <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#eee;" >
                      Rp. {{number_format($order->voucher_amount,0,',','.')}}
                    </td>
                </tr>
            @endif
            <tr class="total">
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;" ></td>
                <td style="padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;vertical-align:top;" >Total: Rp. {{number_format($order->total,0,',','.')}}</td>
            </tr>
        </table>
    </div>
</body>
</html>