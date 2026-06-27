// database/migrations/2024_01_01_000002_create_tickets_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->text('ai_summary')->nullable();
            $table->json('ai_suggestions')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->integer('response_time')->nullable(); // in minutes
            $table->integer('resolution_time')->nullable(); // in minutes
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['status', 'priority']);
            $table->index(['user_id', 'agent_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};