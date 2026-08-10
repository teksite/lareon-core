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
        Schema::create('seo_meta_models', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('indexable')->default(true);
            $table->boolean('followable')->default(true);
            $table->json('open_graph')->nullable();
            $table->json('extra')->nullable();
            $table->timestamps();

            $table->unique(['model_id', 'model_type']);
        });

        Schema::create('seo_schema_models', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->json('schema')->nullable();
            $table->timestamps();

            $table->index(['model_id', 'model_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_schema_models');
        Schema::dropIfExists('seo_meta_models');
    }
};
