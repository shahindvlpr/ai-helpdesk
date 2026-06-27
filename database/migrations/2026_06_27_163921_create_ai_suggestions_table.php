// database/migrations/2024_01_01_000011_create_ai_suggestions_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ai_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->json('suggestion');
            $table->string('type'); // reply, summary, category
            $table->float('confidence_score')->default(0);
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['ticket_id', 'type']);
            $table->index('confidence_score');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_suggestions');
    }
};