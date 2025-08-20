<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class SignInController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

    public function index()
    {
        if (Auth::guard('user')->check()) {
            return redirect('/dashboard');
        }

        return view('login');
    }

    public function signIn(Request $request)
    {
        // Validate the request
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()->toArray(),
                'message' => 'Some fields are missing or incorrect',
            ]);
        }

        // Attempt authentication
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => true,
                'location' => url('/dashboard'), // URL to redirect after login
            ]);
        } else {
            return response()->json([
                'success' => false,
                'errors' => ['password' => ['Invalid Email or Password']],
                'message' => 'Invalid credentials',
            ]);
        }
    }
}
