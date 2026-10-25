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
        Schema::create('ticketing_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('ticketing_tickets')->cascadeOnDelete();
            $table->foreignId('message_id')->nullable()->constrained('ticketing_messages')->cascadeOnDelete();

            $table->tinyInteger('uploaded_by_type',);
            $table->unsignedBigInteger('uploaded_by_id');

            $table->string('disk', 50);
            $table->string('path', 255);
            $table->string('original_name', 255);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->timestamps();

            $table->index('ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticketing_attachments');
    }
};
