<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AirtelSmsService
{
    public static function sendSingle($phone, $text)
    {

        $endPoint = 'multi';
        $payload = [
            'from' => env('SMS_SENDER_NAME', 'N-SMS'),
            'to' => $phone,
            'text' => $text,
        ];
        // Sending HTTP POST Request
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($apiUrl, $payload);

        return self::initHttp($payload, $endPoint);
    }

    public static function sendMulticast($messagesArray)
    {
        $formatedMessagesArray = array_map(function ($message) {
            return [
                'from' => env('SMS_SENDER_NAME', 'N-SMS'),
                'to' => $message['phone'],
                'text' => $message['body'],
            ];
        }, $messagesArray);

        $endPoint = 'multi';
        // SMS Payload
        $payload = [
            'messages' => $formatedMessagesArray,
            'reference' => 'aswqetgcv',
        ];

        return self::initHttp($payload, $endPoint);
    }

    public static function initHttp($payload, $endPoint)
    {

        $apiUrl = env('NEXTSMS_URL').$endPoint;
        $token = env('NEXTSMS_TOKEN');
        $authHeader = 'Basic '.$token;

        // Sending HTTP POST Request
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($apiUrl, $payload);

        // ✅ Fixed: Correct function name
        return response()->json($response->json());
    }
}
