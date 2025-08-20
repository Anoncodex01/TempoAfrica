<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BeemAfricaSmsService
{
    public static function sendSingle($phone, $message)
    {

        $receipients = ['recipient_id' => 1, 'dest_addr' => $phone];
        $endPoint = 'send';

        $payload = [
            'source_addr' => config('beem.sender_id'),
            'schedule_time' => '',
            'encoding' => 0,
            'recipients' => json_encode($receipients),
            'message' => $message,
        ];

        return self::initHttp($payload, $endPoint);
    }

    public static function sendMulticast($messages)
    {
        $endPoint = 'send';
        $recipients = collect($messages)->map(function ($message, $index) {
            return [
                'recipient_id' => $index + 1,
                'dest_addr' => $message['phone'],
            ];
        })->values();

        $firstMessage = $messages[0];

        $payload = [
            'source_addr' => config('beem.sender_id'),
            'schedule_time' => '',
            'encoding' => 0,
            'message' => $firstMessage['body'],
            'recipients' => $recipients,
        ];

        return self::initHttp($payload, $endPoint);
    }

    public static function initHttp($payload, $endPoint)
    {
        $apiUrl = config('beem.url').$endPoint;
        $api_key = config('beem.api_key');
        $secret_key = config('beem.secret_key');
        // Sending HTTP POST Request
        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode($api_key.':'.$secret_key),
            'Content-Type: application/json',
            'Accept: application/json',
        ])->post($apiUrl, $payload);

        return $response;
    }
}
