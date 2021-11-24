<?php namespace digipos\Http\Controllers\Front;

use Hash;

use digipos\models\Customer;

use Illuminate\Http\request;

class ForgotController extends Controller {

	public function __construct(){
		parent::__construct();

		$this->model = new Customer;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index($token = ""){
		$this->data["token"] = $token;
		$reset = $this->model->where('reset_password_token', $token)->first();

		if(count($reset) > 0) return $this->render_view('auth.reset');
		else redirect()->to('/');
	}

	public function reset(Request $request)
    {
    	dd('test');
        $this->validate($request, [
            'token' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $customers = $this->model->where('reset_password_token', $request->token)->first();

        if(count($customers) > 0){
        	$customer = $this->model->find($customers->id);
        	$customer->password = Hash::make($request->password);
        	$customer->reset_password_token = "";
        	$customer->save();
     	}
        
        return redirect()->to('/');
    }
}
