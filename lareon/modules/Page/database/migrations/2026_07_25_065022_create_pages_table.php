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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('label')->nullable();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->text('body')->nullable();
            $table->string('image', 200)->nullable();
            $table->string('template')->nullable();
            $table->tinyInteger('publish_status')->default(\Lareon\Steward\App\Enums\PublishStatusEnum::PUBLISHED->value);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['publish_status', 'published_at', 'deleted_at']);
            $table->fullText(['title', 'excerpt', 'body']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
