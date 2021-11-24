<?php namespace digipos\Http\Controllers\Admin;

use DB;
use Session;
use Hash;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;

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
use digipos\models\Timetable;
use digipos\Libraries\Alert;
use digipos\Libraries\Timeelapsed;
use Illuminate\Http\Request;

class TimetableController extends KyubiController {

	public function __construct(){
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->title 			= "Timetable";
		$this->root_url			= "order/timetable";
		$this->root_link 		= "timetable";
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
		// $this->data['data1'] 		= $this->model->get();
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
		return $this->render_view('pages.timetable.index');
	}

	public function ext(request $request, $action){
		return $this->$action($request);
	}

	public function filter($request){
		// $outlet_access = $this->myStore2();
		$query = $this->model->leftJoin('order_status', 'order_status.id', 'orderhd.order_status_id')->leftJoin('promo', 'promo.id', 'orderhd.promo_id')->leftJoin('user', 'user.id', 'promo.user_id')->join('orderdt', 'orderdt.orderhd_id', 'orderhd.id');
		
		// return $request->input('outlet');
		// if($request->input('outlet') != ''){
		// 	if(in_array('0', $request->input('outlet'))){
				
		// 	}else{
		// 		$query->whereIn('user.outlet_id', $request->input('outlet'));
		// 	}
		// }else{
		// 	return '<div style="text-align:center;color:red;">Please select Office !</div>';
		// }
		
		$query2 	= Order_dt::Join('modem', 'modem.id', 'orderdt.modem_id')->orderBy('orderhd_id')->select('orderdt.*','modem.modem_name');
		$product 	= Modem::where('status', 'y')->leftJoin('category_modem', 'category_modem.id', 'modem.category_modem_id')->orderBy('category_modem_id')->select('modem.*', 'category_modem.category_modem_name')->get();
		// $outlet 	= $this->outlet->where('status', 'y')->get();

		if($request->input('search_date_from') != ""){
			// dd($request->input('search_date_from'));
			// $search_date_from = date('d-m-Y', strtotime($request->input('search_date_from') . ' -1 day'));
			$search_from = $this->displayToSql($request->input('search_date_from'));
			// dd($search_from);
			$query->whereDate('orderhd.go_from_indonesia', '>=', $search_from);
		}

		if($request->input('search_date_to') != ""){
			$search_to = $this->displayToSql($request->input('search_date_to'));
			$query->whereDate('orderhd.arrival_in_indonesia', '<=', $search_to);
		}

		$count_product = 0;

		$display = "";
		$get_last = "";
		
		$mode 		= 3;
		$begin 		= new DateTime($request->input('search_date_from')); 
		$end 		= new DateTime(date('d-m-Y', strtotime($request->input('search_date_to') . ' +1 day')));
		$interval 	= new DateInterval('P1D'); 
		$daterange 	= new DatePeriod($begin, $interval ,$end);
		// dd($daterange);
		$display .='<div class="table-scrollable">
			        <table id="table-laporan" class="table table-hover table-light">
			          <thead>
			            <tr>
			            <th class="bg-blue-madison font-white" style="text-align:center;" rowspan="1">Date\Modem</th>
			            <th class="bg-blue-madison font-white" style="text-align:center;" rowspan="1">Hari</th>';
		$range_day = 0;
		$arr_date = [];
		$arr_modem = [];
		$mode = 2;

		if($mode == 1){
			foreach($daterange as $date){
				$display .= '<th class="bg-blue-madison font-white" style="text-align:center;">'.$date->format("d-m-Y").'</th>';
				$range_day += 1;
				array_push($arr_date, $date->format("d-m-Y"));
			}
		}else{
			$prev_category_modem = '';
			$colspan = 0;
			foreach($product as $key => $odr){
				// $curr_category_modem = $odr->category_modem_id;
				// if($prev_category_modem != $curr_category_modem){
				// 	$display .= '<th class="bg-blue-madison font-white" style="text-align:center;">'.$odr->category_modem_name.'</th>';
				// 	$prev_category_modem = $curr_category_modem;
				// }else{
				// 	$colspan += 1;
				// }

				$display .= '<th class="bg-blue-madison font-white" style="text-align:center;">'.$odr->modem_name.' ('.$odr->imei.')</th>';
				$range_day += 1;
				$arr_modem[] = [
					'modem_id' 		=> $odr->id,
					'modem_name' 	=> $odr->modem_name
				];
				
				// array_push($arr_date, $date->format("d-m-Y"));
			}
		}
		

		$display .= '</tr>
		              </thead>
		              <tbody>';
		// if($mode == 2){
		// 	$display .='<div class="table-scrollable">
		//             <table id="table-laporan" class="table table-hover table-light">
		//               <thead>
		//                 <tr>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Header</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Product Name</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Barcode</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Total Item</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Type</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Price (Rp)</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Subtotal (Rp)</th>
		//                 </tr>
		//               </thead>
		//               <tbody>';
		// }elseif($mode ==3){

		// 	$display .='<div class="table-scrollable">
		//             <table id="table-laporan" class="table table-hover table-light">
		//               <thead>
		//                 <tr>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Date</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Order No</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Country</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Go From Indonesia</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Arrival In Indonesia</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Modem Name</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Qty Modem</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Rent Day</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Rent Price</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Deposit Price</th>
		//                   <th class="bg-blue-madison font-white" style="text-align:center;">Sub Total</th>
		//                 </tr>
		//               </thead>
		//               <tbody>';
		// }
		$query->select('orderhd.*', 'order_status.order_status_name', 'promo.voucher_code', 'user.name', 'orderdt.modem_id')->orderBy('orderdt.modem_id');
		$prev_category = ''; 
		// dd($query->get());

		if($mode == 1){
			foreach($product as $key => $odr){
				$curr_category = $odr->category_modem_id;
				if($prev_category == ''){
					$display .= '<tr>
										<td class="bg-dark font-white order_header" colspan="'.($range_day + 1).'">'.$odr->category_modem_name.'</td>
									</tr>';
					$prev_category = $curr_category;
				}else{
					if($prev_category != $curr_category){
						$display .= '<tr>
										<td class="bg-dark font-white order_header" colspan="'.($range_day + 1).'">'.$odr->category_modem_name.'</td>
									</tr>';
						$prev_category = $curr_category;
					}
				}
				
				$display .= '<tr>
								<td class="">'.$odr->modem_name.'</td>';
				
				$prev_orderhd_id = '';
				$curr_subtotal = 0;
				$flagfind = 0;
				$flagFirst = 0;
				// var_dump($odr->id.'<br>');
				$i = 0;

				//check data order
				foreach($query->get() as $key2 => $odc){
					// var_dump($key2.' - '.$odc->modem_id.' - '. $odr->id.'<br>');
					if($odc->modem_id == $odr->id){
						$flagfind = 1;
						// $curr_orderhd_id = $odc->id;
						// if($prev_orderhd_id != $curr_orderhd_id){
							foreach ($arr_date as $ad) {

								if($this->cekDateInDateRange($odc->go_from_indonesia, $odc->arrival_in_indonesia, date_format(date_create($ad),'Y-m-d'))){
										$color = "#14f057";
										if($odc->order_status_id == 5 || $odc->order_status_id == 6){
											$color = "#4646dd";
										}
										$display .= '<td bgcolor="'.$color.'" title="'.$odr->modem_name." ".$ad.'"><a href="'.url("_admin/order/manage-order/".$odc->id."/edit").'" style="color:white;">'.substr($odc->order_no, strpos($odc->order_no, 'I')+1).'</a></td>';
								}else{
									$display .= '<td ></td>';
								}
							}
						// }
					}

					$i++;

				}

				if($flagfind == 0){
					foreach ($arr_date as $ad) {
						$display .= '<td></td>';
					}
				}
				$display .= '</tr>';
				// 			<tr><td colspan="10" style="text-align: right;">Total Shipping '.$odr->total_weight_gram.'Gr @'.$odr->shipping_price.'/Kg (Rp.)</td><td>'.number_format($odr->total_shipping_price).'</td></tr>
				// 			<tr><td colspan="10" style="text-align: right;">Discount (Rp.)</td><td>'.number_format($odr->promo_amount).'</td></tr>
				// 			<tr><td colspan="10" style="text-align: right;">Total (Rp.)</td><td>'.number_format($odr->total)	.'</td></tr>';
			}																																																																																	
		}else{
			foreach($daterange as $date){
				$display 	.= '<tr><td class="" style="text-align:center;">'.$date->format("d-m-Y").'</td>
									<td class="" style="text-align:center;">'.$date->format("l").'</td>';
				$range_day 	+= 1;
				// dd($arr_modem);
				foreach($arr_modem as $am){
					$flagfind = 0;
					// check data order
					foreach($query->get() as $key2 => $odc){
						// var_dump($odc->modem_id);
						// var_dump($am['modem_id'].'<br>');
						// var_dump($am['modem_id'].'<br>');
						if($odc->modem_id == $am['modem_id']){
							// $curr_orderhd_id = $odc->id;
							// if($prev_orderhd_id != $curr_orderhd_id){
									// var_dump('start: '.$odc->go_from_indonesia.' end: '.$odc->arrival_in_indonesia.' current: '.$date->format("Y-m-d"));
									// var_dump($this->cekDateInDateRange($odc->go_from_indonesia, $odc->arrival_in_indonesia, $date->format("Y-m-d")));
									$search_date_from_2 = date('Y-m-d', strtotime($odc->go_from_indonesia . ' -1 day'));
									if($this->cekDateInDateRange($odc->delivery_date, $search_date_from_2, $date->format("Y-m-d"))){
										if($odc->order_status_id != 3){
											$flagfind = 1;	

											$color = '#4caf50';
											$display .= '<td bgcolor="'.$color.'" title="'.$am['modem_name']." ".$date->format("d-m-Y").'"><a href="'.url("_admin/order/manage-order/".$odc->id."/edit").'">'.substr($odc->order_no, strpos($odc->order_no, 'I')+1).'</a></td>';
										}
									}

									if($this->cekDateInDateRange($odc->go_from_indonesia, $odc->arrival_in_indonesia, $date->format("Y-m-d"))){
										if($odc->order_status_id != 3){
											$flagfind = 1;	

											$color = ($odc->order_status_id == 3 ? '#c8cdd7' : '#14f057');
											if($odc->order_status_id == 5 || $odc->order_status_id == 6){
												$color = "#4646dd";
											}
												$display .= '<td bgcolor="'.$color.'" title="'.$am['modem_name']." ".$date->format("d-m-Y").'"><a href="'.url("_admin/order/manage-order/".$odc->id."/edit").'">'.substr($odc->order_no, strpos($odc->order_no, 'I')+1).'</a></td>';
										}
									}else{
										// $display .= '<td>test</td>';
									}

									$search_date_to_2 = date('Y-m-d', strtotime($odc->arrival_in_indonesia . ' +1 day'));
									if($this->cekDateInDateRange($search_date_to_2, $odc->return_date, $date->format("Y-m-d"))){
										if($odc->order_status_id != 3){
											$flagfind = 1;	

											$color = '#4caf50';
											$display .= '<td bgcolor="'.$color.'" title="'.$am['modem_name']." ".$date->format("d-m-Y").'"><a href="'.url("_admin/order/manage-order/".$odc->id."/edit").'">'.substr($odc->order_no, strpos($odc->order_no, 'I')+1).'</a></td>';
										}
									}

							// }
						}else{
							// $display .= '<td>2</td>';
						}

					}

					if($flagfind == 0){
					 $display .= '<td></td>';
					}
				}
				$display 	.= '</tr>';	
				array_push($arr_date, $date->format("d-m-Y"));
			}
		}

		$display .='</tbody>
					</table>
					</div>';
		// $display .= '<table id="header-fixed" class="table table-hover table-light"></table>

		// 			<script>
		// 				var tableOffset = $("#table-laporan").offset().top;
		// 				var $header = $("#table-laporan > thead").clone();
		// 				var $fixedHeader = $("#header-fixed").append($header);

		// 				$(window).bind("scroll", function() {
		// 				    var offset = $(this).scrollTop();
						    
		// 				    if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
		// 				        $fixedHeader.show();
		// 				    }
		// 				    else if (offset < tableOffset) {
		// 				        $fixedHeader.hide();
		// 				    }
		// 				});
		// 			</script>';
		return $display;
	}

	public function cekDateInDateRange($from, $to, $curr_date){
		// Convert to timestamp
		  $start_ts = strtotime($from);
		  $end_ts = strtotime($to);
		  $user_ts = strtotime($curr_date);

		  // Check that user date is between start & end
		  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
	}
}
