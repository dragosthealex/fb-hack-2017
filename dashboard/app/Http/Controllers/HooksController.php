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

        // Try script
        set_time_limit (1000000);
        $user = Auth::user();
        $token = $user->fb_token;
        $cmd = "python ../../src/listner.py " . $token;
        $parsed = json_decode(shell_exec($cmd), 1);

        if(count($parsed["blockchain"]) == 0) {
            die("sugi cucu");
        }

        $video = new Video();
        $video->user_id = $user->id;
        $video->fb_id = $parsed["video_id"];
        $video->description = $parsed["description"];
        $video->save();

        foreach($parsed["blockchain"] as $block) {
            $frame = new Frame();
            $frame->video_id = $video->id;
            $frame->timestamp = $block["timestamp"]; 
            $frame->view_count = $block["view_count"];
            $frame->reactions = json_encode($block["reactions"]);
            $frame->block_id = $block["block_id"];
            $frame->save();
        }

        foreach ($parsed["comments"] as $comm) {
            $comment = new Comment();
            $comment->video_id = $video->id;
            $comment->timestamp = $comm["created_time"];
            $comment->message = $comm["message"];
            $comment->user_fb_id = $comm["from"]["id"];
            $comment->user_fb_name = $comm["from"]["name"];
            $comment->fb_id = $comm["id"];
            $comment->save();
        }

        // Get id of video inside the live video
        $ch = curl_init();
        $url = "https://graph.facebook.com/v2.8/" . $video->fb_id . "?fields=video&access_token=" . $user->fb_token;
        echo $url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $live_vid_id = json_decode(curl_exec($ch), 1)["video"]["id"];
        curl_close($ch);
        // Get the source of video
        sleep(10);
        $ch = curl_init();
        $url = "https://graph.facebook.com/v2.8/" . $live_vid_id . "?fields=source&access_token=" . $user->fb_token;
        echo $url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $source = json_decode(curl_exec($ch), 1)["source"];
        curl_close($ch);

        $video->url = $source;
        $video->save();

        // Make user dir if not exists
        if(!is_dir('../../videos')) {
            mkdir('../../videos');
        }
        if(!is_dir('../../videos/' . $user->id)) {
            mkdir('../../videos/' . $user->id);
        }
        // Download video there
        file_put_contents('../../videos/' . $user->id . '/' . $video->fb_id + '.mp4', file_get_contents($source));

        // Go to videos
        return redirect()->to('/videos/' . $video->id);
    }
}