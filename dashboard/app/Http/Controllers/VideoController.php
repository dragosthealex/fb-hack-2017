<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Video;
use App\Comment;
use App\Frame;

class VideoController extends Controller
{
    public function show($id) {
        $video = Video::find($id);
        if($video->user_id != Auth::id()) {
            return redirect()->to('/');
        }

        return view('video')->with('video', $video);
    }
}