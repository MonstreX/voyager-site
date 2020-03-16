<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing
        Schema::create('block_regions', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('status')->default(1);
            $table->integer('order')->unsigned();
            $table->string('title')->unique();
            $table->string('key')->unique();
            $table->string('color');
            $table->timestamps();
        });


        // Create table for storing
        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('region_id')->unsigned()->nullable();
            $table->boolean('status')->default(1);
            $table->integer('order')->unsigned()->nullable();
            $table->string('title')->unique();
            $table->string('key')->unique();
            $table->text('content')->nullable();
            $table->string('images')->nullable();
            $table->text('urls')->nullable();
            $table->integer('rules')->unsigned()->nullable();
            $table->text('details')->nullable();
            $table->timestamps();

            $table->foreign('region_id')->references('id')->on('block_regions')
                ->onUpdate('cascade')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('blocks');
        Schema::drop('blocks_regions');
    }
}
