<?php namespace digipos\Http\Controllers\Api;

use Hash;
use JWTAuth;
use DB;
use DateTime;
use Illuminate\Http\Request;
use digipos\models\User;
use digipos\models\Config;
use digipos\models\Warta;

use digipos\Libraries\Email;
use App\Mail\MailOrder;
use Illuminate\Support\Facades\Mail;

class WartaController extends Controller {

	public function __construct(){
		parent::__construct();

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function get_warta(request $request){
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
		$url = $this->data['file_path'].'/warta/';
		$music_image_url = $this->data['file_path'].'/warta/';
		$music = DB::table('warta')
					->where('status', 'y')
					->select('warta.*', DB::raw("CONCAT('$url', file) as file_url"))
					->offset($offset)->limit($limit)
					->get();

		foreach ($music as $key => $mu) {
			// $mu->file_url = urlencode($mu->file_url);
		}

		if(count($music) > 0){
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Data warta found', 'warta' => $music);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data warta not found !');
		}
		
		return response()->json($res);
	}

	public function upload_file(request $request){
		
		$image_path = $this->data['file_path'].'/warta';
		$image_path_2 = $this->data['file_path_2'].'/warta';
		// return var_dump($request->hasFile('image'));
		// return $_FILES['image'];
		// return $image_path_2;
		if ($request->hasFile('file')){
        	// File::delete($image_path_2.'/'.$user->images);
			$data = [
						'name' => 'file',
						'file_opt' => ['path' => $image_path_2.'/']
					];
			$image = $this->build_image($data);
			// $user->images = $image;
			// $user->save();

			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success upload file', 'filename' => $image, 'file_url' => $image_path.'/'.$image);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Failed upload file !');
		}

		return response()->json($res);
	}
}