// database/migrations/2024_01_01_000003_create_messages_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->enum('type', ['customer', 'agent', 'system'])->default('customer');
            $table->boolean('is_internal')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['ticket_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};