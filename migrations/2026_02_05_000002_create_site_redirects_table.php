<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url')->index();
            $table->string('to_url');
            $table->integer('status_code')->default(301);
            $table->boolean('is_active')->default(true);
            $table->integer('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_redirects');
    }
};
