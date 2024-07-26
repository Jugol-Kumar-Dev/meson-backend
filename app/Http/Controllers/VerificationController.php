<?php

namespace App\Http\Controllers;

use App\Mail\ForgatePasswordMail;
use App\Mail\SendVerificationMail;
use App\Models\User;
use App\Notifications\SendVerificationNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('is_verified');
//    }


    public function verification(){
        return inertia("Auth/Verified",[
            'resend' => URL::route('resend.email', Auth::user()->email)
        ]);
    }

    public function refendEmail($email, $forgate = false){
        $user = User::where("email", $email)->first();

        if ($forgate){
            if ($user && $user != null){
                Mail::to($user)->send(new ForgatePasswordMail($user));
                return back()->with('success', 'Verification Mail Send Successful.');
            }else{
                return back()->with('error', 'Email Address is not valid.');
            }
        }else{
            if ($user && $user != null){
                Mail::to($user)->send(new SendVerificationMail($user));
//            Notification::sendNow($user, new SendVerificationNotification($user));
                return back()->with('success', 'Verification Mail Send Successful.');
            }else{
                return back()->with('error', 'Email Address is not valid.');
            }
        }

    }

    public function verifiedEmail(){
        $email = base64_decode(Request::input('email'));
        $user = User::where('email', $email)->first();

        if ($user && $user != null){
            $user->email_verified_at = now();
            $user->save();
            return redirect(route('dashboard'));
        }else{
            return back()->with('error', 'Email Address Not Valid');
        }
    }
}
