<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSentimentAnalysisComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->longtext('keywords')->nullable();
            $table->string('score')->nullable();
            $table->string('negative')->nullable();
            $table->string('neutral')->nullable();
            $table->string('positive')->nullable();
            $table->string('compound')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('keywords');
            $table->dropColumn('score');
            $table->dropColumn('negative');
            $table->dropColumn('neutral');
            $table->dropColumn('positive');
            $table->dropColumn('compound');
        });
    }
}
