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
        if(!($c = count($this->comments()))) {
            return 'N/A';
        }
        $sum = 0;
        foreach($this->comments as $comm) {
            $sum += floatval($comm->score);
        }
        return $sum*100 / (float)$c;
    }
}