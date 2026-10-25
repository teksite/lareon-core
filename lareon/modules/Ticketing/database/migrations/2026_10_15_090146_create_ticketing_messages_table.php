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
        Schema::create('ticketing_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('ticketing_tickets')->cascadeOnDelete();
            $table->tinyInteger('sender_type');
            $table->foreignId('sender_id')->nullable();
            $table->text('body');
            $table->boolean('is_internal_note')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['ticket_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticketing_messages');
    }
};
