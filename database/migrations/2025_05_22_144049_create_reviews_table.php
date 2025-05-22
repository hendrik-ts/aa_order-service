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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->text('comment')->nullable();
            $table->tinyInteger('rating')->unsigned(); // rating from 1–5
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
