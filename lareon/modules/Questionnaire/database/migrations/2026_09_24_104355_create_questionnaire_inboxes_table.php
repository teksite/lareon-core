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
        Schema::create('questionnaire_inboxes', function (Blueprint $table,) {
            $table->id();
            $table->foreignId('form_id')->nullable()->constrained('questionnaire_forms')->nullOnDelete()->cascadeOnUpdate();
            $table->string('url')->nullable();
            $table->json('data');
            $table->json('note')->nullable();
            $table->string('title')->nullable();
            $table->ipAddress();
            $table->foreignId('reader_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_inboxes');
    }
};
