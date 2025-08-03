<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('cat_id')->nullable();
            $table->unsignedInteger('sub_id')->nullable();
            $table->unsignedInteger('super_id')->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('user_type', 255)->nullable();
            $table->string('insert_by', 255)->nullable();
            $table->string('update_by', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('post_date', 255)->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('status', 200)->default('1');
            $table->integer('order_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
