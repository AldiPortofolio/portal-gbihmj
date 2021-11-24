<?php namespace digipos\Http\Controllers\Admin;

use DB;
use Session;
use Hash;
use Carbon\Carbon;

// use digipos\models\Outlet;
// use digipos\models\Product_adjustment;
use digipos\models\Product;
use digipos\models\Order_hd;
use digipos\models\Order_dt;
use digipos\models\Order_status;
// use digipos\models\Subcategory_product_id;
use digipos\models\User;
use digipos\models\Promo;
use digipos\models\Modem;
use digipos\models\Country;
use digipos\models\Category_modem;


use digipos\Libraries\Alert;
use digipos\Libraries\Timeelapsed;
use Illuminate\Http\Request;

class OmzetReportController extends KyubiController {

	public function __construct(){
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->title 			= "Sales Report";
		$this->root_url			= "report/sales-report";
		$this->root_link 		= "sales-report";
		$this->model 			= new Order_hd;
		$this->orderdt 			= new Order_dt;
		$this->user 			= new User;
		$this->promo 			= new Promo;
		$this->order_status 	= new Order_status;
		$this->data['root_url']	= $this->root_url;

		$this->payment_type = [
			'1' => 'Cash',
			'2' => 'Transfer',
			'3' => 'TOP',
		];
	}

	/**source.
	 *
	 * @return Response
	 * Display a listing of the response
	 */
	public function index(){
		$this->data['title'] 			= $this->title;
		// $this->data['data1'] 			= $this->model->get();
		$this->data['payment_type'] 	= $this->payment_type;
		// $this->data['order_status'] 	= $this->order_status->whereIn('id', [5,6])->get();
		$this->data['order_status'] 	= $this->order_status->get();
		$this->data['agent_name'] 		= User::where('user_access_id', 3)->get();
		$this->data['promo_code'] 		= Promo::get();
		// $outlet_access 					= $this->myStore2();
		// if($outlet_access != 'all'){
		// 	$office 					= Outlet::where('id', $outlet_access)->get();
		// }else{
		// 	$office 					= Outlet::get();
		// }
		// $this->data['office'] 			= $office;
		// $this->data['outlet_access'] 	= $outlet_access;
		// dd($this->data['office']);
		return $this->render_view('pages.reports.omzet');
	}

	public function ext(request $request, $action){
		return $this->$action($request);
	}

