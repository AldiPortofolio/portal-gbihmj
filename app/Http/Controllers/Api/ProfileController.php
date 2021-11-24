<?php namespace digipos\Http\Controllers\Api;

use Hash;
use JWTAuth;
use DB;
use DateTime;
use File;

use Illuminate\Http\Request;
use digipos\models\User;
use digipos\models\Config;
use digipos\models\Booking;

use digipos\Libraries\Email;
use App\Mail\MailOrder;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller {

	public function __construct(){
		parent::__construct();

		$this->distance_from_location = 3; //in kilometer
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function get_user(request $request){
		$phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token;
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		
		$user_id = $check_token['user_id'];
		$user_url = $this->data['image_path'].'/user/'.$user_id;

		$user = User::where('id', $user_id)->first();
		
		if($user){
			$user->image_url = $user_url.'/'.$user->images;
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Data user found', 'user' => $user);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data user not found !');
		}
		
		return response()->json($res);
	}

	public function edit_user_setting(request $request){
		$phone = $request->header('phone');
		$token = $request->header('token');
		$value = $request->value;

		//check token valid
		$check_token = $this->check_token($phone, $token);

		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}

		$user = User::where([['phone', $phone],['status', 'y']])->first();
		// return response()->json($user);

		if($user){
			$user->push_notification_setting = $value;
			$user->save();
			// return $user->token;
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success edit setting push notification');
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'User not valid !');
		}

		return response()->json($res);
	}

	public function delete_user(request $request){
		$phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);

		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}

		$user = User::where([['phone', $phone],['status', 'y']])->first();
		// return response()->json($user);

		if($user){
			$user->status = 'n';
			$user->save();
			// return $user->token;
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success delete user');
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'User not valid !');
		}

		return response()->json($res);
	}

	public function upload_profile_image(request $request){
		$phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);

		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}

		$user_id = $check_token['user_id'];
		// $user_id = '56';
		$user = User::where([['phone', $phone],['status', 'y']])->first();
		$image_path = $this->data['image_path'].'/user/'.$user_id;
		$image_path_2 = $this->data['image_path_2'].'/user/'.$user_id;
		// return var_dump($request->hasFile('image'));
		// return $_FILES['image'];
		// return $image_path_2;
		if ($request->hasFile('image')){
        	File::delete($image_path_2.'/'.$user->images);
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $image_path_2.'/']
					];
			$image = $this->build_image($data);
			$user->images = $image;
			$user->save();

			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success upload image', 'image_url' => $image_path.'/'.$image);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Image not found !');
		}

		return response()->json($res);
	}

	public function delete_profile_image(request $request){
		$phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);

		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}

		$user_id = $check_token['user_id'];
		// return $user_id;
		$user = User::where([['phone', $phone],['status', 'y']])->first();
		$image_path = $this->data['image_path_2'].'/user/'.$user_id;

		if($user->images){
        	File::delete($image_path.'/'.$user->images);
			// $data = [
			// 			'name' => 'image',
			// 			'file_opt' => ['path' => $image_path.'/']
			// 		];
			// $image = $this->build_image($data);
			// $image = $this->save_image(56,$_FILES);
			$user->images = null;
			$user->save();

			// return $image;
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success delete image');
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Image not found in database !');
		}

		return response()->json($res);
	}

	public function edit_profile(request $request){
		$phone = $request->header('phone');
		$token = $request->header('token');
		$name = $request->name;
		$email = $request->email;
		$birth_date = $request->birth_date;
		$address = $request->address;

		//check token valid
		$check_token = $this->check_token($phone, $token);

		// if($check_token['status_code'] != 1){
		// 	return response()->json($check_token);
		// }

		$user = User::where([['phone', $phone],['status', 'y']])->first();
		// return response()->json($user);

		if($user){
			$user->name = $name;
			$user->email = $email;

			$user->birth_date = date_format(date_create($birth_date), 'Y-m-d');
			$user->address = $address;
			$user->save();
			// return $user->token;
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success edit profile');
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'User not valid !');
		}

		return response()->json($res);
	}

	public function save_image($id,$files){
		if ($files){
			// $check_face 	= $this->detect_face($files["file"]["tmp_name"]);
			// if($check_face == 'not_face' && $this->data['face_detection'] == 'y'){
			// 	return 'not_face';
			// }
	
			list($w, $h) = getimagesize($files["file"]["tmp_name"]);
			/* calculate new image size with ratio */
			$width 	= 350;
			$height = ($width/$w) * $h;
			$ratio 	= max($width/$w, $height/$h);
			$h 		= ceil($height / $ratio);
			$x 		= ($w - $width / $ratio) / 2;
			$w 		= ceil($width / $ratio);
			
			$temp	= 'components/images/temp';
			$imagename = date('Y-m-d_H:i:s').".jpg";
			$path 	= trim($temp)."/".$imagename;
			/* read binary data from image file */
			$imgString = file_get_contents($files['file']['tmp_name']);
			/* create image from string */
			$image 	= imagecreatefromstring($imgString);
			$tmp 	= imagecreatetruecolor($width, $height);
	
			imagecopyresampled($tmp, $image,0, 0, $x, 0,$width, $height,$w, $h);
			/* Save image */
			switch ($files['file']['type']) {
				case 'image/jpeg':
					imagejpeg($tmp, $path, 60);
					  break;
				case 'image/png':
					  imagepng($tmp, $path, 0);
					  break;
				case 'image/gif':
					  imagegif($tmp, $path);
					  break;
				default:
					  exit;
					  break;
			}
	
			$dirname= 'components/images/user/'.$id.'/image';
			$this->move_image($dirname,$imagename);
	
			// $usr = User::find($id);
			// if($usr->status_last_absence_image == 'y'){
			// 	$usr->images = 'absence/'.$imagename;
			// 	$usr->save();
			// }
			return ['status' => 'continue','imagename' => $imagename];
		}else{
			return ['status' => 'no_images'];
		}
	}
	
}