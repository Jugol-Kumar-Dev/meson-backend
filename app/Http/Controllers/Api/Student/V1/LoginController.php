<?php

namespace App\Http\Controllers\Api\Student\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();
        $user->token = $user->createToken($request->header('sec-ch-ua-platform') ?? 'student')->plainTextToken;
        return request()->acceptsJson() ? $user : redirect()->intended(RouteServiceProvider::HOME);
    }

    public function signup(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        User::create($data);
        return response()->json('Registraiton Successfully Done...');

    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json('logout successfully done...');

    }

}

