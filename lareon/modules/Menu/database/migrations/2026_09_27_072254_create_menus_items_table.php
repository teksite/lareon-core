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
        Schema::create('menus_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('position')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('pre_icon')->nullable();
            $table->string('next_icon')->nullable();
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->string('classes')->nullable();
            $table->string('attributes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus_items');
    }
};
