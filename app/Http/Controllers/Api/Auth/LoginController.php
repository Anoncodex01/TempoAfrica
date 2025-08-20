<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Version;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'fcm_token' => ['required', 'string'],
            'phone' => ['required'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            if ($request->lang == 'sw') {
                $message = 'Some field are missing';
            } else {
                $message = 'Baadhi ya taarifa hazikujazwa';
            }

            return response()->json(['success' => false, 'message' => $message,  'errors' => $validator->errors()->toArray()]);

        }
        $request->merge(['phone' => ConstantsController::formatPhone($request->phone)]);

        $data = [
            'phone' => $request->phone,
            'password' => $request->password,
            'isverified' => 1,
        ];

        // return $data;

        $customer = Customer::where('phone', $request->phone)->where('is_verified', 1)->first();

        if ($customer) {
            if (Hash::check($request->password, $customer->password)) {

                if ($customer->is_suspended) {

                    return response()->json(['success' => false, 'message' => 'This account is suspended. Please contact us for further assistance!']);

                }

                if (! $customer->is_approved) {

                    return response()->json(['success' => false, 'message' => 'This account is not verified. Please contact us for further assistance!']);

                }

                $version = Version::orderBy('id', 'DESC')->first();


                $customer->update([
                    'last_seen_at' => Carbon::now()->toDateTimeString(),
                    'fcm_token' => $request->fcm_token,
                    'device' => $request->device,
                    'is_ios' => $request->is_ios,
                    'version' => $request->version,
                    'last_login_ip' => $request->getClientIp(),
                ]);

                $token = $customer->createToken('authToken')->accessToken;

                ActivityLog::create([
                    'user_id' => 0,
                    'action' => ' A customer  '.$customer->business.' logged in successfully in App from IP '.$request->ip(),
                    'type' => 'Update',
                ]);

                $message = 'You are logged in successfully';

                return response()->json(['success' => true,  'message' => $message, 'token' => $token, 'user' => $customer], 200);

            } else {

                ActivityLog::create([
                    'user_id' => 0,
                    'action' => 'Tried to loggin unsuccessfully in App from IP '.$request->ip(),
                    'type' => 'Update',
                ]);

               $message = 'invalid_login_cridentials';

                return response()->json(['success' => false,  'message' => $message], 200);

            }
        } else {

            $customer = Customer::where('phone', $request->phone)->first();

            if ($customer) {

                $message = 'Your account is not verified, enter OTP or create new account';

                return response()->json(['success' => false,  'message' => $message], 200);
            }

            $message = 'invalid_login_cridentials';

            return response()->json(['success' => false,  'message' => $message], 200);

            return response()->json(['success' => false,  'message' => $message], 200);
        }

    }
}
