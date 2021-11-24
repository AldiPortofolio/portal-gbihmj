<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<head>
		<title>Invoices</title>
		<style>
			hr{
				color:gray;
			}
		</style>
	</head>
	<body>
		<table cellspacing="0" cellpadding="0" align="left">
			<tr>
				<td><img src="{{asset('components/back/images/admin')}}/{{$web_logo}}" width="200"></td>
			</tr>
		</table>
		<br/><br/><br/><br/>
		<div><hr/></div>
		<table width="70%" cellspacing="0" cellpadding="0" align="left">
			<tr>
				<td>
					<b>Order Number</b>: #{{$order->order_no}} <br/>
					<b>Order Status</b>: {{$order->order_status->order_status_name}}<br/>
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
					<b>Go from Indonesia</b>: {{date_format(date_create($order->go_from_indonesia),'d-m-Y')}}<br/>
					<b>Arrival in Indonesia</b>: {{date_format(date_create($order->arrival_in_indonesia),'d-m-Y')}}<br/><br/>
				</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="0" align="right">
			<tr>
				<td>Order Date: {{date_format(date_create($order->created_at),'d-m-Y H:i:s')}}</td>
			</tr>
		</table>
		<br/><br/><br/>
		<table width="50%" cellspacing="0" cellpadding="0" align="left">
			<tr>
				<td>Delivery: <hr/></td>
			</tr>
			<tr>
				<td><b>{{$order->delivery_method->delivery_method_name}}</b></td>
			</tr>
			@php
				$address 			= '';

				if($order->delivery_method_id == 2){
					($order->delivery_address) ? $address .= $order->delivery_address.'<br>' : '';
					($order->delivery_city) ? $address .= $order->delivery_city->name.'<br>' : '';
					($order->delivery_province) ? $address .= $order->delivery_province->name.'<br>' : '';
					($order->post_code) ? $address .= $order->post_code->name.'<br>' : '';
					
				}elseif($order->delivery_method_id == 3){
					$address .= $order->delivery_area->description;
				}
			@endphp
			<tr>
				<td><b>{!! $address !!}</b></td>
			</tr>
		</table>
		<table width="50%" cellspacing="0" cellpadding="0" align="right">
			<tr>
				<td>Return: <hr/></td>
			</tr>
			<tr>
				<td><b>{{$order->return_method->delivery_method_name}}</b></td>
			</tr>
			@php
				$address 			= '';

				if($order->return_method_id == 2){
					($order->return_address) ? $address .= $order->return_address.'<br>' : '';
					($order->return_city) ? $address .= $order->return_city->name.'<br>' : '';
					($order->return_province) ? $address .= $order->return_province->name.'<br>' : '';
					($order->post_code) ? $address .= $order->post_code->name.'<br>' : '';
					
				}elseif($order->return_method_id == 3){
					$address .= $order->return_area->delivery_area_name;
				}
			@endphp
			<tr>
				<td><b>{!! $address !!}</b></td>
			</tr>
		</table>

		<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><hr/>
		<table width="100%" cellspacing="0" cellpadding="0">
			<thead>
	          <tr>
	            <td><b>Modem Name<b></td>
	            <td><b>Serial No<b></td>
	            <td><b>Qty<b></td>
	            <td><b>Day<b></td>
	            <td><b>Rent Price / Day (IDR)<b></td>
	            <td><b>Deposit Price (IDR)<b></td>
	            <td><b>Sub Total (IDR)<b></td>
	          </tr>
	          <tr>
	            <td colspan="8"><hr/></td>
	          </tr>
	        </thead>
	        <tbody>
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
		          	<tr>
		                <td>
		                    <b>{{$modem_name}}</b>
		                </td>
		                <td>
		                    <b>{{$serial_no}}</b>
		                </td>
		                <td> 1 </td>
		               	<td> {{$day_between_date}} </td>
		                <td> {{number_format($rent_price,0,',','.')}} </td>
		                <td> {{number_format($deposit_price,0,',','.')}} </td>
		                <td> {{number_format($subtotal,0,',','.')}} </td>
	            	</tr>
            	@endforeach
	          <!-- <tr><td colspan="5"></td><td><b>Sub Total</b></td><td>{{number_format($subtotal_amount,0,',','.')}}</td></tr> -->
	          
	        </tbody>
		</table>
		<table width="50%" cellspacing="0" cellpadding="0" align="right">
			<tr>
				<td>Summary<hr/></td>
			</tr>
			<tr>
				<td><b>Sub Total: </b></td>
				<td>{{number_format($order->subtotal,0,',','.')}}</td>
			</tr>
			<tr>
				<td>
					<b>Total Shipping Amount: </b><br/>
					<small>Total Weight : {{$order->total_weight_kg}} Kg</small><br/>
                    <small>Shipping Amount (IDR): {{number_format($order->shipping_price,0,',','.')}}</small>
				</td>
				<td> {{number_format($order->total_shipping_price,0,',','.')}}</td>
			</tr>
            @if($order->voucher_amount > 0)
              	<tr>
	                <td><b>Voucher Amount:</b></td>
	                <td>
	                  (IDR {{number_format($order->voucher_amount,0,',','.')}}) 
	                </td>
              	</tr>
            @endif
			<tr>
				<td><b>Total Amount: </b></td>
				<td>IDR {{number_format($order->total,0,',','.')}}</td>
			</tr>
		</table>

		<br/>
	</body>
</html>