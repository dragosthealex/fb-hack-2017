<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HooksController extends Controller
{
    public function test_live_video() {
        if(!Auth::check()) {
            return redirect()->to('/');
        }
        // Try script
        echo "muie";
        $user = Auth::user();
        $token = $user->fb_token;
        $cmd = "python ../../src/listner.py " . $token;
        $parsed = json_decode(shell_exec($cmd), 1);
        
    }

}