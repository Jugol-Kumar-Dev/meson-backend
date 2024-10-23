<?php

namespace App\Http\Controllers\Api\Student\V1;


use App\Http\Controllers\Controller;
use App\Http\Controllers\VerificationController;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\ForgatePasswordMail;
use App\Mail\SendVerificationMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
        if(!$user->is_active){
            throw ValidationException::withMessages([
                'email' => "Your Account Is Deactive. Please Contact Administrator.",
            ]);
        }

        $user->tokens()->delete();
        $user->token = $user->createToken($request->header('sec-ch-ua-platform') ?? 'student')->plainTextToken;
        return request()->acceptsJson() ? $user : redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * @throws ValidationException
     */
    public function signup(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $user = User::create($data);
        if(!$user->is_active){
            throw ValidationException::withMessages([
                'email' => "Your Account Is Deactive. Please Contact Administrator.",
            ]);
        }

        $verification = new VerificationController();
        $verification->refendEmail($user->email);


        $user->token = $user->createToken($request->header('sec-ch-ua-platform') ?? 'student')->plainTextToken;
        return request()->acceptsJson() ? $user : redirect()->intended(RouteServiceProvider::HOME);

    }

    public function updateProfile(Request $request)
    {


        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email'
        ]);

        $user = User::query()->findOrFail(Auth::id());
        if($name = $request->input('name')){
            $user->name = $name;
        }

        $email = $request->input('email');
        if ($email !== null) {
            $user->email = $email;
        }
        if($request->hasFile('image')){
            $user->image = $request->file('image')->store('/profile');
            if(Storage::disk('public')->exists($user->image)){
                Storage::disk('public')->delete($user->image);
            }
        }

        $user->save();
        return response()->json([
            'message' => 'Your Profile has been Updated...'
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function updatePassword(Request $request)
    {
        $user = User::query()->findOrFail(Auth::id());
        $hashedPassword = $user->password;

        $request->validate([
            'current_password' => 'required',
            'new_password'=> 'required|min:6',
            'confirm_password' => 'required|min:6|same:new_password',
        ]);

        if (Hash::check($request->input('current_password'), $hashedPassword)) {
            if (!Hash::check($request->input("new_password"), $hashedPassword)) {
                $user->update([
                    'password' =>$request->input("new_password")
                ]);
                return response()->json(['message' => "New Password Updated.."]);
            } else {
                throw ValidationException::withMessages(['new_password' => "New Password Can Not Be Same As Same Password"]);
            }
        } else {
            throw ValidationException::withMessages(['current_password' => "Current Password Not Match"]);
        }
    }


    public function sendForgotPasswordReqs(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')]
        ]);


        $user = User::where('email', \Illuminate\Support\Facades\Request::input('email'))->first();

        if($user != null){
            if ($user && $user != null){
                Mail::to($user)->send(new ForgatePasswordMail($user));
                return \response()->json('Resend Password Mail Send Successfully Done !', 200);
            }else{
                return \response()->json('Your Email Address Not Valid...!', 404);
            }
        }else{
            return \response()->json('Your Email Address Not Valid...!', 404);
        }

    }
    public function saveNewChangedPassword(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'min:6|required_with:confirm_password|same:confirm_password',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        $user->password = $request->input('password');
        $user->update();

        return response()->json('Password Change Successfully Done...', 200);
    }
    public function refendEmail($email, $forgate = false){
        $user = User::where("email", $email)->first();

        if ($forgate){
            if ($user && $user != null){
                Mail::to($user)->send(new ForgatePasswordMail($user));
                return \response()->json(['message' => 'Verify email resend done...'], 404);
            }else{
                return \response()->json(['message' => 'Email Address is not valid...'], 200);
            }
        }else{
            if ($user && $user != null){
                Mail::to($user)->send(new SendVerificationMail($user));
//            Notification::sendNow($user, new SendVerificationNotification($user));
                return \response()->json(['message' => 'Verify email resend done...'], 200);
            }else{
//                return back()->with('error', 'Email Address is not valid.');
                return \response()->json(['message' => 'Email Address is not valid...'], 404);

            }
        }

    }
    public function verifiedEmail(){
        $email = base64_decode(\Illuminate\Support\Facades\Request::input('email'));
        $user = User::where('email', $email)->first();

        if ($user && $user != null){
            $user->email_verified_at = now();
            $user->save();
            $url = env("FRONTEND_URL")."/verification-success";
            return redirect($url);
        }else{
            return \response()->json(['message' => 'Email Address is not valid...'], 404);
        }
    }

    public function checkForgotPassword(){
        $email = base64_decode(\request()->input("_token"));

        $user = User::where('email', $email)->first();

        if ($user && $user != null){
            $url = env('FRONTEND_URL')."/new-password?email=$email";
            return redirect($url);
        }else{
            return \response()->json(['message' => 'Email Address is not valid...'], 404);
        }
    }



    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json('logout successfully done...');

    }

}

