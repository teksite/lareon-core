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
            $table->string('title')->unique();
            $table->timestamps();
        });

        Schema::create('meta_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('model_type');
            $table->string('template_path');
            $table->timestamps();

            $table->unique(['model_type', 'template_path', 'name' ,'title']);
        });

        Schema::create('meta_template_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meta_template_id')->constrained('meta_templates')->cascadeOnDelete();
            $table->foreignId('meta_element_id')->constrained('meta_elements')->cascadeOnDelete();
            $table->string('key');
            $table->string('label')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['meta_template_id', 'sort',]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_template_models');
        Schema::dropIfExists('meta_templates');
        Schema::dropIfExists('meta_elements');

    }
};
