<?php namespace digipos\Http\Controllers\Api;

use Hash;
use JWTAuth;
use DB;
use DateTime;
use Illuminate\Http\Request;
use digipos\models\User;
use digipos\models\Config;

use digipos\Libraries\Email;
use digipos\Libraries\Pushnotification;
use App\Mail\MailOrder;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller {

	public function __construct(){
		parent::__construct();

		$this->user 			= new User;
		$this->max_failed_verification = Config::select('value')->where('name','max_failed_verification')->first()->value;
		$this->digit_otp = 4;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
	public function verification_otp(request $request){
		$phone 	= $request->phone;
		$otp 	= $request->otp;
		$token  =  uniqid();
		$firebase_token = $request->firebase_token;
		// $this->max_failed_verification;

		$user =  User::where('phone', $phone)->first();
		$count_failed_verification = $user->count_failed_verification;
		$email = $user->email;

		$user_wa_log =	DB::table('user_wa_log')
							->select('user.*', 'user_wa_log.otp')
							->join('user', 'user.id', 'user_id')
							->where([['user.phone', $phone],['user_wa_log.otp', $otp]])
							->orderBy('user_wa_log.id','desc')
							->first();

		$res;

		//jika otp terakhir sama dengan otp input, confirm status aktif dan respon sukses
		/*
			jika salah otp, cek 
				jika sudah mencapai max_failed_verification, maka kirim otp 3 menit lagi ke wa dan email, dan kirim respon anda sudah n kali salah memasukan otp, harus tunggu 3 menit
				jika belum mencapai max_failed_verification, maka kirim otp 1 menit lagi ke wa dan email, dan kirim respon error, anda salah memasukan otp
		*/
		if(!$user_wa_log){
			$count_failed_verification += 1;
			if($count_failed_verification == $this->max_failed_verification){
				$update_user = DB::update('update user set count_failed_verification = 0 where phone = ?', [$phone]);

				$res = array('status' => 'invalid', 'status_code' => 2, 'message' => "Verification failed, you have reach maximum limit failed verification");
			}else{
				$update_user = DB::update('update user set count_failed_verification = '.$count_failed_verification.' where phone = ?', [$phone]);

				$res = array('status' => 'invalid', 'status_code' => 2, 'message' => "Verification failed OTP not valid");
			}			
		}else{
			$res = array();

			//send otp to whatsupp
			$msg_otp = 'GBI HMJ Admin. JANGAN BERIKAN kode ini ke siapa pun. Kode OTP: '.$otp.' berlaku selama 3 menit';
			$sendOtpToWA = $this->send_otp_to_whatsapp($phone, $msg_otp);
			$res['send_otp_to_whatsaapp'] = 'failed';
			$sendOtpToWA_2 = json_decode($sendOtpToWA);
			if($sendOtpToWA_2->status == 1 && $sendOtpToWA_2->text == 'Success'){
				$res['send_otp_to_whatsaapp'] = 'success';
			}

			// Send email
			$this->data = array(
				'title' => 'GBI HMJ',
				'phone' => $phone,
				'msg' => $msg_otp
			);
			
			$dt_email = array();
			$dt_email['subject'] = "Request Kode OTP";
			$dt_email['to'] 		= $email;

			$res_email = Mail::send('front.mail.content', $this->data, function ($message) use($dt_email){
				$message->subject($dt_email['subject'])
					->to($dt_email['to']);
			});	
			
			$update_user = DB::update("update user set count_failed_verification = 0, token = '".$token."', `status` = 'y', firebase_token = '".$firebase_token."' where phone = ?", [$phone]);
			$user = DB::select('select * from user where phone = :phone',['phone' => $phone]);

			$res['status'] = 'success';
			$res['status_code'] = 1;
			$res['message'] = 'Verification success';
			$res['user'] = $user[0];
		}

		return response()->json($res);
	}

	//Login by otp
	public function check_otp(request $request){
		$phone 	= $request->phone;
		$otp 	= $request->otp;
		$token 	=  uniqid();
		$firebase_token = $request->firebase_token;

		$user_wa_log =	DB::table('user_wa_log')
							->select('user.*', 'user_wa_log.otp')
							->join('user', 'user.id', 'user_id')
							->where('user.phone', $phone)
							->orderBy('user_wa_log.id','desc')
							->first();
		$res;
		if($user_wa_log){
			if($user_wa_log->otp == $otp){
				if($user_wa_log->status == 'y'){
					$user = User::where('phone', $phone)->first();
					$user->token = $token;
					$user->firebase_token = $firebase_token;
					$user->is_login = 'y';
					$user->save();
	
					$res = array('status' => 'success', 'status_code' => 1,'message' => "Phone Number and OTP valid", 'user' => $user);
				}else{
					$res = array('status' => 'invalid', 'status_code' => 2,'message' => "Jemaat status not verified !");
				}
			}else{
				$res = array('status' => 'invalid', 'status_code' => 2,'message' => 'OTP not valid !');
			}
		}else{
			$res = array('status' => 'invalid', 'status_code' => 2,'message' => 'OTP for this phone number not found !');
		}

		return response()->json($res);
	}

	public function request_otp(request $request){
		$phone 	= $request->phone;
		$user_wa_log =	DB::table('user_wa_log')
							->select('user.id as user_id', 'user.email','user_wa_log.*')
							->join('user', 'user.id', 'user_wa_log.user_id')
							->where('user.phone', $phone)
							->orderBy('user_wa_log.id','desc')
							->first();

		$res = array();

		//if phone no tidak ditemukan di tabel user_wa_log
		if(!$user_wa_log){
			$res['status'] = 'invalid';
			$res['status_code'] = 2;
			$res['message'] = 'Phone number not found !';

			return response()->json($res);
		}

		$last_send_otp = $user_wa_log->created_at;
		$email = $user_wa_log->email;
		$user_id = $user_wa_log->user_id;
		$datetime = date('Y-m-d H:i:s');

		$date_a = new DateTime($last_send_otp);
		$date_b = new DateTime($datetime);

		$interval = date_diff($date_a, $date_b);
		//get menit
		// return $user_wa_log->created_at.'<br>'.json_encode($date_a).'<br>'.json_encode($date_b).'<br>'.json_encode($interval);
		$diff_minute =  $interval->format("%i");
		// return $diff_minute;
		if($diff_minute < 1){
			$res = array('status' => 'invalid', 'status_code' => 1, 'message' => 'Time interval with last request otp, less than 1 minute !');
		}else{
			$digits = $this->digit_otp;
			$otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
			$res['otp'] = $otp;
			//send otp to whatsupp
			$msg_otp = 'GBI HMJ Admin. JANGAN BERIKAN kode ini ke siapa pun. Kode OTP: '.$otp.' berlaku selama 3 menit';
			$sendOtpToWA = $this->send_otp_to_whatsapp($phone, $msg_otp);
			$res['send_otp_to_whatsapp'] = 'failed';
			$sendOtpToWA_2 = json_decode($sendOtpToWA);
			if($sendOtpToWA_2->status == 1 && $sendOtpToWA_2->text == 'Success'){
				$res['send_otp_to_whatsapp'] = 'success';
			}

			// Send email
			$this->data = array(
				'title' => 'GBI HMJ',
				'phone' => $phone,
				'msg' => $msg_otp
			);
			
			$dt_email = array();
			$dt_email['subject'] 	= "Request Kode OTP";
			$dt_email['to'] 		= $email;

			$res_email = Mail::send('api.mail.content', $this->data, function ($message) use($dt_email){
				$message->subject($dt_email['subject'])
					->to($dt_email['to']);
			});	
				
			// check for failures
			if( count(Mail::failures()) > 0 ) {
				// return response showing failed emails
				$res['send_otp_to_email'] = 'failed';
			}else{
				$res['send_otp_to_email'] = 'success';
			}

			if($res['send_otp_to_email'] == 'success' && $res['send_otp_to_whatsapp'] == 'success'){
				DB::statement('insert into user_wa_log (user_id, otp, messageId, `to`, status, text, cost, created_at) values (?, ?, ?, ?, ?, ?, ?, ?)', [$user_id, $otp, $sendOtpToWA_2->messageId, $sendOtpToWA_2->to, $sendOtpToWA_2->status, $sendOtpToWA_2->text, $sendOtpToWA_2->cost, date('Y-m-d H:i:s')]);

				$res['status'] = 'success';
				$res['status_code'] = 1;
				$res['message'] = 'OTP has sent to whatsapp and email';
				$res['otp'] =$otp;
			}else{
				$res['status'] = 'invalid';
				$res['status_code'] = 2;
				$res['message'] = 'Phone number and OTP not valid !';
			}
		}

		return response()->json($res);
	}

	public function check_no_kaj(request $request){
		$no_kaj 	= $request->no_kaj;

		$user = User::where('no_kaj', $no_kaj)->first();

		$res;
		if($user){
			if($user->status == 'y'){
				$res = array('status' => 'invalid', 'status_code' => 2,'message' => "No KAJ found, it has registered");
			}else{
				$res = array('status' => 'success', 'status_code' => 1, 'message' => "No KAJ found and ready for registered", 'user' => $user);
			}
		}else{
			$res = array('status' => 'invalid', 'status_code' => 2,'message' => 'No KAJ not found');
		}

		return response()->json($res);
	}

	public function register(request $request){
		// return response()->json($request);
		// $no_kaj 	= $request->no_kaj;
		$name 		= $request->name;
		$email 		= $request->email;
		$birth_date = $request->birth_date;
		$phone 		= $request->phone;
		$address 	= $request->address;
		
		$res;

		if(trim($phone) == "" || trim($email) == ""){
			$res = array('status' => 'error', 'status_code' => 0, 'message' => 'Please input phone number and email !');
			return response()->json($res);
		}
		$checkemail = User::where("phone",$phone)->orWhere("email",$email)->first();
		if($checkemail){
			if($checkemail->status == 'n'){
				$res = array();
				$digits = $this->digit_otp;
				$otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
				$res['otp'] = $otp;

				//send otp to whatsupp
				$msg_otp = 'GBI HMJ Admin. JANGAN BERIKAN kode ini ke siapa pun. Kode OTP: '.$otp.' berlaku selama 3 menit';
				$sendOtpToWA = $this->send_otp_to_whatsapp($phone, $msg_otp);
				$res['send_otp_to_whatsapp'] = 'failed';
				$sendOtpToWA_2 = json_decode($sendOtpToWA);
				if($sendOtpToWA_2->status == 1 && $sendOtpToWA_2->text == 'Success'){
					$res['send_otp_to_whatsapp'] = 'success';
				}

				// Send email
				$this->data = array(
					'title' => 'GBI HMJ',
					'email' => $email,
					'phone' => $phone,
					'msg' => $msg_otp
				);
				
				$dt_email = array();
				$dt_email['subject'] = "Request Kode OTP";
				$dt_email['to'] 		= $email;

				$res_email = Mail::send('front.mail.content', $this->data, function ($message) use($dt_email){
					$message->subject($dt_email['subject'])
						->to($dt_email['to']);
				});	
					
				// check for failures
				if( count(Mail::failures()) > 0 ) {
					// return response showing failed emails
					$res['send_otp_to_email'] = 'failed';
				}else{
					$res['send_otp_to_email'] = 'success';
				}

				if($res['send_otp_to_whatsapp'] == 'success' && $res['send_otp_to_email'] == 'success'){
					DB::statement('insert into user_wa_log (user_id, otp, messageId, `to`, status, text, cost, created_at) values (?, ?, ?, ?, ?, ?, ?, ?)', [$checkemail, $otp, $sendOtpToWA_2->messageId, $sendOtpToWA_2->to, $sendOtpToWA_2->status, $sendOtpToWA_2->text, $sendOtpToWA_2->cost, date('Y-m-d H:i:s')]);

					$res = array('status' => 'success', 'status_code' => 1,'message' => 'Email or Phone Number found, but not verified !');
				}else{
					$res = array('status' => 'invalid', 'status_code' => 2,'message' => 'Email or Phone Number found, but not verified !');
				}
			}else{
				$res = array('status' => 'invalid', 'status_code' => 2,'message' => 'Email or Phone Number already registered !');
			}
		}else{
			$res = array();
			$digits = $this->digit_otp;
			$otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
			$res['otp'] = $otp;

			//send otp to whatsupp
			$msg_otp = 'GBI HMJ Admin. JANGAN BERIKAN kode ini ke siapa pun. Kode OTP: '.$otp.' berlaku selama 3 menit';
			$sendOtpToWA = $this->send_otp_to_whatsapp($phone, $msg_otp);
			$res['send_otp_to_whatsapp'] = 'failed';
			$sendOtpToWA_2 = json_decode($sendOtpToWA);
			if($sendOtpToWA_2->status == 1 && $sendOtpToWA_2->text == 'Success'){
				$res['send_otp_to_whatsapp'] = 'success';
			}

			// Send email
			$this->data = array(
				'title' => 'GBI HMJ',
				'email' => $email,
				'phone' => $phone,
				'msg' => $msg_otp
			);
			
			$dt_email = array();
			$dt_email['subject'] = "Request Kode OTP";
			$dt_email['to'] 		= $email;

			$res_email = Mail::send('front.mail.content', $this->data, function ($message) use($dt_email){
				$message->subject($dt_email['subject'])
					->to($dt_email['to']);
			});	
				
			// check for failures
			if( count(Mail::failures()) > 0 ) {
				// return response showing failed emails
				$res['send_otp_to_email'] = 'failed';
			}else{
				$res['send_otp_to_email'] = 'success';
			}

			// $res['send_otp_to_whatsapp'] = 'success';
			// $res['send_otp_to_email'] = 'success';
			if($res['send_otp_to_whatsapp'] == 'success' && $res['send_otp_to_email'] == 'success'){
				// $user = DB::statement('insert into user (no_kaj, name, email, birth_date, phone, address, user_access_id, otp, created_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$no_kaj, $name, $email, $birth_date, $phone, $address, 3, $otp, date('Y-m-d H:i:s')]);
				$user 			= new User;
				// $user->no_kaj 	= $no_kaj;
				$user->name 	= $name;
				$user->email 	= $email;
				$user->birth_date 		= $birth_date;
				$user->phone 			= $phone;
				$user->address 			= $address;
				$user->user_access_id 	= 3;
				$user->count_failed_verification 	= 0;
				$user->created_at 	= date('Y-m-d H:i:s');
				$user->save();

				DB::statement('insert into user_wa_log (user_id, otp, messageId, `to`, status, text, cost, created_at) values (?, ?, ?, ?, ?, ?, ?, ?)', [$user->id, $otp, $sendOtpToWA_2->messageId, $sendOtpToWA_2->to, $sendOtpToWA_2->status, $sendOtpToWA_2->text, $sendOtpToWA_2->cost, date('Y-m-d H:i:s')]);

				$res['status'] = 'success';
				$res['status_code'] = 1;
				$res['message'] = 'Register success';
			}else{
				$res['status'] = 'error';
				$res['status_code'] = 0;
				$res['message'] = 'Register failed !';
			}
		}
		return response()->json($res);
	}

	public function check_phone(request $request){
		$phone = $request->phone;

		$user = User::where('phone', $phone)->first();

		$res = array();
		if($user){
			$email = $user->email;
			if($user->status == 'y'){
				$digits = $this->digit_otp;
				$otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
				$res['otp'] = $otp;

				//send otp to whatsupp
				$msg_otp = 'GBI HMJ Admin. JANGAN BERIKAN kode ini ke siapa pun. Kode OTP: '.$otp.' berlaku selama 3 menit';
				$sendOtpToWA = $this->send_otp_to_whatsapp($phone, $msg_otp);
				$res['send_otp_to_whatsapp'] = 'failed';
				$sendOtpToWA_2 = json_decode($sendOtpToWA);
				if($sendOtpToWA_2->status == 1 && $sendOtpToWA_2->text == 'Success'){
					$res['send_otp_to_whatsapp'] = 'success';
				}

				// Send email
				$this->data = array(
					'title' => 'GBI HMJ',
					'email' => $email,
					'phone' => $phone,
					'msg' => $msg_otp
				);
				
				$dt_email = array();
				$dt_email['subject'] = "Request Kode OTP";
				$dt_email['to'] 		= $email;

				// $res_email = Mail::send('front.mail.content', $this->data, function ($message) use($dt_email){
				// 	$message->subject($dt_email['subject'])
				// 		->to($dt_email['to']);
				// });	
					
				// check for failures
				// if( count(Mail::failures()) > 0 ) {
				// 	// return response showing failed emails
				// 	$res['send_otp_to_email'] = 'failed';
				// }else{
				// 	$res['send_otp_to_email'] = 'success';
				// }
				
				$res['send_otp_to_email'] = 'success';
				if($res['send_otp_to_whatsapp'] == 'success' && $res['send_otp_to_email'] == 'success'){
					$user_id = $user->id;
					DB::statement('insert into user_wa_log (user_id, otp, messageId, `to`, status, text, cost, created_at) values (?, ?, ?, ?, ?, ?, ?, ?)', [$user_id, $otp, $sendOtpToWA_2->messageId, $sendOtpToWA_2->to, $sendOtpToWA_2->status, $sendOtpToWA_2->text, $sendOtpToWA_2->cost, date('Y-m-d H:i:s')]);
					
					$res['status'] = 'success';
					$res['status_code'] = 1;
					$res['message'] = 'Phone number registered';
				}else{
					$res['status'] = 'invalid'; 
					$res['status_code'] = 2;
					$res['message'] = 'Phone number registered';
				}
			}else{
				$res['status'] = 'invalid'; 
				$res['status_code'] = 2; 
				$res['message'] = 'Phone number not verified !'; 
			}
		}else{
			$res['status'] = 'invalid'; 
			$res['status_code'] = 2; 
			$res['message'] = 'Phone number not found !'; 
		}
		return response()->json($res);
	}

	public function send_push_notif_birthday(request $request){
		$date = date('Y-m-d');
		// $user = User::where([['status', 'y'],['push_notification_setting', 1]])->whereDate('birth_date','=',$date)->get();
		$user = User::where([['status', 'y'],['push_notification_setting', 1],['phone', '08999878046']])->get();
		
		$arr_firebaseToken = [];
		foreach ($user as $key => $u) {
			if(isset($u->firebase_token)){
				array_push($arr_firebaseToken, $u->token);
			}
		}
		
		// dd($arr_firebaseToken);
		Pushnotification::title('GBI HMJ');
		Pushnotification::message('Selamat ulang tahun untuk anda');
		Pushnotification::registrationsid($arr_firebaseToken);
		$res = Pushnotification::send();

		return response()->json($res);
	}

	// public function login(request $request){
	// 	$phone = $request->phone;
	// 	$otp = $request->otp;

	// 	$user = User::where([['phone', $phone],['status', 'y']])->first();
	// 	// return response()->json($user);

	// 	if($user){
	// 		$token =  uniqid();
	// 		$user->token = $token;
	// 		$user->save();
	// 		// return $user->token;
	// 		$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success login', 'user' => $user);
	// 	}else{
	// 		$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Phone number not valid');
	// 	}

	// 	return response()->json($res);
	// }

	public function logout(request $request){
		$phone = $request->phone;

		$user = User::where('phone', $phone)->first();
		// return response()->json($user);

		// if($user){
			$user->token = null;
			$user->firebase_token = null;
			$user->is_login = 'n';
			$user->save();
			// return $user->token;
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success logout');
		// }else{
		// 	$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Phone number not valid');
		// }

		return response()->json($res);
	}

	public function send_email(request $request, $email){
		// Send email
		$this->data = array(
			'title' => 'GBI HMJ',
			'phone' => '08999878046',
			'msg' => 'Test send email'
		);
		
		$dt_email = array();
		$dt_email['subject'] 	= "Test Send Email";
		$dt_email['to'] 		= $email;

		$res_email = Mail::send('api.mail.content', $this->data, function ($message) use($dt_email){
			$message->subject($dt_email['subject'])
				->to($dt_email['to']);
		});

		$res;
		// check for failures
		if( count(Mail::failures()) > 0 ) {
			// return response showing failed emails
			$res['send_otp_to_email'] = 'failed';
		}else{
			$res['send_otp_to_email'] = 'success';
		}

		if($res['send_otp_to_email'] == 'success'){
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success send email');
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Failed send email !');
		}
		return response()->json($res);
	}

	public function send_otp_to_whatsapp($phone, $msg){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://console.zenziva.net/wareguler/api/sendWA/',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => array('userkey' => '4ef7d9dca3f0','passkey' => 'fa47554820dac1d6107d96ef','to' => $phone,'message' => $msg),
		));

		$response = curl_exec($curl);
		
		// hardcode response
		// $response = json_encode(array(
		// 	"messageId" => 3057239,
		// 	"to" => "+628999878046",
		// 	"status" => "1",
		// 	"text" => "Success",
		// 	"cost" => 220
		// ));

		curl_close($curl);

		//return success
		//{"messageId":3057239,"to":"+628999878046","status":"1","text":"Success","cost":220}
		return $response;
	}
}
