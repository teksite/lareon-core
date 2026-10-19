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
        Schema::create('ticketing_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('ticketing_tickets')->cascadeOnDelete();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->string('changed_by_type', 20);
            $table->unsignedBigInteger('changed_by_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['ticket_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticketing_status_logs');
    }
};
