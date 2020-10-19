<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;

// use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    // use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    public function verify(Request $request, User $user)
    {


        // check if the url is a valid signed url
        if(! URL::hasValidSignature($request))
        {
            return response()->json([
                "errors" => ["message" => "Link xac nhan khong hop le"]
            ], 422);
        }

        // check if the user as already verified account
        if($user->hasVerifiedEmail())
        {
            return response()->json([
                "errors" => ["message" => "Dia chi email da duoc xac nhan"]
            ], 422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email da duoc xac nhan thanh cong'], 200);
    }

    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required'],

        ]);

        $user = User::where('email', $request->email)->first();

        if(! $user)
        {
            return response()->json(['errors' => [
                'email' => 'Khong tim thay nguoi dung co dia chi email nay'
            ]] , 422);
        }

        if($user->hasVerifiedEmail())
        {
            return response()->json([
                "errors" => ["message" => "Dia chi email da duoc xac nhan roi"]
            ], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => 'Link xac nhan da duoc gui di']);

    }
}
