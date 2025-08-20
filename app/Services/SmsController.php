<?php
namespace App\Http\Controllers\SmsApi;


use App\Http\Controllers\Controller;

use App\Models\SingleMessage;
use App\Models\SmsGateway;
use Illuminate\Http\Request;


class SmsController extends Controller
   {
        public static function sendSMS($message,$recipients_phone=array()){

          $activeGateway = SmsGateway::where('is_default', 1)->first();

          $gatewayName = $activeGateway->name;

          $smsController =  new SmsController();

         switch($gatewayName){
            case 'NEXT SMS':
                 return $smsController->nextSms($activeGateway, $message, $recipients_phone);
                 break;
            case 'AIRTEL SMS':
                return $smsController->airtelSms($activeGateway, $message, $recipients_phone);
                break;
            default:

         }
   }

   public function airtelSms($activeGateway, $message, $recipients_phone){
       $apiUrl = $activeGateway->api_url;
       $authKey = $activeGateway->token;

       $body = array(
            "service" => array(
                "micro"=>"sms-engine",
                "task"=>"new-sms",
                "method"=>"POST",
                "route"=>"/"
            ),
            "data" => array(
                "phone_numbers"=>$recipients_phone,
                "message"=>$message
            ),
        );

        $json_body = json_encode($body);

        $authHeader = 'Bearer '.$authKey;

        $response = $this->curlFunction($authHeader, $apiUrl, $json_body);

        return $response;
   }

   private function nextSms($activeGateway, $message, $recipients_phone)
   {


       $apiUrl = $activeGateway->api_url;
       $authKey = $activeGateway->token;
       $senderName = $activeGateway->sender_name;

       $postFields = array(
            'from' => $senderName,
            'to' => $recipients_phone,
            'text' => $message,
            );

        $body = json_encode($postFields);
        $authHeader = 'Basic '. $authKey;

        $response = $this->curlFunction($authHeader, $apiUrl, $body);

        return $response;

   }


    private function beemAfricaController($activeGateway, $message, $recipients_phone)
   {


       $apiUrl = $activeGateway->api_url;
       $authKey = $activeGateway->token;
       $senderName = $activeGateway->sender_name;

       $postFields = array(
            'from' => $senderName,
            'to' => $recipients_phone,
            'text' => $message,
            );

        $body = json_encode($postFields);
        $authHeader = 'Basic '. $authKey;

        $response = $this->curlFunction($authHeader, $apiUrl, $body);

        return $response;

   }









   private function curlFunction($authHeader, $url, $body){
       $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$body,
            CURLOPT_HTTPHEADER => array(
                'Authorization: '. $authHeader,
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
   }



}
