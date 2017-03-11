<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'frames';

    public function video() {
        return $this->belongsTo('App\Video', 'video_id');
    }

}