	public function filter($request){
		// $outlet_access = $this->myStore2();
		$query = $this->model->leftJoin('order_status', 'order_status.id', 'orderhd.order_status_id')->leftJoin('promo', 'promo.id', 'orderhd.promo_id')->leftJoin('user', 'user.id', 'promo.user_id');
		
		// return $request->input('outlet');
		// if($request->input('outlet') != ''){
		// 	if(in_array('0', $request->input('outlet'))){
				
		// 	}else{
		// 		$query->whereIn('user.outlet_id', $request->input('outlet'));
		// 	}
		// }else{
		// 	return '<div style="text-align:center;color:red;">Please select Office !</div>';
		// }
		
		$query2 = Order_dt::Join('modem', 'modem.id', 'orderdt.modem_id')->leftJoin('category_modem','category_modem.id','modem.category_modem_id')->orderBy('orderhd_id')->select('orderdt.*','modem.modem_name','category_modem.category_modem_name', 'modem.category_modem_id');
		// $product 	= $this->product->where('status', 'y');
		// $outlet 	= $this->outlet->where('status', 'y')->get();

		if($request->input('search_date_from') != ""){
			// dd($request->input('search_date_from'));
			$search_date_from = date('d-m-Y', strtotime($request->input('search_date_from') . ' -1 day'));
			$search_from = $this->displayToSql($search_date_from);
			// dd($search_from);
			$query->whereDate('orderhd.created_at', '>=', $search_from);
		}

		if($request->input('search_date_to') != ""){
			$search_to = $this->displayToSql($request->input('search_date_to'));
			$query->whereDate('orderhd.created_at', '<=', $search_to);
		}

		$count_product = 0;
		// return $request->input('payment_type');
		if($request->input('order_status') != ""){
			if(in_array('0', $request->input('order_status'))){
				// $product2= $product->get();
				// $count_product = count($this->product->get()) * 3;
			}else{
				// $count_product = count($request->input('payment_type')) * 3;
				$query->whereIn('orderhd.order_status_id', $request->input('order_status'));
				// $product2 = $product->whereIn('product.id', $request->input('payment_type'))->get();
			}
		}else{
			return '<div style="text-align:center;color:red;">Please select Order Status !</div>';
		}

		if($request->input('promo_id') != ""){
			if(in_array('0', $request->input('promo_id'))){
				// $product2= $product->get();
				// $count_product = count($this->product->get()) * 3;
			}else{
				// $count_product = count($request->input('payment_type')) * 3;
				$query->whereIn('orderhd.promo_id', $request->input('promo_id'));
				// $product2 = $product->whereIn('product.id', $request->input('payment_type'))->get();
			}
		}
		// return $request->input('user_id');
		if($request->input('user_id') != ""){
			if(in_array('0', $request->input('user_id'))){
				// $product2= $product->get();
				// $count_product = count($this->product->get()) * 3;
			}else{
				// $count_product = count($request->input('payment_type')) * 3;
				$query->whereIn('user.id', $request->input('user_id'));
				// $product2 = $product->whereIn('product.id', $request->input('payment_type'))->get();
			}
		}

		$display = "";
		$get_last = "";
		
		//report invoice
		// $query2 = $query->select('product_adjustment.*', 'product.product_name as product_name', 'outlet.outlet_name as outlet_name')->get();
		// $query2 = $query->select('product_adjustment.*', 'product.product_name as product_name', 'outlet.outlet_name as outlet_name')->pluck('created_at', 'id')->toArray();

		// dd($query2);

		// $display .='<div class="table-scrollable">
		//             <table id="table-laporan" class="table table-hover table-light">
		//               <thead>
		//                 <tr>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Product Name</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Barcode</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Total Item</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Type</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Price (Rp)</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Subtotal (Rp)</th>
		//                 </tr>
		//               </thead>
		//               <tbody>';
		// $query->select('orderhd.*', 'user.name as user_name', 'outlet.outlet_name', 'promo.discount_type', 'promo.discount_value')->orderBy('orderhd.id'); 
		// foreach($query->get() as $odr){
		// 	$display .= '<tr>
		// 					<td colspan="6" class="bg-dark font-white order_header">Office: '.$odr->outlet_name.' - Order Id: '.$odr->order_id.' - sales: '.$odr->user_name.'<br>'.date('d - F - Y', strtotime($odr->order_date)).' - cust: '.$odr->name.' - Payment Type: '.$odr->payment_type.'</td>
		// 				</tr>';
		// 	$curr_orderhd_id = $odr->id;
		// 	$curr_subtotal = 0;
		// 	$flagfind = 0;
		// 	foreach($query2->get() as $odc){
		// 		if($curr_orderhd_id == $odc->orderhd_id){
		// 			$flagfind 		= 1;
		// 			$subtotal 		= $odc->price;
		// 			$curr_subtotal += $subtotal;
		// 			$total_item   	= 1;
		// 			if($odc->type == 'dompul'){
		// 				$total_item 	= $odc->total_item;
		// 				$subtotal 		= $odc->price * $odc->total_item;
		// 			}
		// 			$display .= '<tr>
		// 							<td>'.$odc->name.'</td>
		// 							<td>'.$odc->barcode.'</td>
		// 							<td>'.$total_item.'</td>
		// 							<td>'.$odc->type.'</td>	
		// 							<td>'. number_format($odc->price).'</td>
		// 							<td>'. number_format($subtotal).'</td>
		// 						</tr>';
		// 		}else{
		// 			if($flagfind == 1){
		// 				break;
		// 			}
		// 		}
		// 	}

		// 	$display .= '<tr><td colspan="5" style="text-align: right;">Subtotal (Rp.)</td><td>'.number_format($odr->subtotal).'</td></tr>
		// 				<tr><td colspan="5" style="text-align: right;">Discount (Rp.)</td><td>'.number_format($odr->discount).'</td></tr>
		// 				<tr><td colspan="5" style="text-align: right;">Total (Rp.)</td><td>'.number_format($odr->total_price)	.'</td></tr>';
		// }

		// $mode = 2; //mode header
		$mode = 3;
		if($mode == 2){
			$display .='<div class="table-scrollable">
		            <table id="table-laporan" class="table table-hover table-light">
		              <thead>
		                <tr>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Header</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Product Name</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Barcode</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Total Item</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Type</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Price (Rp)</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Subtotal (Rp)</th>
		                </tr>
		              </thead>
		              <tbody>';
		}elseif($mode ==3){
			$display .='<div class="table-scrollable">
		            <table id="table-laporan" class="table table-hover table-light">
		              <thead>
		                <tr>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Date</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Order No</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Country</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Go From Indonesia</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Arrival In Indonesia</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Modem Name</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Qty Modem</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Rent Day</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Rent Price</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Deposit Price</th>
		                  <th class="bg-blue-madison font-white" style="text-align:center;">Sub Total</th>
		                </tr>
		              </thead>
		              <tbody>';
		}
		$query->select('orderhd.*', 'order_status.order_status_name', 'promo.voucher_code', 'user.name')->orderBy('orderhd.id'); 
		foreach($query->get() as $odr){
			// $display .= '<tr>
			// 				<td colspan="6" class="bg-dark font-white order_header">Office: '.$odr->outlet_name.' - Order Id: '.$odr->order_id.' - sales: '.$odr->user_name.'<br>'.date('d - F - Y', strtotime($odr->order_date)).' - cust: '.$odr->name.' - Payment Type: '.$odr->payment_type.'</td>
			// 			</tr>';
			$curr_orderhd_id = $odr->id;
			$curr_subtotal = 0;
			$flagfind = 0;
			$flagFirst = 0;
			foreach($query2->get() as $key => $odc){
				if($curr_orderhd_id == $odc->orderhd_id){
					$flagfind 		= 1;
					$subtotal 		= $odc->subtotal;
					$curr_subtotal += $subtotal;
					// $total_item   	= 1;
					// if($odc->type == 'dompul'){
					// 	$total_item 	= $odc->total_item;
					// 	$subtotal 		= $odc->price * $odc->total_item;
					// }

					$display .= '<tr>';
					if($mode == 2){
						if($flagFirst == 0){
							$display .= '<td>Office: '.$odr->outlet_name.' - Order Id: '.$odr->order_id.' - sales: '.$odr->user_name.'<br>'.date('d - F - Y', strtotime($odr->order_date)).' - cust: '.$odr->name.' - Payment Type: '.$odr->payment_type.'</td>';
							$flagFirst = 1;
						}else{
							$display .= '<td></td>';
						}
						$display .=	'<td>'.$odc->name.'</td>
									<td>'.$odc->barcode.'</td>
									<td>'.$total_item.'</td>
									<td>'.$odc->type.'</td>	
									<td>'. number_format($odc->price).'</td>
									<td>'. number_format($subtotal).'</td>
								</tr>';
					}elseif($mode == 3){
						$date_order = date('d/m/Y', strtotime($odr->created_date));
						$country = Country::whereIn('id', json_decode($odr->country_id))->get();
						$country2 = '';
						if(count($country) > 0){
							foreach ($country as $key => $val) {
								$country2 .= $val->country_name.';';
							}
						}
						$display .=	'<td>'.$date_order.'</td>
									<td>'.$odr->order_no.'</td>
									<td>'.$country2.'</td>
									<td>'.date('d/m/Y', strtotime($odr->go_from_indonesia)).'</td>	
									<td>'.date('d/m/Y', strtotime($odr->arrival_in_indonesia)).'</td>	
									<td>'.$odc->modem_name.'</td>
									<td>1</td>
									<td>'.$odc->rent_day.'</td>
									<td>'.number_format($odc->rent_price).'</td>
									<td>'.number_format($odc->deposit_price).'</td>	
									<td>'.number_format($subtotal).'</td>
								</tr>';
					}
					
				}else{
					if($flagfind == 1){
						break;
					}
				}

			}
			$display .= '<tr><td>'.$odr->order_status_name.'</td><td colspan="9" style="text-align: right;">Subtotal (Rp.)</td><td>'.number_format($odr->subtotal).'</td></tr>
						<tr><td colspan="10" style="text-align: right;">Total Shipping '.$odr->total_weight_kg.'Gr @'.number_format($odr->shipping_price).'/Kg';
			$total_shipping_price = $odr->total_shipping_price;
			if($odr->return_shipping_price > 0){
				$total_shipping_price += $odr->total_return_shipping_price;
				$display .= ' & @'.number_format($odr->return_shipping_price).'/Kg';
				$display .= ' (Rp.)</td>';
			}
			$display .= '<td>'.number_format($total_shipping_price).'</td></tr>
						<tr><td colspan="10" style="text-align: right;">Discount (Rp.)</td><td>'.number_format($odr->promo_amount).'</td></tr>';
			$total = $odr->total;		
			if($odr->total_refund > 0){
				$total -=  $odr->total_refund;
				$display .=	'<tr><td colspan="10" style="text-align: right;">Total Refund (Rp.)</td><td>'.number_format($odr->total_refund).'</td></tr>';
			}
			$display .=	'<tr><td colspan="10" style="text-align: right;">Total (Rp.)</td><td>'.number_format($total).'</td></tr>';
		}

		$display .= '<tr style="font-weight: bold;"><td colspan="4">Summary</td></tr>'.
					'<tr><td class="bg-blue-madison font-white">Category Modem</td>'.
					'<td class="bg-blue-madison font-white">Total Rent</td>'.
					'<td class="bg-blue-madison font-white">Total Deposit</td>'.
					'<td class="bg-blue-madison font-white">SubTotal</td></tr>';

		$arr_category_modem = [];
		foreach($query->get() as $odr){
			// $category_modem = $query2->groupBy('category_modem_id');
			// dd($query2->get());
			$curr_orderhd_id = $odr->id;
			foreach($query2->get() as $key => $odc){
				//check orderhd_id di tabel ordedt

				// var_dump($curr_orderhd_id);
				if($curr_orderhd_id == $odc->orderhd_id){
					$subtotal2 		= $odc->subtotal;
					$deposit_total 	= $odc->deposit_price;
					$rent_total 	= $odc->rent_price * $odc->rent_day;
					// var_dump($odc->category_modem_id);
					// var_dump($arr_category_modem);
					if(count($arr_category_modem) == 0){
						$temp = [
							'category_modem_id' => $odc->category_modem_id,
							'category_modem_name' => $odc->category_modem_name,
							'subtotal' => $subtotal2,
							'deposit_total' => $deposit_total,
							'rent_total' => $rent_total
						];
						array_push($arr_category_modem, $temp);
					}else{
						$flagFindCategoryModemId = 0;
						foreach ($arr_category_modem as $key2 => $val2) {
							// var_dump($val2['category_modem_id'].' - '.$val2['subtotal']);
							// var_dump($odc->category_modem_id.' - '.$subtotal2.'<br>');
							if($val2['category_modem_id'] == $odc->category_modem_id){
								// var_dump('test: '.$key2);
								// var_dump($val2[$key2]);
								$arr_category_modem[$key2]['subtotal'] += $subtotal2;
								$arr_category_modem[$key2]['deposit_total'] += $deposit_total;
								$arr_category_modem[$key2]['rent_total'] += $rent_total;
								$flagFindCategoryModemId = 1;
								// var_dump($val2);	
								break;
							}
						}
						// var_dump($arr_category_modem);

						if($flagFindCategoryModemId == 0){
							$temp = [
								'category_modem_id' => $odc->category_modem_id,
								'category_modem_name' => $odc->category_modem_name,
								'subtotal' => $subtotal2,
								'deposit_total' => $deposit_total,
								'rent_total' => $rent_total
							];
							array_push($arr_category_modem, $temp);
						}
					}
				}
			}
		}
		// dd($arr_category_modem);

		foreach ($arr_category_modem as $key3 => $val3) {
			$display .= '<tr>
							<td>'.$val3['category_modem_name'].'</td>
							<td>'.number_format($val3['deposit_total']).'</td>
							<td>'.number_format($val3['rent_total']).'</td>
							<td>'.number_format($val3['subtotal']).'</td>
						</tr>';
		}

		// $display .= '<td>Grand Total</td>';

		// $display .= '</tr>';


		$display .='</tbody>
					</table>
					</div>';

		return $display;
	}
}
