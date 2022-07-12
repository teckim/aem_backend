<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->string('id', 10);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('location_id');

            $table->string('title', 100);
            $table->string('slug', 150)->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamp('publish_at')->useCurrent();
            $table->timestamp('unpublish_at')->nullable();
            $table->integer('tickets_count');
            $table->boolean('suspended')->default(false);
            $table->integer('price')->nullable();
            
            $table->timestamps();
            $table->primary('id');
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}