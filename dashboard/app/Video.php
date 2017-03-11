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
            $total += $frame->view_count;
        }
        return $total;
    }
}