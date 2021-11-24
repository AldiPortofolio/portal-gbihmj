<?php namespace digipos\Http\Controllers\Api;

use Hash;
use JWTAuth;
use DB;
use DateTime;
use File;

use Illuminate\Http\Request;
use digipos\models\User;
use digipos\models\Config;
use digipos\models\Church;
use digipos\models\Church_images;

use digipos\Libraries\Email;
use App\Mail\MailOrder;
use Illuminate\Support\Facades\Mail;

class ChurchController extends Controller {

	public function __construct(){
		parent::__construct();

		$this->distance_from_location = 3; //in kilometer
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function upload_image(Request $request){
		$church_id = $request->church_id;
        // dd($church_id);
		if ($request->hasFile('image')){
        	// File::delete($this->image_path.$this->model->image);
			$image_path = $this->data['image_path_2'];
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $image_path.'/church/']
					];
            // dd('test');                    
			$image = $this->build_image($data);
			
			// echo $image_path.'/church/'.$image;
            // echo($image);
            // return;
			$church_images = new Church_images;
			$church_images->image = $image;
			
			$church_images->church_id = $church_id;
            // dd('test 3');
            // dd($church_images);
			$church_images->save();

			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success upload image', 'data' => $church_images);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Image not found !');
		}

		return json_encode($res);
	}

    public function edit_status_church_image(Request $request){
		$id = $request->id;
		$status = $request->status;

		$church_images = Church_images::find($id);
		$church_images->status = $status;
		$church_images->save();

		$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success update data', 'data' => $church_images);
		
		return json_encode($res);
	}

    public function delete_church_image(Request $request){
		$id = $request->id;
        $datetime = date('Y-m-d H:m:s');

        $church_images = Church_images::find($id);
        if($church_images->image){
            $image_path = $this->data['image_path_2'];
            File::delete($image_path.'/church/'.$church_images->image);
        }
        $church_images->deleted_at = $datetime;
		$church_images->save();

		$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success delete data');
		
		return json_encode($res);
	}
}