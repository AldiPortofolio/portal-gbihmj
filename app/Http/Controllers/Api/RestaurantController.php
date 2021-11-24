<?php namespace digipos\Http\Controllers\Api;

use Hash;
use JWTAuth;
use DB;
use DateTime;
use Illuminate\Http\Request;
use digipos\models\User;
use digipos\models\Config;
use digipos\models\Booking;

use digipos\Libraries\Email;
use App\Mail\MailOrder;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller {

	public function __construct(){
		parent::__construct();

		$this->distance_from_location = 3; //in kilometer
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function create(){
		$res['feature'] = Restaurant_feature::where('status','y')->orderBy('feature_name','asc')->select('id','feature_name')->get(); 
		$res['food'] 	= Restaurant_food::where('status','y')->orderBy('food_name','asc')->select('id','food_name')->get(); 
		$res['tag'] 	= Restaurant_tag::where('status','y')->orderBy('tag_name','asc')->select('id','tag_name')->get();
		$res['listDay']	= ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
		$start 			= '00:00';
		$end 			= '24:00';
		$listHours[] 	= $start; 
		while($start < '23:45'){
			$start  	= $this->set_dif_time('+','15','minutes',$start);
			$listHours[] 	= $start;
		}
		$listHours[]		= $end;
		$res['listHours']	= $listHours;
		return response()->json($res);
	}

	public function store(request $request){
		dd($request->all());
	}
}
