<?php

namespace App\Services;

use App\Models\SmsGateway;

class NotificationService
{
    public function sendSMS($body, $recipients_phone = [])
    {

        try {
            // Validate and filter phone numbers (must start with 255 and have 12 digits)
            $validPhoneNumbers = array_filter($recipients_phone, function ($phone) {
                return is_numeric($phone) && strlen($phone) === 12 && strpos($phone, '255') === 0;
            });
            // If there are valid numbers, format messages
            if (! empty($validPhoneNumbers)) {
                // Create the message array
                $messageArray = array_map(function ($phone) use ($body) {
                    return [
                        'phone' => $phone,
                        'body' => $body,
                    ];
                }, array_values($validPhoneNumbers));

                // Reset array keys
                return self::SMSRouting($messageArray);
            }

            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function SMSRouting($messageArray)
    {
        $activeGateway = SmsGateway::where('is_active', 1)->first();
        switch ($activeGateway->name) {
            case 'BEEM AFRICA':
                return (new BeemAfricaSmsService)->sendMultiCast($messageArray);
            case 'NEXT SMS':
                return (new NextSmsService)->sendMultiCast($messageArray);
            case 'AIRTEL SMS':
            default:
                return (new NextSmsService)->sendMultiCast($messageArray);
        }
    }

    public function sendFCM($title, $body, $recipients_token = [])
    {

        try {
            // Filter out null or empty tokens
            $validTokens = array_filter($recipients_token, function ($token) {
                return ! empty($token); // Remove empty or null values
            });

            // Check if there are valid tokens before proceeding
            if (! empty($validTokens)) {
                $iconUrl = null;
                $fcmService = new FcmService;

                return $fcmService->sendMulticastNotification(array_values($validTokens), $title, $body, $iconUrl);
            }

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function sendALL($title, $body, $recipients_phone = [], $recipients_token = [])
    {
        try {
            $response = '';
            // Validate and filter phone numbers (must start with 255 and have 12 digits)
            $validPhoneNumbers = array_filter($recipients_phone, function ($phone) {
                return is_numeric($phone) && strlen($phone) === 12 && strpos($phone, '255') === 0;
            });
            // If there are valid numbers, format messages
            if (! empty($validPhoneNumbers)) {
                // Create the message array
                $messageArray = array_map(function ($phone) use ($body) {
                    return [
                        'phone' => $phone,
                        'body' => $body,
                    ];
                }, array_values($validPhoneNumbers));

                // Reset array keys
                $response = self::SMSRouting($messageArray);
            }
            // Filter valid tokens (remove empty ones)
            $validTokens = array_filter($recipients_token, function ($token) {
                return ! empty($token);
            });

            if (! empty($validTokens)) {
                $iconUrl = null;
                $fcmService = new FcmService;
                $response = $fcmService->sendMulticastNotification(array_values($validTokens), $title, $body, $iconUrl);
            }

            return $response;

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function sendCombined($messageArray)
    {
        try {
            $response = '';

            $validMessages = array_filter($messageArray, function ($message) {
                return ! empty($message['token'] && ! empty($message['title']) && ! empty($message['body'])); // Ensures token is not empty
            });

            // Check if there are valid messages before sending
            if (! empty($validMessages)) {
                $iconUrl = null;
                $fcmService = new FcmService;
                $response = $fcmService->pushMulticastNotification($validMessages);
            }

            $validPhoneNumbers = array_filter($messageArray, function ($message) {
                return ! empty($message['phone']) && ! empty($message['body']); // Ensure 'phone' and 'body' exist
            });

            // If there are valid numbers, format messages
            if (! empty($validPhoneNumbers)) {
                // Create the message array
                $formattedMessages = array_map(function ($message) {
                    return [
                        'phone' => $message['phone'],
                        'body' => $message['body'],
                    ];
                }, array_values($validPhoneNumbers)); // Reset array keys

                // Send messages
                $response = self::SMSRouting($formattedMessages);
            }

            return $response;

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
