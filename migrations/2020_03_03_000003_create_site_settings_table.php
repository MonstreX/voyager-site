<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Create table for storing
        Schema::create('site_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->unsigned();
            $table->string('title')->unique();
            $table->string('key')->unique();
            $table->text('details')->nullable();
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
        Schema::drop('site_settings');
    }
}
