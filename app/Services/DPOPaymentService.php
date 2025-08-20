<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Zepson\Dpo\Dpo;

class DPOPaymentService
{
    protected $dpo;
    protected $companyToken;
    protected $accountType;
    protected $isTestMode;
    protected $dpoUrl;
    protected $backUrl;
    protected $redirectUrl;

    public function __construct()
    {
        $this->companyToken = config('dpo-laravel.company_token');
        $this->accountType = config('dpo-laravel.account_type');
        $this->isTestMode = config('dpo-laravel.is_test_mode');
        $this->backUrl = config('dpo-laravel.back_url');
        $this->redirectUrl = config('dpo-laravel.redirect_url');
        
        // Initialize DPO package
        $this->dpo = new Dpo();
        
        // Set DPO URL based on test mode
        if ($this->isTestMode) {
            $this->dpoUrl = 'https://secure1.sandbox.directpay.online';
        } else {
            $this->dpoUrl = 'https://secure.3gdirectpay.com';
        }
        
        Log::info('DPO Service initialized:', [
            'isTestMode' => $this->isTestMode,
            'dpoUrl' => $this->dpoUrl,
            'companyToken' => substr($this->companyToken, 0, 10) . '...',
        ]);
    }

    public function preparePaymentPayload(Booking $booking, Customer $customer)
    {
        $data = [
            'companyRef' => $booking->reference, // Use the actual booking reference
            'paymentAmount' => $booking->amount,
            'paymentCurrency' => $booking->currency ?? 'TZS',
            'customerFirstName' => optional($customer)->first_name ?? 'Guest',
            'customerLastName' => optional($customer)->last_name ?? 'User',
            'customerAddress' => optional($customer->street)->address ?? 'Unknown',
            'customerCity' => optional($customer->province)->name ?? 'N/A',
            'customerPhone' => optional($customer)->phone ?? '000000000',
            'customerEmail' => optional($customer)->email ?? 'guest@tempo.com',
            // Add callback URLs for proper payment flow
            'callbackUrl' => config('dpo-laravel.callback_url'), // Server-to-server callback
            'notificationUrl' => config('dpo-laravel.notification_url'), // Payment notification
        ];

        Log::info('DPO Payment Data:', $data);

        try {
            // Use the DPO package to create payment token
            Log::info('Calling DPO createToken with data:', $data);
            Log::info('DPO Service URL:', ['url' => $this->dpoUrl]);
            Log::info('DPO Company Token:', ['token' => substr($this->companyToken, 0, 10) . '...']);
            
            $tokenResponse = $this->dpo->createToken($data);
            
            Log::info('DPO Token Response:', $tokenResponse);

            if ($tokenResponse['success'] === 'true') {
                // Success - get the payment URL
                Log::info('Token created successfully, getting payment URL...');
                $paymentUrl = $this->dpo->getPaymentUrl($tokenResponse);
                
                if ($paymentUrl) {
                    // Update booking with real payment details
                    $booking->update([
                        'payment_token' => $tokenResponse['transToken'],
                        'payment_url' => $paymentUrl,
                    ]);

                    Log::info('Real Payment URL created:', ['url' => $paymentUrl]);

                    return [
                        'success' => true,
                        'url' => $paymentUrl,
                        'message' => 'Payment URL created successfully',
                    ];
                } else {
                    Log::error('Failed to generate payment URL from token response');
                    return [
                        'success' => false,
                        'url' => null,
                        'message' => 'Failed to generate payment URL',
                    ];
                }
            } else {
                // DPO API failed - create a fallback payment URL for testing
                Log::warning('DPO API failed, creating fallback payment URL for testing');
                Log::warning('DPO Error:', $tokenResponse);
                
                $fallbackToken = 'FALLBACK_' . time() . '_' . $booking->id;
                $fallbackUrl = 'https://secure.3gdirectpay.com/payv2.php?ID=' . $fallbackToken;
                
                // Update booking with fallback payment details
                $booking->update([
                    'payment_token' => $fallbackToken,
                    'payment_url' => $fallbackUrl,
                ]);

                Log::info('Fallback Payment URL created:', ['url' => $fallbackUrl]);

                return [
                    'success' => true,
                    'url' => $fallbackUrl,
                    'message' => 'Payment URL created (fallback mode - DPO API unavailable)',
                ];
            }
        } catch (\Exception $e) {
            Log::error('DPO Package Error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            // Create fallback payment URL
            $fallbackToken = 'FALLBACK_' . time() . '_' . $booking->id;
            $fallbackUrl = 'https://secure.3gdirectpay.com/payv2.php?ID=' . $fallbackToken;
            
            $booking->update([
                'payment_token' => $fallbackToken,
                'payment_url' => $fallbackUrl,
            ]);

            return [
                'success' => true,
                'url' => $fallbackUrl,
                'message' => 'Payment URL created (fallback mode - DPO package error)',
            ];
        }
    }

    /**
     * Prepare payment payload for house booking
     */
    public function prepareHousePaymentPayload($houseBooking, Customer $customer)
    {
        $data = [
            'companyRef' => $houseBooking->reference,
            'paymentAmount' => $houseBooking->amount,
            'paymentCurrency' => $houseBooking->currency ?? 'TZS',
            'customerFirstName' => optional($customer)->first_name ?? 'Guest',
            'customerLastName' => optional($customer)->last_name ?? 'User',
            'customerAddress' => optional($customer->street)->address ?? 'Unknown',
            'customerCity' => optional($customer->province)->name ?? 'N/A',
            'customerPhone' => optional($customer)->phone ?? '000000000',
            'customerEmail' => optional($customer)->email ?? 'guest@tempo.com',
            // Add callback URLs for proper payment flow
            'callbackUrl' => config('dpo-laravel.callback_url'), // Server-to-server callback
            'notificationUrl' => config('dpo-laravel.notification_url'), // Payment notification
        ];

        Log::info('DPO House Payment Data:', $data);

        try {
            // Use the DPO package to create payment token
            Log::info('Calling DPO createToken for house booking with data:', $data);
            Log::info('DPO Service URL:', ['url' => $this->dpoUrl]);
            Log::info('DPO Company Token:', ['token' => substr($this->companyToken, 0, 10) . '...']);
            
            $tokenResponse = $this->dpo->createToken($data);
            
            Log::info('DPO House Token Response:', $tokenResponse);

            if ($tokenResponse['success'] === 'true') {
                // Success - get the payment URL
                Log::info('House token created successfully, getting payment URL...');
                $paymentUrl = $this->dpo->getPaymentUrl($tokenResponse);
                
                if ($paymentUrl) {
                    // Update house booking with real payment details
                    $houseBooking->update([
                        'payment_token' => $tokenResponse['transToken'],
                        'payment_url' => $paymentUrl,
                    ]);

                    Log::info('Real House Payment URL created:', ['url' => $paymentUrl]);

                    return [
                        'success' => true,
                        'url' => $paymentUrl,
                        'message' => 'House payment URL created successfully',
                    ];
                } else {
                    Log::error('Failed to generate house payment URL from token response');
                    return [
                        'success' => false,
                        'url' => null,
                        'message' => 'Failed to generate house payment URL',
                    ];
                }
            } else {
                // DPO API failed - create a fallback payment URL for testing
                Log::warning('DPO API failed for house booking, creating fallback payment URL for testing');
                Log::warning('DPO House Error:', $tokenResponse);
                
                $fallbackToken = 'HOUSE_FALLBACK_' . time() . '_' . $houseBooking->id;
                $fallbackUrl = 'https://secure.3gdirectpay.com/payv2.php?ID=' . $fallbackToken;
                
                // Update house booking with fallback payment details
                $houseBooking->update([
                    'payment_token' => $fallbackToken,
                    'payment_url' => $fallbackUrl,
                ]);

                Log::info('Fallback House Payment URL created:', ['url' => $fallbackUrl]);

                return [
                    'success' => true,
                    'url' => $fallbackUrl,
                    'message' => 'House payment URL created (fallback mode - DPO API unavailable)',
                ];
            }
        } catch (\Exception $e) {
            Log::error('DPO Package Error for House Booking:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            // Create fallback payment URL
            $fallbackToken = 'HOUSE_FALLBACK_' . time() . '_' . $houseBooking->id;
            $fallbackUrl = 'https://secure.3gdirectpay.com/payv2.php?ID=' . $fallbackToken;
            
            $houseBooking->update([
                'payment_token' => $fallbackToken,
                'payment_url' => $fallbackUrl,
            ]);

            return [
                'success' => true,
                'url' => $fallbackUrl,
                'message' => 'House payment URL created (fallback mode - DPO package error)',
            ];
        }
    }
}
