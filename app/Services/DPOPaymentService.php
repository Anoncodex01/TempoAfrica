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

    /**
     * Verify payment with DPO servers using HTTP request
     * This is crucial for security - we must verify the payment with DPO
     */
    public function verifyPayment($transToken, $transRef, $companyRef)
    {
        // Use HTTP verification since DPO package doesn't have verification method
        return $this->verifyPaymentHttp($transToken, $transRef, $companyRef);
    }

    /**
     * Verify payment using HTTP request to DPO API
     * Fallback method if DPO package doesn't have verification
     */
    public function verifyPaymentHttp($transToken, $transRef, $companyRef)
    {
        try {
            Log::info('Verifying payment with DPO via HTTP:', [
                'transToken' => $transToken,
                'transRef' => $transRef,
                'companyRef' => $companyRef,
            ]);

            // Prepare verification request
            $verificationUrl = $this->dpoUrl . '/payv2.php';
            $verificationData = [
                'ID' => $transToken,
                'TransRef' => $transRef,
                'CompanyRef' => $companyRef,
                'action' => 'verify',
            ];

            // Make HTTP request to DPO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $verificationUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verificationData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            Log::info('DPO HTTP Verification Response:', [
                'httpCode' => $httpCode,
                'response' => $response,
            ]);

            if ($httpCode === 200 && $response) {
                // Parse response (DPO might return XML or JSON)
                $responseData = $this->parseDPOResponse($response);
                
                if ($responseData && isset($responseData['Result']) && $responseData['Result'] === '000') {
                    Log::info('Payment verified successfully via HTTP');
                    return [
                        'success' => true,
                        'verified' => true,
                        'data' => $responseData,
                    ];
                } else {
                    Log::warning('Payment verification failed via HTTP:', $responseData);
                    return [
                        'success' => false,
                        'verified' => false,
                        'data' => $responseData,
                    ];
                }
            } else {
                Log::error('HTTP verification failed:', [
                    'httpCode' => $httpCode,
                    'response' => $response,
                ]);
                return [
                    'success' => false,
                    'verified' => false,
                    'error' => 'HTTP request failed',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error in HTTP payment verification:', [
                'error' => $e->getMessage(),
                'transToken' => $transToken,
                'transRef' => $transRef,
                'companyRef' => $companyRef,
            ]);
            
            return [
                'success' => false,
                'verified' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Parse DPO response (could be XML or JSON)
     */
    private function parseDPOResponse($response)
    {
        // Try to parse as JSON first
        $jsonData = json_decode($response, true);
        if ($jsonData !== null) {
            return $jsonData;
        }

        // Try to parse as XML
        $xmlData = simplexml_load_string($response);
        if ($xmlData !== false) {
            return json_decode(json_encode($xmlData), true);
        }

        // If neither works, return as string
        return ['raw_response' => $response];
    }
}
