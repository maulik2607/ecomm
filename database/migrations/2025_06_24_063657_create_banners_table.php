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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image'); // Path to the image file
            $table->string('link')->nullable(); // Custom URL or redirect
            $table->enum('type', ['product', 'category', 'custom'])->default('custom');
            $table->unsignedBigInteger('reference_id')->nullable(); // Product/Category ID if applicable
            $table->enum('placement', ['top', 'middle', 'bottom'])->default('top');
            $table->integer('order')->default(0); // Display order
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
