<?php 

namespace digipos\Libraries;

use digipos\models\Push_notification;

class Pushnotification{
  private static $data;
  private static $gcm_key;
  private static $title;
  private static $message;
  private static $registrationsid;
  private static $registrationsid_ios;

  public static function setGcmkey(){
      Self::$gcm_key    = env('GCM_KEY');
  }

  public static function title($title){
      Self::$title      = $title;
  }

  public static function message($message){
      Self::$message    = $message;
  }

  public static function registrationsid($id){
      $list_android = [];
      $list_ios = [];
      foreach($id as $token){
        // $platform = Push_notification::where('token', $token)->first()->platform;
        $platform = 'Android';

        if($platform == "iOS") $list_ios[] = $token;
        else $list_android[] = $token;
      }

      Self::$registrationsid   = $list_android;
      Self::$registrationsid_ios   = $list_ios;
  }

  public static function send(){
    if(count(Self::$registrationsid) > 0){
      //Set Key
      Self::setGcmkey();
      
      $gcm_key  = Self::$gcm_key;
      $msg = [
                'title'     => Self::$title,
                'message'   => Self::$message,
                'vibrate'   => 1,
                'sound'     => 'default',
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon'
              ];
      $fields = [
        'registration_ids'  => Self::$registrationsid,
        'data'              => $msg
      ];
       
      $headers = array(
        'Authorization: key=' . $gcm_key,
        'Content-Type: application/json'
      );
       
      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
      $result = curl_exec($ch );
      curl_close( $ch );

      return $result;
    }

    foreach(Self::$registrationsid_ios as $token){
      // Nuestro token
      $deviceToken = $token;

      // El password del fichero .pem
      $passphrase = 'Solveway!789';
       
      // El mensaje push
      $message = Self::$message;
       
      $ctx = stream_context_create();
      //Especificamos la ruta al certificado .pem que hemos creado
      stream_context_set_option($ctx, 'ssl', 'local_cert', 'components/admin/pushcert.pem');
      stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
       
      // Abrimos conexión con APNS
      $fp = stream_socket_client(
        'ssl://gateway.push.apple.com:2195', $err,
        $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
       
      if (!$fp) {
        exit("Error: $err $errstr" . PHP_EOL);
      }
       
      //echo 'Connect' . PHP_EOL;
       
      // Creamos el payload
      $body['aps'] = array(
        'alert' => array(
          'body' => $message,
          'action-loc-key' => 'Dash',
        ),
        'badge' => 1,
        'sound' => 'default',
      );
       
      // Lo codificamos a json
      $payload = json_encode($body);
       
      // Construimos el mensaje binario
      $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
       
      // Lo enviamos
      $result = fwrite($fp, $msg, strlen($msg));
       
      if (!$result) {
        echo 'Notification error' . PHP_EOL;
      } else { 
        echo 'Notification success' . PHP_EOL;
      }
       
      // cerramos la conexión
      fclose($fp);
    }

    // die;
  }
}