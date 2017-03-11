<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialize;

class OAuthController extends Controller {

    public function redirectToProvider($provider) {
        return Socialize::driver($provider)
                    ->fields(['first_name', 'last_name', 'email', 'gender', 'birthday',
                              'live_videos', 'videos'])
                    ->scopes(['user_videos'])
                    ->redirect();
    }

    public function handleProviderCallback($provider) {
        $user = Socialize::with($provider)->user();
        echo $user->token;
        echo $user->videos;
    }

    public function success($provider) {
        # Pass
    }
}