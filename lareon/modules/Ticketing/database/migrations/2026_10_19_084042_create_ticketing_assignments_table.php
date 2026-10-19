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
        Schema::create('ticketing_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('ticketing_tickets')->cascadeOnDelete();
            $table->string('type', 20);

            $table->unsignedBigInteger('from_admin_id')->nullable();
            $table->unsignedBigInteger('to_admin_id')->nullable();

            $table->text('reason')->nullable();

            $table->tinyInteger('status', )->default('accepted');

            $table->text('decision_reason')->nullable();
            $table->timestamp('decided_at')->nullable();

            $table->timestamps();

            $table->index(['ticket_id', 'created_at']);
            $table->index('to_admin_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticketing_assignments');
    }
};
