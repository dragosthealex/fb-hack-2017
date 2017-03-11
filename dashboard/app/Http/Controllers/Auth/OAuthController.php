<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialize;
use User;

class OAuthController extends Controller {

    public function redirectToProvider($provider) {
        return Socialize::driver($provider)
                    ->fields(['first_name', 'last_name', 'email', 'gender', 'birthday',
                              'live_videos', 'videos'])
                    ->scopes(['user_videos'])
                    ->redirect();
    }

    public function handleProviderCallback($provider) {
        $fb_user = Socialize::with($provider)
                    ->fields(['first_name', 'last_name', 'email', 'gender', 'birthday',
                              'live_videos', 'videos'])->user();
        $token = $fb_user->token;
        $user = User::where('fb_token', $token)->first();
        if(!$user) {
            $user = new User();
            
        }
    }

    public function success($provider) {
        # Pass
    }
}