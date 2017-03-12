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
                    ->scopes(['user_videos', 'pages_messaging'])
                    ->redirect();
    }

    public function handleProviderCallback($provider) {
        $fb_user = Socialize::with($provider)
                    ->fields(['first_name', 'last_name', 'email'])->user();
        $token = $fb_user->token;
        // Get long lived token
        $ch = curl_init();
        $url = "https://graph.facebook.com/v2.8/oauth/access_token?grant_type=fb_exchange_token&client_id=" . env('FB_APP_ID') . "&client_secret=" . env('FB_APP_SECRET') . "&fb_exchange_token=" . $token . "&redirect_uri=" . urlencode(env('APP_URL'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = json_decode(curl_exec($ch), 1);
        curl_close($ch);
        if(!isset($result["access_token"])) {
            echo "There was an error.";
        }
        $token = $result["access_token"];
        // Try to login
        $fb_user = $fb_user->user;
        $user = User::where('fb_id', $fb_user["id"])->first();
        if(!$user) {
            $user = new User();
            $user->name = $fb_user["first_name"] . ' ' . $fb_user["last_name"];
            $user->email = $fb_user["email"];
            $user->fb_id = $fb_user["id"];
            $user->password = bcrypt(str_random(40));
        }
        $user->fb_token = $token;
        $user->save();
        Auth::login($user);
        return redirect()->to('/home');
    }

    public function success($provider) {
        # Pass
    }
}