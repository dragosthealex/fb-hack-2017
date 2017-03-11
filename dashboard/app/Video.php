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
}