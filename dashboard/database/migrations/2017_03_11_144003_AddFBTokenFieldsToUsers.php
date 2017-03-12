<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFBTokenFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::table('users', function (Blueprint $table) {
            $table->string('fb_token')->unique();
            $table->string('fb_id')->unique();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fb_token');
            $table->dropColumn('fb_id');
        });
    }
}
