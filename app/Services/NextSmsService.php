<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NextSmsService
{
    public static function sendSingle($phone, $text)
    {

        $endPoint = 'multi';
        $payload = [
            'from' => config('nextsms.sender_id'),
            'to' => $phone,
            'text' => $text,
        ];

        return self::initHttp($payload, $endPoint);
    }

    public static function sendMulticast($messagesArray)
    {
        $formatedMessagesArray = array_map(function ($message) {
            return [
                'from' => config('nextsms.sender_id'),
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

        $apiUrl = config('nextsms.url').$endPoint;
        $token = config('nextsms.token');
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