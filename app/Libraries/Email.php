<?php namespace digipos\Libraries;

use Mail;

class Email{
	private static $to;
	private static $subject;
	private static $view;
	private static $email_data;

	public static function to($to){
        Self::$to = $to;
    }

    public static function subject($subject){
        Self::$subject = $subject;
    }

    public static function view($view){
        Self::$view = $view;
    }

    public static function email_data($email_data){
        Self::$email_data = $email_data;
    }

    public static function send(){
    	$to 		= Self::$to;
    	$subject 	= Self::$subject;
    	$view 		= Self::$view;
    	$email_data = Self::$email_data;
        Mail::later(1, $view,$email_data, function($message) use($to,$subject){
		    $message->to($to)->subject($subject);
		});
    }
}