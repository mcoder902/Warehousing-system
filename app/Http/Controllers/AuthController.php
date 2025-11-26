<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if(!Auth::guest())
        {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string'
        ]);

        if (Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password])) {
            $request->session()->regenerate(); // جلوگیری از Session Fixation (نکته امنیتی)

            if ($request->wantsJson())
            {
                return response()->json([
                    'message' => 'ورود موفقیت‌آمیز بود.',
                    'redirect_url' => route('dashboard') // آدرس داشبورد را به فرانت می‌فرستیم
                ], 200);
            }

            return redirect()->intended('dashboard');
        }

        if ($request->wantsJson())
        {
            return response()->json([
                'errors' => [
                    'phone_number' => ['شماره تلفن یا رمز عبور اشتباه است.']
                ]
            ], 422);
        }

        return back()->withErrors([
            'phone_number' => 'شماره تلفن یا رمز عبور اشتباه است.',
        ])->onlyInput('phone_number');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }
}
