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
            echo "There was an error. Please make sure you are live-streaming from Facebook.";
            echo "<br>";
            echo "Go <a href='" . url('/') . "'>back</a> and try again.";
            exit();
        }

        $video = new Video();
        $video->user_id = $user->id;
        $video->fb_id = $parsed["video_id"];
        $video->description = $parsed["description"];
        $video->start_timestamp = $parsed["creation"];
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
        $ch = curl_init();
        $url = "https://graph.facebook.com/v2.8/" . $live_vid_id . "?fields=source&access_token=" . $user->fb_token;
        echo $url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $v = json_decode(curl_exec($ch), 1);
        while(!isset($v["source"])) {
            sleep(3);
            $v = json_decode(curl_exec($ch), 1);
        }
        $source = $v["source"];
        curl_close($ch);

        $video->url = $source;
        $video->keywords = json_encode([]);
        $video->save();

        // Make user dir if not exists
        if(!is_dir('../../videos')) {
            mkdir('../../videos');
        }
        if(!is_dir('../../videos/' . $user->id)) {
            mkdir('../../videos/' . $user->id);
        }
        // Download video there
        file_put_contents('../../videos/' . $user->id . '/' . $video->fb_id . '.mp4', file_get_contents($source));

        // Do MS stuff
        $cmd = "python ../../src/mscaller.py " . $video->fb_id;
        $res = shell_exec($cmd);
        var_dump($res);
        $parsed = json_decode($res, 1);
        foreach($parsed as $comm) {
            $db_comm = $video->comments()->where('timestamp', $comm['id'])->firstOrFail();
            $db_comm->keywords = json_encode($comm["keyPhrases"]);
            $db_comm->score = $comm["sentiment"];
            $db_comm->negative = $comm["vader"]["neg"];
            $db_comm->positive = $comm["vader"]["pos"];
            $db_comm->neutral = $comm["vader"]["neu"];
            $db_comm->compound = $comm["vader"]["compound"];
            $db_comm->save();
        }

        // Do ffmpeg
        $cmd = "python ../../src/vision.py " . Auth::user() . ' ' . $video->fb_id;
        $res = shell_exec($cmd);
        var_dump($res);
        $parsed = json_decode($res, 1);
        if($parsed) {
            $kw = [];
            foreach($parsed as $objects) {
                foreach($objects as $obj) {
                    array_push($kw, $obj["name"]);
                }
            }
            $video->keywords = json_encode($kw);
            $video->save();
        }


        // Go to videos
        return redirect()->to('/videos/' . $video->id);
    }


    public function live(Request $request) {
	$result = $request->all();
	$f = fopen("test.txt", "a");
	fwrite($f, json_encode($result));
	fclose($f);
    }
}
