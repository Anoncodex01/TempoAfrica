<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthenticationOtp;
use App\Models\Customer;
use App\Services\NotificationService;
use App\Services\UtilService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Merge formatted phone number into the request
            $request->merge(['phone' => UtilService::formatPhone($request->phone)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
        
        // Validation rules
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
            'phone' => ['required', 'regex:/^255[0-9]{9}$/'], // Must start with 255 and have 12 digits total
        ]);

        // Handle validation failures
        if ($validator->fails()) {
            $message = 'Phone number is required and must be in correct format (e.g., 756xxxxxxxx)';

            return $this->jsonResponse(false, $message, $validator->errors()->toArray());
        }

        // Check if the phone number is already registered and verified
        $customer = Customer::where('phone', $request->phone)->first();

        if ($customer && $customer->is_verified) {
            $message = 'Phone number is already taken';

            return response()->json(['success' => false, 'message' => $message]);
        }

        // Create new customer if not found
        $customer = $customer ?: new Customer;

        $otp = UtilService::getOtp();

        // Remove SMS limit check for now
        // $recentOtpCount = AuthenticationOtp::where('phone', $request->phone)
        //     ->where('sent_at', '>=', Carbon::now()->subMinutes(30))
        //     ->count();

        // if ($recentOtpCount >= 3) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You have reached the maximum number of OTP requests. Please try again later.',
        //     ], 429); // Too Many Requests
        // }

        $this->sendOtp($otp, $request->phone);

        // Populate customer fields
        $customer->fill([
            'fcm_token' => $request->fcm_token,
            'phone' => $request->phone,
        ]);

        // Save customer and handle failure
        if (! $customer->save()) {
            $message = 'Sory Could not createa an account!';

            return response()->json(['success' => false, 'message' => $message]);
        }

        $customer->save();

        $message = 'Account created successfully';

        return $this->jsonResponse(true, $message, null);

    }

    public function login(Request $request)
    {
        try {
            // Merge formatted phone number into the request
            $request->merge(['phone' => UtilService::formatPhone($request->phone)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
        
        // Validation rules
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
            'phone' => ['required', 'regex:/^255[0-9]{9}$/'], // Must start with 255 and have 12 digits total
        ]);

        // Handle validation failures
        if ($validator->fails()) {
            $message = 'Phone number is required and must be in correct format (e.g., 756xxxxxxxx)';

            return $this->jsonResponse(false, $message, $validator->errors()->toArray());
        }
        // Check if the phone number is already registered and verified
        $customer = Customer::where('phone', $request->phone)->first();

        if (! $customer) {
            $message = 'No account associated with this phone number';

            return response()->json(['success' => false, 'message' => $message], 200);
        }

        $otp = UtilService::getOtp();

        // Remove SMS limit check for now
        // $recentOtpCount = AuthenticationOtp::where('phone', $request->phone)
        //     ->where('sent_at', '>=', Carbon::now()->subMinutes(30))
        //     ->count();

        // if ($recentOtpCount >= 3) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You have reached the maximum number of OTP requests. Please try again later.',
        //     ], 429); // Too Many Requests
        // }

        $this->sendOtp($otp, $request->phone);

        // Populate customer fields
        $customer->update([
            'login_attempts' => $customer->login_attempts + 1,
            'last_login_ip' => $request->ip(),
            'last_login_at' => now(),
        ]);

        $maskedPhone = str_repeat('*', 6).substr($customer->phone, -3);
        $message = "Login initiated. Enter OTP sent to $maskedPhone.";

        return $this->jsonResponse(true, $message, null);

    }

    public function sendOtp($otp, $phone)
    {

        // Proceed to generate and store OTP
        AuthenticationOtp::create([
            'phone' => $phone,
            'otp' => $otp,
            'sent_at' => now(),
        ]);
        // Send SMS verification
        $body = '<#> TEMPO AFRICA: Your verification code is '.$otp."\n3VHuD4CK7s0";
        if (! app()->environment('production')) {
            $response = (new NotificationService)->sendSMS($body, [$phone]);
        }

    }

    public function otpVerification(Request $request)
    {
        try {
            $request['phone'] = UtilService::formatPhone($request->phone);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        $validator = Validator::make($request->all(), [
            'otp' => ['required', 'regex:/^[0-9]{6,}$/'],
            'phone' => ['required', 'regex:/^255[0-9]{9}$/'], // Must start with 255 and have 12 digits total
        ]);

        if ($validator->fails()) {
            $message = 'Some details are missing!';

            return $this->jsonResponse(false, $message, $validator->errors()->toArray());
        }

        $customer = Customer::where('phone', $request->phone)->first();

        // Check if customer exists
        if (! $customer) {
            $message = 'No account associated with this number';

            return response()->json(['success' => false, 'message' => $message], 200);
        }

        $authenticationOtp = AuthenticationOtp::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->orderBy('id', 'DESC')->first();

        if (! $authenticationOtp) {
            $message = 'Verification code is invalid please double check';

            return response()->json(['success' => false, 'message' => $message], 200);
        }

        // Check if OTP is expired
        $minutesSinceSent = Carbon::now()->diffInMinutes(Carbon::parse($authenticationOtp->sent_at));

        if ($minutesSinceSent > 30) {
            $message = 'Verification code is expired please request another code';

            return response()->json(['success' => false, 'message' => $message], 200);
        }

        // if ($authenticationOtp->is_used) {
        //     $message = 'Verification code has already been used!';

        //     return response()->json(['success' => false, 'message' => $message], 200);
        // }

        // Use transaction for critical database operations
        DB::beginTransaction();
        try {

            $authenticationOtp->update([
                'is_used' => true,
                'used_at' => now(),
            ]);

            $ip = $request->ip();

            $data = [
                'login_attempts' => 0,
                'last_login_ip' => $ip,
                'last_login_at' => now(),
            ];

            if (! $customer->is_verified) {
                $data['is_verified'] = true;
                $data['phone_verified_at'] = now();

                \Log::info('Verifying customer phone', [
                    'customer_id' => $customer->id,
                    'phone' => $customer->phone,
                    'update_data' => $data
                ]);

                $body = 'Your account has been created successfully. Continue enjoying your bookings without limitations. In case on any challange do not hesitate to check us';
                $response = (new NotificationService)->sendSMS($body, [$customer->phone]);
                $message = 'Account verified successfully';

            } else {
                $message = 'Logged insuccessfully';

            }
            
            $customer->update($data);
            
            \Log::info('Customer updated after OTP verification', [
                'customer_id' => $customer->id,
                'is_verified' => $customer->is_verified,
                'phone_verified_at' => $customer->phone_verified_at
            ]);
            DB::commit();
            $token = $customer->createToken('CustomerToken')->accessToken;

            return response()->json(['success' => true, 'message' => $message, 'token' => $token, 'customer' => $customer], 200);

        } catch (\Exception $e) {
            // Roll back the transaction on error
            DB::rollback();
            $message = 'Phone number not verified please try again later';

            return response()->json(['success' => false, 'message' => $message, 'error' => $e->getMessage()], 500);
        }
    }

    public function otp_send(Request $request)
    {
        // Format the phone number first
        $request['phone'] = UtilService::formatPhone($request->phone);

        // Start transaction
        DB::beginTransaction();

        try {
            // Retrieve the customer based on the formatted phone number
            $customer = Customer::where('phone', $request->phone)->first();

            // Handle case where the customer is not found
            if (! $customer) {
                return response()->json(['success' => false, 'message' => 'No account associated with this number'], 200);
            }

            // Remove SMS limit checks for now
            // // Check the time difference from the last sent OTP
            // $minutes = Carbon::now()->diffInMinutes(Carbon::parse($customer->sent_at));
            // if ($minutes <= 5) {
            //     return response()->json(['success' => false, 'message' => 'Please wait at least five minutes before requesting another code'], 200);
            // }

            // // Check if the maximum number of OTP sends has been exceeded
            // if ($customer->sent_count >= 3) {
            //     return response()->json(['success' => false, 'message' => 'Too many verification codes sent'], 200);
            // }

            // Generate an OTP
            $otp = UtilService::getOtp();

            // Update customer record with new OTP and increment send count
            $customer->otp = $otp;
            $customer->sent_count++;
            $customer->sent_at = Carbon::now()->format('Y-m-d H:i:s');
            $customer->save();

            // Prepare the message
            $body = '<#> Dawa Mkononi: Your verification code is '.$otp."\n3VHuD4CK7s0";
            $messageArray[] = [
                'recipients_phone' => [$request->phone],
                'body' => $body,
            ];

            // Send the SMS
            SmsApiController::sendSMS($messageArray);

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json(['success' => true, 'message' => 'Verification code sent successfully'], 200);

        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();

            // Log error or handle it as needed
            return response()->json(['success' => false, 'message' => 'Failed to send verification code: '.$e->getMessage()], 500);
        }
    }

    public function jsonResponse($status, $message, $errors = null)
    {
        return response()->json(['success' => $status, 'message' => $message, 'errors' => $errors]);
    }
}
