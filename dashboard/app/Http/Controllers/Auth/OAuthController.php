<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialize;

class OAuthController extends Controller {

    public function redirectToProvider($provider) {
        return Socialize::with($provider)->redirect();
    }

    public function handleProviderCallback($provider) {
        $user = Socialize::with($provider)->user();
        echo $user->token;
    }

    public function success($provider) {
        # Pass
    }
}