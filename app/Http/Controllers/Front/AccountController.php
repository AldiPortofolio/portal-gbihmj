<?php namespace digipos\Http\Controllers\Front;

use Illuminate\Http\request;

use Hash;
use Validator;
use Redirect;
use Image;
use File;
use Auth;
use Session;

use digipos\models\Banner;
use digipos\models\City;
use digipos\models\Kecamatan;
use digipos\models\Kelurahan;
use digipos\models\Kode_pos;
use digipos\models\Password_resets;
use digipos\models\Customer;
use digipos\models\Client;
use digipos\models\Client_card;
use digipos\models\Card_category;

class AccountController extends ShukakuController {

	public function __construct(){
		parent::__construct();

		$this->middleware($this->auth_guard)->only(['profile', 'edit_profile', 'change_password']);
		$this->menu = $this->data['path'][0];
		$this->data['menu'] 		= $this->menu;
	}

	public function lock_in(request $request){
		$validator 	= Validator::make($request->all(), [
		    'lock_em'				=> 'required',
			'lock_pw'				=> 'required',
		],
		[
			'lock_em.required'		=> 'Email is required',
			'lock_pw.required'		=> 'Password is required',
		]);

		if($validator->fails()){
			$error = $validator->errors();
			$er_message = $error->first();
			return redirect()->back()->with('err', $er_message);
		}

		$email 		= $request->lock_em;
		$password 	= $request->lock_pw;

		$get_cust = Customer::select('status')->where('email', $email)->orWhere('handphone', $email)->first();

		if(count($get_cust) > 0){
			if(Auth::attempt(['handphone' => $email,'password' => $password]) || Auth::attempt(['email' => $email,'password' => $password])){
				if($get_cust->status == "y"){
					return redirect()->route('cs_profile');
				} else if($get_cust->status == "n"){
	        		session(['rgs_dt_sub' => $request->lock_em]);
	      			return redirect()->route('vc_page')->with('suc', 'Please verification your account');
			  	}
			} else{
	      	 	return redirect()->guest('/')->with('err', 'Sorry, Email, Handphone / password wrong');
			}
		} else{
		    return redirect()->guest('/')->with('err', 'Sorry, Email, Handphone / password wrong');
		}
	}

	public function lock_out(){
		auth()->guard($this->guard)->logout();
      	return redirect()->route('cs_home')->with('suc', 'You have been logout');
	}

	public function register(){
		$this->data['met_title'] 		= $this->data['web_title'];
		$this->data['met_keywords'] 	= $this->data['web_keywords'];
		$this->data['met_description'] 	= $this->data['web_description'];
		$this->data['banner'] = Banner::where('id', 3)->first();
		return $this->render_view('pages.account.register');
	}

	public function sub_register(request $request){
		$this->validate($request,[
			'rg_name'							=> 'required',
			'rg_email'							=> 'required|email|unique:customer,email',
			'rg_phone'							=> 'required|unique:customer,handphone',
			'rg_password'						=> 'required',
			'g-recaptcha-response'				=> 'required',
		],
		[
			'rg_name.required'					=> 'Name is required',
			'rg_email.required'					=> 'Email is required',
			'rg_email.unique'					=> 'Email already be taken',
			'rg_phone.unique'					=> 'Handphone already be taken',
			'rg_email.email'					=> 'Email Must be Valid',
			'rg_phone.required'					=> 'Handphone is required',
			'rg_password.required'				=> 'Password is required',
			'g-recaptcha-response.required'		=> 'Recaptcha is required',
		]);

		$captcha = $request->input('g-recaptcha-response');
		$secretKey = "6LfwCR4UAAAAABF3iG7IrmiseJVZ2ebeeTVpfCgA";
		$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha);
        $responseKeys = json_decode($response,true);
        
