<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers;

   


    public function attemptLogin(Request $request)
    {
  
        $token = $this->guard()->attempt($this->credentials($request));
   
        if(! $token)
        {
            return false;
        }
        
        // Get the authenticated user
        $user = $this->guard()->user();
        
        if($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail())
        {
            
            return false;
        }
        
        // set the user's token
        $this->guard()->setToken($token);
    
        return true;
      


    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        // get token from the authentication guard (JWT)
        $token = (string)$this->guard()->getToken();

        // extract the expiry date of the token
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);

    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->user();

        if($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail())
        {
            return response()->json(['errors' => [
                'verification' => 'Ban can xac nhan tai khoan email cua ban',
            ]]);

            throw ValidationException::withMessages([
                $this->username() => 'Ma Xac thuc khong hop le',
            ]);
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Dang xuat thanh cong']);
    }
}
