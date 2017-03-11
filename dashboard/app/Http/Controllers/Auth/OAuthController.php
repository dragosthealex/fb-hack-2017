<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Socialize;
use App\User;

class OAuthController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function redirectToProvider($provider) {
        return Socialize::driver($provider)
                    ->fields(['first_name', 'last_name', 'email'])
                    ->scopes(['user_videos'])
                    ->redirect();
    }

    public function handleProviderCallback($provider) {
        $fb_user = Socialize::with($provider)
                    ->fields(['first_name', 'last_name', 'email'])->user();
        $token = $fb_user->token;
        $fb_user = $fb_user->user;
        $user = User::where('fb_id', $fb_user["id"])->first();
        if(!$user) {
            $user = new User();
            $user->name = $fb_user["first_name"] . ' ' . $fb_user["last_name"];
            $user->email = $fb_user["email"];
            $user->fb_token = $token;
            $user->fb_id = $fb_user["id"];
            $user->password = bcrypt(str_random(40));
            $user->save();
        }
        Auth::login($user);
        return redirect('/home');
    }

    public function success($provider) {
        # Pass
    }
}