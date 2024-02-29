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
    Schema::create('listings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('category', 10);
      $table->string('title');
      $table->integer('bedroom');
      $table->integer('bathroom');
      $table->integer('land_size');
      $table->integer('garage');
      $table->string('address');
      $table->integer('price');
      $table->string('about');
      $table->string('image');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('listings');
  }
};
