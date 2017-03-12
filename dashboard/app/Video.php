<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'videos';

    public function frames() {
        return $this->hasMany('App\Frame', 'video_id', 'id');
    }

    public function comments() {
        return $this->hasMany('App\Comment', 'video_id', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function get_total_reactions() {
        $total = 0;
        $frames = $this->frames;
        foreach($frames as $frame) {
            $total += count(json_decode($frame->reactions, 1));
        }
        return $total;
    }

    public function get_total_views() {
        $total = 0;
        $frames = $this->frames;
        foreach($frames as $frame) {
            if($frame->view_count > $total) {
                $total = $frame->view_count;
            }
        }
        return $total;
    }

    public function get_avg_sentiment() {
        if(!($c = count($this->comments))) {
            return 'N/A';
        }
        $sum = 0;
        foreach($this->comments as $comm) {
            $sum += floatval($comm->score);
        }
        return $sum*100 / (float)$c;
    }

    public function get_info_by_frames() {
        $res = [];
        $zero = (int)$this->frames()->orderBy('timestamp', 'ASC')->first()->timestamp;
        $previous = $zero;
        $zero = $this->frames()->orderBy('timestamp', 'ASC')->get();
        $zero = (int)$this->start_timestamp;
        foreach($this->frames()->orderBy('timestamp', 'ASC')->get() as $key => $frame) {
            $obj = [];
            $obj["frame"] = (int)$frame["timestamp"] - $zero;
            $obj["view_count"] = (int)$frame["view_count"];
            $obj["like"] = 0;
            $obj["haha"] = 0;
            $obj["wow"] = 0;
            $obj["love"] = 0;
            $obj["angry"] = 0;
            $obj["sad"] = 0;
            $reactions = json_decode($frame["reactions"], 1);
            foreach($reactions as $r) {
                $obj[strtolower($r["type"])]++;
            }
            $comms = $this->comments()->where('timestamp', '<=', $frame->timestamp)
                                        ->where('timestamp', '>=', $previous);
            $obj["comment_count"] = $comms->count();
            $tp = 0.0;
            $tneu = 0.0;
            $tneg = 0.0;
            $tc = 0.0;
            foreach ($comms->get() as $key => $comm) {
                $tp += $comm->positive;
                $tneu += $comm->neutral;
                $tneg += $comm->negative;
                $tc += $comm->compound;
            }
            if($obj["comment_count"] > 0) {
                $obj["positive"] = 100*$tp / (float)$obj["comment_count"];
                $obj["neutral"] = 100*$tneu / (float)$obj["comment_count"];
                $obj["negative"] = 100*$tneg / (float)$obj["comment_count"];
                $obj["trend"] = $tc / (float)$obj["comment_count"];
            }
            else {
                $obj["positive"] = 0;
                $obj["neutral"] = 0;
                $obj["negative"] = 0;
                $obj["trend"] = 0;
            }
            array_push($res, $obj);

            $previous = $frame->timestamp;
        }
        array_shift($res);
        return json_encode($res);
    }
}