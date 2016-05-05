<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use OAuth;

class Oauth2 extends Controller
{

    public function loginWithGoogle(Request $request){

        // get data from request
        $code = $request->get('code');

        // get google service
        $googleService = OAuth::consumer('Google');

        // check if code is valid

        // if code is provided get user data and sign in
        if (!is_null($code)) {
            // This was a callback request from google, get the token
            $token = $googleService->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);
            $user = User::where('email',$result['email'])->get()->first();
            if (isset($user['id'])) {
                if (Auth::login($user)) {
                    return redirect('/')->with(['google_err'=>'You account in Google is incorrect']);
                }
            }
        } else {
            // get googleService authorization
            $url = $googleService->getAuthorizationUri();

            // return to google login url
            return redirect((string)$url);
        }
        return redirect('/');
    }
}
