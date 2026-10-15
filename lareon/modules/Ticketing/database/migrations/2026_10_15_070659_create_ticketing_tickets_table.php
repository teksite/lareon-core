<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticketing_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject', 190);
            $table->tinyInteger('priority');
            $table->tinyInteger('status')->default(TicketStatusType::Opened->value);
            $table->foreignId('holder_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('pending_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('source', 20)->default('web');
            $table->boolean('ai_handled')->default(false);
            $table->float('ai_last_confidence')->nullable();
            $table->tinyInteger('closed_by_type')->nullable();
            $table->unsignedBigInteger('closed_by_id')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('holder_admin_id');
            $table->index('pending_admin_id');
            $table->index('status');
            $table->index(['status', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticketing_tickets');
    }
};
