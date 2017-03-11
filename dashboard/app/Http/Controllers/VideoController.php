<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Video;
use App\Comment;
use App\Frame;

class HooksController extends Controller
{
    public function test_live_video() {
        if(!Auth::check()) {
            return redirect()->to('/');
        }
    }
}