<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgnizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orgnizes', function (Blueprint $table) {
            $table->id();

            $table->string('team_id', 10);
            $table->string('event_id', 10);
            $table->boolean('confirmed');

            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('team_id')->references('id')->on('teams');
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
        Schema::dropIfExists('orgnizes');
    }
}
