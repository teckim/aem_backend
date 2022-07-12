<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpeakingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speakings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('speaker_id');
            $table->string('event_id', 10);
            $table->string('subject', 100);
            
            $table->timestamps();

            $table->foreign('speaker_id')->references('id')->on('speakers');
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('speakings');
    }
}
