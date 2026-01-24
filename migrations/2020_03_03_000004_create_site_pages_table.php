<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSitePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('pages')) {
            // Create table for storing
            Schema::create('pages', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('parent_id')->unsigned()->nullable();
                $table->boolean('status')->default(1);
                $table->boolean('menu')->default(1);
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('content')->nullable();
                $table->string('image')->nullable();
                $table->string('images')->nullable();
                $table->integer('order')->unsigned()->nullable();
                $table->text('seo')->nullable();
                $table->text('details')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('system_pages')) {
            // Create table for storing
            Schema::create('system_pages', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('status')->default(1);
                $table->string('title')->unique();
                $table->string('slug')->unique();
                $table->text('content')->nullable();
                $table->string('image')->nullable();
                $table->string('images')->nullable();
                $table->integer('order')->unsigned()->nullable();
                $table->text('seo')->nullable();
                $table->text('details')->nullable();
                $table->timestamps();
            });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('pages')) {
            Schema::drop('pages');
        }
        if (Schema::hasTable('system_pages')) {
            Schema::drop('system_pages');
        }
    }
}
