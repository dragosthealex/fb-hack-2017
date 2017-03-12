<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fb_id');
            $table->text('description')->nullable();
            $table->unsignedInteger('user_id');
            $table->text('url')->nullable();
            $table->string('started')->nullable();
            $table->string('finished')->nullable();
            $table->timestamps();
        });
        Schema::create('frames', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('video_id');
            $table->bigInteger('timestamp');
            $table->integer('view_count');
            $table->longtext('reactions');
            $table->string('block_id');
            $table->timestamps();
        });
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fb_id');
            $table->unsignedInteger('video_id');
            $table->bigInteger('timestamp');
            $table->string('user_fb_id');
            $table->string('user_fb_name');
            $table->longtext('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::defaultStringLength(191);
        Schema::drop('videos');
        Schema::drop('frames');
        Schema::drop('comments');
    }
}
