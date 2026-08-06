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
        Schema::create('seo_sitemaps', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('group', 50);
            $table->string('url');
            $table->decimal('priority', 2, 1)->default(0.5);
            $table->string('change_frequency' ,30);
            $table->timestamp('last_modified')->nullable();
            $table->json('image')->nullable();
            $table->json('video')->nullable();
            $table->timestamp('available_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['model_id', 'model_type']);
            $table->index(['group', 'available_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_sitemaps');
    }
};
