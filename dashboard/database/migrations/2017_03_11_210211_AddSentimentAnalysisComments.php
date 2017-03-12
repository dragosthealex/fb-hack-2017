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
            $table->float('score')->nullable();
            $table->float('negative')->nullable();
            $table->float('neutral')->nullable();
            $table->float('positive')->nullable();
            $table->float('compound')->nullable();
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
