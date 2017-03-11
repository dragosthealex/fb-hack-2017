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
        $token = $user->fb_token;
        $cmd = "python ../../src/listner.py " . $token;
        while (@ ob_end_flush()); // end all output buffers if any
        $proc = popen($cmd, 'r');
        echo '<pre>';
        while (!feof($proc))
        {
            echo fread($proc, 4096);
            @ flush();
        }
        echo '</pre>';
    }

}