        if(intval($responseKeys["success"]) !== 1) {
         	return redirect()->back()->withError('Wrong Captcha, Please Try again');
        } else{
			$request->request->add(['url' => 'http://202.77.104.230/web_service/send_verification_code.php', 'phone' => $request->rg_phone, 'name' => $request->rg_name]);
      	 	$verif = json_decode($this->smsverification($request));

      	 	if($verif->responsestatus == "S"){
      	 		$customer 					 = new Customer;
	        	$customer->name 			 = $request->rg_name;
	        	$customer->handphone 		 = $request->rg_phone;
	        	$customer->email 			 = $request->rg_email;
	        	$customer->password 		 = Hash::make($request->rg_password);
	        	$customer->status 			 = 'n';
	        	$customer->verification_code = $verif->code;
	        	$customer->save();

	        	$url      = "front.mail.content";
				$title    = "Registration";
				$subject  = "Verification Code"; 
				$status   = "cust";
				$to 	  = $request->rg_email;
				$content  = "<p>Hi, ". $request->rg_name .",</p><p>Terima kasih telah melakukan Registrasi</p><p>Gunakan Code berikut untuk melakukan verifikasi : ". $verif->code ."</p>";


				$request->request->add([
										'e_url'     => $url,
										'e_title'   => $title,
										'e_subject' => $subject,
										'e_email'	=> $to,
										'e_status'  => $status,
										'e_content' => $content
										]);

				$this->email($request);

        		session(['rgs_dt_sub' => $request->rg_email]);

      	 		return redirect()->route('vc_page')->with('th_message', '');
      	 	} else{
      	 		return redirect()->back()->with('fail','Sorry there a problem, Please Try again');
      	 	}
      	}
	}

	public function verification(){
		$this->data['met_title'] 		= $this->data['web_title'];
		$this->data['met_keywords'] 	= $this->data['web_keywords'];
		$this->data['met_description'] 	= $this->data['web_description'];
		$this->data['em_dt'] = session('rgs_dt_sub');
		return $this->render_view('pages.account.verification');
	}

	public function verif_account(request $request){
		$code_1 = $request->code_1;
		$code_2 = $request->code_2;
		$code_3 = $request->code_3;
		$code_4 = $request->code_4;

		$get_code = $code_1.$code_2.$code_3.$code_4;

		$email 	= session('rgs_dt_sub');

		$check = Customer::select('id','email', 'verification_code', 'status')->where('email', $email)->first();

		if($check){
			if($check->status == 'n'){
				if($check->verification_code != NULL){
					if($get_code == $check->verification_code){
						$update = Customer::where('email', $email)->update(['status' => 'y']);
						auth()->guard($this->guard)->loginusingId($check->id);
						Session::forget('rgs_dt_sub');
      	 				return redirect()->route('cs_profile')->with('th_message', '');
					} else{
						return redirect()->back()->with('fail','Sorry, wrong Verification code');
					}
				} else{	
					return redirect()->back()->with('fail','Maaf terjadi kesalahan, silahkan klik kirim ulang verifikasi');
				}
			} else if($check->status == 'y'){
				return redirect()->back()->with('fail','Akun Sudah di verifikasi');
			}
		}
	}

	public function verif_register(){
		$this->data['met_title'] 		= $this->data['web_title'];
		$this->data['met_keywords'] 	= $this->data['web_keywords'];
		$this->data['met_description'] 	= $this->data['web_description'];
		return $this->render_view('pages.account.verif_register');
	}

	public function verif_reg_account(request $request){
		$this->validate($request,[
			'email'		=> 'exists:customer,email',
		]);

		$code_1 = $request->code_1;
		$code_2 = $request->code_2;
		$code_3 = $request->code_3;
		$code_4 = $request->code_4;

		$get_code = $code_1.$code_2.$code_3.$code_4;

		$check = Customer::select('id','email', 'verification_code', 'status')->where('email', $request->email)->first();

		if($check){
			if($check->status == 'n'){
				if($check->verification_code != NULL){
					if($get_code == $check->verification_code){
						$update = Customer::where('email', $request->email)->update(['status' => 'y']);
      					return redirect()->route('cs_home')->with('suc', 'Verifikasi berhasil, silahkan Login akun anda');
					} else{
						return redirect()->back()->with('fail','Sorry, wrong Verification code');
					}
				} else{	
					return redirect()->back()->with('fail','Maaf terjadi kesalahan, silahkan klik kirim ulang verifikasi');
				}
			} else if($check->status == 'y'){
				return redirect()->back()->with('fail','Akun Sudah di verifikasi');
			}
		}
	}

	public function sent_verif(request $request){
		$email = $request->email;

		$customer = Customer::select('handphone', 'name')->where('email', $email)->first();

		$request->request->add(['url' => 'http://202.77.104.230/web_service/send_verification_code.php', 'phone' => $customer->handphone, 'name' => $customer->name]);
  	 	$verif = json_decode($this->smsverification($request));

  	 	if(count($customer) > 0){
  	 		if($verif->responsestatus == "S"){
	        	$url      = "front.mail.content";
				$title    = "Registration";
				$subject  = "Verification Code"; 
				$status   = "cust";
				$to 	  = $email;
				$content  = "<p>Hi, ". $customer->name .",</p><p>Terima kasih telah melakukan Registrasi</p><p>Gunakan Code berikut untuk melakukan verifikasi : ". $verif->code ."</p>";

				$request->request->add([
										'e_url'     => $url,
										'e_title'   => $title,
										'e_subject' => $subject,
										'e_email'	=> $to,
										'e_status'  => $status,
										'e_content' => $content
										]);

				$this->email($request);

				$update = Customer::where('email', $email)->update(['verification_code' => $verif->code]);

		        return response()->json(['status' => 'success']);
			} else if($get_data->responsestatus == "F"){
		        return response()->json(['status' => 'failed']);
			}
  	 	} else{
		    return response()->json(['status' => 'invalid']);
  	 	}
	}

	public function profile(){
		$ids = auth()->guard($this->guard)->user()->id;

		$this->data['profile'] = Customer::where('id', $ids)->first();
		$cus = Customer::where('id', $ids)->first();

		if($cus->no_card) {
			$get_no = json_decode($cus->no_card);
			$rs_card = [];
			foreach($get_no as $ct){
				$card = Client_card::join('client', 'client.id', 'client_card.id_client')->join('card_category', 'card_category.id', 'client_card.id_card_category')->where([['client_card.id_client', $ct->id_client],['client_card.id_card_category', $ct->id_card_category], ['client.status', 'y'], ['card_category.status', 'y']])->first();

				if(count($card) > 0){
					$get_card = [
							'image' => $card->image,
							'no_kartu' => $ct->no_kartu,
						];
					array_push($rs_card, $get_card);
				}
			}

			if(count($rs_card) == 0){
				$rs_card = [];
			}
		} else{
			$rs_card = [];
		}

		if(count($this->data['profile']->kota) > 0) {
			$get_city = City::select('name')->where('id', $this->data['profile']->kota)->first();

			$gt_city = array(
       	  				'KAB.', 'KOTA', 'ADM.'
       	  			   ); 
		 	$city = str_replace($gt_city, ' ', $get_city->name);

		 	$this->data['kota'] = trim($city, ' ');
		} else{
			$this->data['kota'] = "";
		}
		
		if(count($this->data['profile']->kecamatan) > 0) {
			$this->data['kecamatan'] = Kecamatan::select('name')->where('id', $this->data['profile']->kecamatan)->first();
		} else{
			$this->data['kecamatan'] = "";
		}

		if(count($this->data['profile']->kelurahan) > 0) {
			$this->data['kelurahan']		= Kelurahan::select('name')->where('id', $this->data['profile']->kelurahan)->first();
		} else{
			$this->data['kelurahan'] = "";
		}

		if(count($this->data['profile']->kodepos) > 0) {
			$this->data['kodepos']		= Kode_pos::select('postcode')->where('id', $this->data['profile']->kodepos)->first();
		} else{
			$this->data['kodepos'] = "";
		}

		$this->data['pengeluaran'] = [
							'1' => '<= 900.000',
							'2' => '=> 900.001 - 1.250.000',
							'3'	=> '1.250.001 - 1.750.000',
							'4' => '1.750.001 - 2.500.000',
							'5' => '2.500.001 - 4.000.000',
							'6' => '4.000.001 - 6.000.000',
							'7' => '> 6.000.000'
						];

		$this->data['card'] = $rs_card;
		$this->data['met_title'] 		= $this->data['web_title'];
		$this->data['met_keywords'] 	= $this->data['web_keywords'];
		$this->data['met_description'] 	= $this->data['web_description'];
		return $this->render_view('pages.account.profile');
	}

	public function edit_profile(){
		$ids = auth()->guard($this->guard)->user()->id;

		$this->data['profile'] = Customer::where('id', $ids)->first();
		$data_city = City::select('id','name')->get();
			
		foreach($data_city as $q => $ctc){
			$ft_city = array(
       	  				'UTARA', 'BARAT', 'SELATAN', 'TIMUR', 'PUSAT', 'TENGGARA', 'DAYA', 'KAB.', 'KOTA'
       	  			   ); 
		  
		 	$ttl_city = str_replace($ft_city, ' ', $ctc->name);

		 	$ttl[$q] = ucfirst(strtolower(trim($ttl_city, ' ')));
		 	 
			$gt_city = array(
       	  				'KAB.', 'KOTA', 'ADM.'
       	  			   ); 
		 	$get_city = str_replace($gt_city, ' ', $ctc->name);

		 	$city[$ctc->id] = trim($get_city, ' ');
		}

		sort($city);
		
		$this->data['pengeluaran'] = [
							'1' => '<= 900.000',
							'2' => '=> 900.001 - 1.250.000',
							'3'	=> '1.250.001 - 1.750.000',
							'4' => '1.750.001 - 2.500.000',
							'5' => '2.500.001 - 4.000.000',
							'6' => '4.000.001 - 6.000.000',
							'7' => '> 6.000.000'
						];

		$this->data['ttl'] 				= array_unique($ttl);
		$this->data['city']				= array_unique($city);
		if(count($this->data['profile']->kecamatan) > 0) {
			$this->data['kecamatan'] = Kecamatan::select('id', 'name')->where('id', $this->data['profile']->kecamatan)->first();
		} else{
			$this->data['kecamatan'] = "";
		}

		if(count($this->data['profile']->kelurahan) > 0) {
			$this->data['kelurahan']		= Kelurahan::select('id', 'name')->where('id', $this->data['profile']->kelurahan)->first();
		} else{
			$this->data['kelurahan'] = "";
		}

		if(count($this->data['profile']->kodepos) > 0) {
			$this->data['kodepos']		= Kode_pos::select('id', 'postcode')->where('id', $this->data['profile']->kodepos)->first();
		} else{
			$this->data['kodepos'] = "";
		}

		$this->data['met_title'] 		= $this->data['web_title'];
		$this->data['met_keywords'] 	= $this->data['web_keywords'];
		$this->data['met_description'] 	= $this->data['web_description'];
		return $this->render_view('pages.account.edit_profile');
	}

	public function get_district(request $request){
		$district = Kecamatan::select('id', 'name')->where('city_id', $request->id)->get();

		return response()->json(['district' => $district]);
	}

	public function get_subdistrict(request $request){
		$subdistrict = Kelurahan::select('id', 'name')->where('district_id', $request->id)->get();

		return response()->json(['subdistrict' => $subdistrict]);
	}

	public function get_postcode(request $request){
		$postcode = Kode_pos::select('id', 'postcode')->where('subdistrict_id', $request->id)->get();

		if(count($postcode) == 0){
			$postcode = Kode_pos::select('id', 'postcode')->where('district_id', $request->dis)->get();
		}

		return response()->json(['postcode' => $postcode]);
	}

	public function update_profile(Request $request){
		$this->validate($request,[
			'ep_name'					=> 'required',
			'ep_email'					=> 'required|email',
			'ep_handphone'				=> 'required',
			'ep_address'				=> 'required',
			'ep_date'					=> 'required',
			'ep_tp_born'				=> 'required',
			'ep_address'				=> 'required',
		],
		[
			'ep_name.required'			=> 'Nama harus diisi',
			'ep_email.required'			=> 'Email harus diisi',
			'ep_handphone.required'		=> 'Handphone harus diisi',
			'ep_address.required'		=> 'Address harus diisi',
			'ep_date.required'			=> 'Tanggal Lahir harus diisi',
			'ep_tp_born.required'		=> 'Tempat Lahir harus diisi',
			'ep_address.required'		=> 'Alamat harus diisi',
		]);

		$ttl = $request->ep_tp_born;
		$status = $request->ep_status;
		$job = $request->ep_pekerjaan;
		$agama = $request->ep_agama;
		$tujuan = $request->ep_tujuan;

		if($ttl == "other" || $ttl == NULL){
			$tp_born = ucfirst($request->ep_tp_other);
		} else{
			$tp_born = $request->ep_tp_born;
		}

		if($status == "other" || $status == NULL){
			$tp_status = ucfirst($request->ep_status_other);
		} else{
			$tp_status = $request->ep_status;
		}

		if($job == "other" || $job == NULL){
			$tp_job = ucfirst($request->ep_pekerjaan_other);
		} else{
			$tp_job = $request->ep_pekerjaan;
		}

		if($agama == "other" || $agama == NULL){
			$tp_agama = ucfirst($request->ep_agama_other);
		} else{
			$tp_agama = $request->ep_agama;
		}

		if($tujuan == "other" || $tujuan == NULL){
			$tp_tujuan = ucfirst($request->ep_tujuan_other);
		} else{
			$tp_tujuan = $request->ep_tujuan;
		}

		$pengeluaran = [
							'1' => '<= 900.000',
							'2' => '=> 900.001 - 1.250.000',
							'3'	=> '1.250.001 - 1.750.000',
							'4' => '1.750.001 - 2.500.000',
							'5' => '2.500.001 - 4.000.000',
							'6' => '4.000.001 - 6.000.000',
							'7' => '> 6.000.000'
						];

		$rs_pengeluaran = $pengeluaran[$request->ep_pengeluaran];

		$customer = Customer::where('id', auth()->guard($this->guard)->user()->id)->first();
		$customer->name 			= $request->ep_name;
		$customer->status_menikah 	= $tp_status;
		$customer->email 			= $request->ep_email;
		$customer->telephone 		= $request->ep_phone;
		$customer->handphone 		= $request->ep_handphone;
		$customer->address 			= $request->ep_address;
		$customer->rt 				= $request->ep_rt;
		$customer->rw 	 			= $request->ep_rw;
		$customer->kota 	 		= $request->ep_kota;
		$customer->kecamatan 	 	= $request->ep_kecamatan;
		$customer->kelurahan 		= $request->ep_kelurahan;
		$customer->kodepos 	 		= $request->ep_kodepos;
		$customer->ttl 				= $tp_born.', '.$request->ep_date;
		$customer->jenis_kelamin	= $request->ep_jenis_kelamin;
		$customer->pekerjaan 		= $tp_job;
		$customer->agama 			= $tp_agama;
		$customer->pendidikan		= $request->ep_pendidikan;
		$customer->tujuan 			= $tp_tujuan;
		$customer->pengeluaran 		= $rs_pengeluaran;
		$customer->hobby 			= $request->ep_hobby;
		
		$ks = "";

		$img = $request->file('photo');
		if(isset($img)){
			$rand = str_random(6);
	        $ks = $rand . '_' . str_replace(' ','_',$img->getClientOriginalName());
	        $image = Image::make($img)->save('components/admin/image/customer/'.$ks)->resize(500, 500);
       		$customer->photo = $ks;
	    } 

		if($request->input('remove-single-image-photo') == 'y'){
	        if($customer->photo){
	        	if(file_exists('components/admin/image/customer/'.$customer->photo)) {
					File::delete('components/admin/image/customer/'. $customer->photo);
		        }	
	        }

	        if(!isset($img)){
	        	$customer->photo = '';
	        }
		}

		$customer->save();

      	return redirect()->route('cs_profile')->with('suc', 'Update Profile sukses');
	}

	public function add_card(Request $request){
		$ids = $request->id;
		$fail = 0;

      	$request->request->add(['url' => 'http://202.77.104.230/web_service/cek_no_kartu.php', 'no_kartu' => $request->id]);

		$get_data = json_decode($this->cek_no_card($request));

		if($get_data->responsestatus == "S") {
			$check_data = Customer::select('no_card')->where([['id', auth()->guard($this->guard)->user()->id], ['status', 'y']])->first();

			if(count($check_data->no_card) > 0){
				$cov_data = json_decode($check_data->no_card);
				foreach($cov_data as $crd_data){
					if($crd_data->no_kartu == $ids){
	        			$fail = 1;
	        			break;
					}
				}
			}

			if($fail > 0){
	       		return response()->json(['status' => 'failed', 'message' => 'Card Already Exists.']);
			} else{
				$get_client = $get_data->client; 
				$get_type = $get_data->type_kartu;

				$find_client = Client::select('id')->where([['client_name', 'LIKE', '%'.$get_client.'%'], ['status', 'y']])->first();
				if(count($find_client) > 0) {
					$get_client_card = Client_card::where('id_client', $find_client->id)->count();
					
					if($get_client_card  > 0){
						if($get_type != NULL){
							$fd_get_type = Card_category::select('id')->where([['card_category_name', 'LIKE', '%'.$get_type.'%'],['status', 'y']])->first();

							if(count($fd_get_type) > 0){
								$find_type = $fd_get_type->id;
							} else{
								$find_type = 0;
							}
						} else{
							$fd_get_type = Client_card::join('card_category', 'client_card.id_card_category', 'card_category.id')->select('card_category.id')->where([['client_card.id_client', $find_client->id], ['card_category.status', 'y']])->first();

							if(count($fd_get_type) > 0){
								$find_type = $fd_get_type->id;
							} else{
								$find_type = 0;
							}
						}

						if($find_type != 0){
							$content  = Customer::select('no_card')->where('id', auth()->guard($this->guard)->user()->id)->first();
					        $temp = [
					    				'id_client' => $find_client->id,
					    				'id_card_category' => $find_type,
					    				'no_kartu' => $ids
					    			];

							if($content->no_card != NULL){
								$crd = json_decode($content->no_card);

								$crd[] = (object)$temp;
							} else{
								$crd[] = (object)$temp; 
							}

							$update = Customer::where('id', auth()->guard($this->guard)->user()->id)->update(['no_card' => json_encode($crd)]);

					        return response()->json(['status' => 'success', 'message' => 'Add Card Success']);
						} else{
		        			return response()->json(['status' => 'failed', 'message' => 'Card Type Not Found.']);
						}
					} else{
		        		return response()->json(['status' => 'failed', 'message' => 'Card Not Found.']);
					}
			    } else{
		        	return response()->json(['status' => 'failed', 'message' => 'Client Not Found.']);
			    }	
			}
		} else if($get_data->responsestatus == "F"){
	        return response()->json(['status' => 'failed', 'message' => 'Card Not Found.']);
		}
	}

	public function get_card(){
		$ids = auth()->guard($this->guard)->user()->id;

		$cus = Customer::where('id', $ids)->first();

		if($cus->no_card) {
			$get_no = json_decode($cus->no_card);
			$rs_card = [];
			foreach($get_no as $ct){
				if($ct->id_card_category > 0) {
					$card = Client_card::join('card_category', 'client_card.id_card_category', 'card_category.id')->join('client', 'client.id', 'client_card.id_client')->where([['client_card.id_client', $ct->id_client],['client_card.id_card_category', $ct->id_card_category], ['card_category.status', 'y'], ['client.status', 'y']])->select('client_card.image', 'client.client_name', 'card_category.card_category_name')->first();
				} else{
					$card = Client_card::join('card_category', 'client_card.id_card_category', 'card_category.id')->join('client', 'client.id', 'client_card.id_client')->where([['client_card.id_client', $ct->id_client], ['client.status', 'y'], ['card_category.status', 'y']])->select('client_card.image', 'client.client_name', 'card_category.card_category_name')->first();
				}
				
				if(count($card) > 0){
					$get_card = [
								'no_kartu' 	=> $ct->no_kartu,
								'client'   	=> $card->client_name,
								'type'   	=> $card->card_category_name
							]; 
					array_push($rs_card, $get_card);
				}

				if(count($rs_card) == 0){
					$rs_card = [];
				}	
			}
		} else{
			$rs_card = [];
		}
		
		$this->data['met_title'] 		= $this->data['web_title'];
		$this->data['met_keywords'] 	= $this->data['web_keywords'];
		$this->data['met_description'] 	= $this->data['web_description'];
		$this->data['card'] = $rs_card;

		return $this->render_view('pages.parts.card');
	}

	public function change_password(){
		$this->data['met_title'] 		= $this->data['web_title'];
		$this->data['met_keywords'] 	= $this->data['web_keywords'];
		$this->data['met_description'] 	= $this->data['web_description'];
		return $this->render_view('pages.account.change_password');
	}

	public function ch_password(Request $request){
		$this->validate($request,[
			'old_password'				=> 'required',
			'password'					=> 'required|min:6|confirmed',
			'password_confirmation'		=> 'required|min:6',
		],
		[
			'old_password.required'		=> 'Old Password is required',
			'password.required'			=> 'New Password is required',
			'password.min'				=> 'Password must be 6 digit or more',
			'password.confirmed'		=> 'Password & Confirm Not Match',
			'Cpassword.required'		=> 'Confirm Password is required',
			'Cpassword.min'				=> 'Confirm Password must be 6 digit or more',
		]);

		$id = auth()->guard($this->guard)->user()->id;
		$check = Customer::where('id', $id)->first();

		if (Hash::check($request->old_password, $check->password)) {
			$new_password = Hash::make($request->password);
			$customer = Customer::where('id', $id)->update(['password' => $new_password]);
      	 	return redirect()->back()->with('message', 'Change Password Success');
		} else{
			return redirect()->back()->withErrors(['password Not Found']);
		}
	}

	public function forgot_password(request $request){
		$validator 	= Validator::make($request->all(), [
		    'email'	=> 'required|exists:customer,email',
		]);


		if($validator->fails()){
			$error = $validator->errors();
			$er_message = $error->first();
			return redirect()->back()->with('err', $er_message);
		}

		$set_token = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$token = substr(str_shuffle($set_token), 0, 15);

		$password = new Password_resets;
		$password->email = $request->email;
		$password->token = $token;
		$password->save();

		$name = Customer::select('name')->where('email', $request->email)->first();
		
		$url      = "front.mail.content";
		$title    = "Forgot Password";
		$subject  = "Link Forgot Password"; 
		$status   = "cust";
		$to 	  = $request->email;
		$base_url = url('/');
		$content  = "<p>Hi, ". $name->name .",</p><p>Silahkan Klik link berikut untuk melakukan reset password : <a href=". $base_url ."/forget-password/". $token .">Link Forgot Password</a></p>";


		$request->request->add([
								'e_url'     => $url,
								'e_title'   => $title,
								'e_subject' => $subject,
								'e_email'	=> $to,
								'e_status'  => $status,
								'e_content' => $content
								]);

		$this->email($request);

		return redirect()->back()->with('suc', 'Request Success, Please Check Your Email');
	}

	public function c_forgot_password($request){
		$token = $request;

		$check = Password_resets::where([['token', $token], ['count', 0]])->first();
		
		if($check != NULL){
			$this->data['status'] = "found";
			$this->data['token'] = $token;
		} else{
			$this->data['status'] = "failed";
		}

		$this->data['met_title'] 		= $this->data['web_title'];
		$this->data['met_keywords'] 	= $this->data['web_keywords'];
		$this->data['met_description'] 	= $this->data['web_description'];
		return $this->render_view('pages.account.forgot_password');
	}

	public function ch_forgot_password(request $request){
		$this->validate($request,[
			'password'					=> 'required|min:6|confirmed',
			'password_confirmation'		=> 'required|min:6'
		],
		[
			'password.required'		=> 'New Password is required',
			'password.min'			=> 'Password must be 6 digit or more',
			'password.confirmed'	=> 'Password & Confirm Not Match',
			'Cpassword.required'	=> 'Confirm Password is required',
			'Cpassword.min'			=> 'Confirm Password must be 6 digit or more',
		]);

		$token = $request->sc_tk;

		$password = Hash::make($request->password);

		$get_cust = Password_resets::where('token', $token)->first();  

		$update = Customer::where('email', $get_cust->email)->update(['password' => $password]);
		$upd = Password_resets::where('token', $token)->update(['count' => 1]);
		
		return redirect()->route('cs_home')->with('suc', 'Your Password change success');
	}
}
