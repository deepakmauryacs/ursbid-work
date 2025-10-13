<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seller_support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('seller')->cascadeOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'in_working', 'under_process', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_support_tickets');
    }
};
