<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLayoutFromPagesTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('pages', 'layout')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('layout');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('pages', 'layout')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->text('layout')->nullable();
            });
        }
    }
}
