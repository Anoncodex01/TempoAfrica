<?php

return [
    "company_token" => env("DPO_COMPANY_TOKEN", "B3F59BE7-0756-420E-BB88-1D98E7A6B040"),
    "account_type" => env("DPO_ACCOUNT_TYPE", "54841"),
    'is_test_mode' => env("DPO_IS_TEST_MODE", true),
    "back_url" => env("DPO_BACK_URL", "https://api-tempoafrica-mobile-v1.tempoapplication.com/api/v1/dpo/payment-cancel"),
    "redirect_url" => env("DPO_REDIRECT_URL", "https://api-tempoafrica-mobile-v1.tempoapplication.com/api/v1/dpo/payment-success"),
    "callback_url" => env("DPO_CALLBACK_URL", "https://api-tempoafrica-mobile-v1.tempoapplication.com/api/v1/dpo/payment-callback"),
    "notification_url" => env("DPO_NOTIFICATION_URL", "https://api-tempoafrica-mobile-v1.tempoapplication.com/api/v1/dpo/payment-notification")
];
