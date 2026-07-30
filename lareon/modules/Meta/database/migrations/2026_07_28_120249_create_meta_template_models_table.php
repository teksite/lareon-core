<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('meta_elements', function (Blueprint $table) {
            $table->id();
            $table->string('element' , 150)->unique();
            $table->string('title' , 150)->unique();
            $table->timestamps();
        });


        Schema::create('meta_template_fields', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->string('template', 150);
            $table->foreignId('meta_element_id')->constrained('meta_elements')->cascadeOnDelete();
            $table->string('title');
            $table->string('name');
            $table->json('settings')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();

            $table->index(['model_type', 'template']);
            $table->unique(['model_type', 'template', 'name']);
        });


        Schema::create('meta_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained('meta_template_fields')->cascadeOnDelete();
            $table->morphs('model');
            $table->json('content')->nullable();
            $table->timestamps();

            $table->unique(['field_id', 'model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_models');
        Schema::dropIfExists('meta_template_fields');
        Schema::dropIfExists('meta_elements');

    }
};
