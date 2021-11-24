<?php namespace digipos\Http\Controllers\Api;

use Hash;
use JWTAuth;
use DB;
use DateTime;
use Illuminate\Http\Request;
use digipos\models\User;
use digipos\models\Config;
use digipos\models\Music;

use digipos\Libraries\Email;
use App\Mail\MailOrder;
use Illuminate\Support\Facades\Mail;

class MusicController extends Controller {

	public function __construct(){
		parent::__construct();

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function get_music(request $request){
		$phone = $request->header('phone');
		$token = $request->header('token');
		$offset = $request->offset;
		$limit 	= $request->limit;

		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token;
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		
		$user_id = $check_token['user_id'];
		$music_url = $this->data['audio_path'].'/';
		$music_image_url = $this->data['image_path'].'/music/';
		$music = DB::table('music')
					->where('status', 'y')
					->select('music.*', DB::raw("CONCAT('$music_url', music_name) as music_url"), DB::raw("CONCAT('$music_image_url', image) as image_url"))
					->offset($offset)->limit($limit)
					->get();

		if(count($music) > 0){
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Data music found', 'music' => $music);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data music not found !');
		}
		
		return response()->json($res);
	}
}