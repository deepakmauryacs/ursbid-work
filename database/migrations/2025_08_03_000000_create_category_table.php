<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('cat_id', 200)->nullable();
            $table->string('post_date', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('status', 200)->default('1');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
