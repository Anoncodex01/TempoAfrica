<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('firebase/firebase_credentials.json'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendMulticastNotification(array $deviceTokens, $title, $body, $iconUrl = null)
    {
        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
            'image' => $iconUrl,
        ]);

        $message = CloudMessage::new()->withNotification($notification);

        $report = $this->messaging->sendMulticast($message, $deviceTokens);

        $result = [
            'success_count' => $report->successes()->count(),
            'failure_count' => $report->failures()->count(),
            'failures' => [],
        ];

        // Loop through each failure to capture details
        foreach ($report->failures()->getItems() as $failure) {
            $result['failures'][] = [
                'token' => $failure->target(),
                'error' => $failure->error()->getMessage(),
            ];
        }

        return response()->json($result);
    }

    public function pushMulticastNotification($messagesArray)
    {

        // Initialize Firebase Messaging
        $firebase = (new Factory)->withServiceAccount(storage_path('firebase_credentials.json'));
        $messaging = $firebase->createMessaging();

        // Convert messages array into CloudMessage objects
        $cloudMessages = array_map(function ($message) {
            return CloudMessage::new()
                ->withNotification(Notification::create($message['title'], $message['body']))
                ->withData($message['data'] ?? []);
        }, $messagesArray);

        // Extract tokens from messages array
        $deviceTokens = array_column($messagesArray, 'token');

        $report = $this->messaging->sendMulticast($cloudMessages, $deviceTokens);

        $result = [
            'success_count' => $report->successes()->count(),
            'failure_count' => $report->failures()->count(),
            'failures' => [],
        ];

        // Loop through each failure to capture details
        foreach ($report->failures()->getItems() as $failure) {
            $result['failures'][] = [
                'token' => $failure->target(),
                'error' => $failure->error()->getMessage(),
            ];
        }

        return response()->json($result);
    }

    public static function sendNotification($token, $title, $body, $iconUrl = null)
    {
        $messaging = App::make('firebase.messaging');

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body, $iconUrl));

        return $messaging->send($message);

    }
}
