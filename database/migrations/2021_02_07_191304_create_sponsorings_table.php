<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSponsoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsorings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sponsor_id');
            $table->string('event_id', 10);

            $table->timestamps();

            $table->foreign('sponsor_id')->references('id')->on('sponsors');
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
        Schema::dropIfExists('sponsorings');
    }
}
