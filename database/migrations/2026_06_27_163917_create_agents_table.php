// database/migrations/2024_01_01_000004_create_agents_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('department')->nullable();
            $table->json('skills')->nullable();
            $table->integer('max_tickets')->default(10);
            $table->boolean('is_available')->default(true);
            $table->float('rating')->default(0);
            $table->integer('total_tickets_handled')->default(0);
            $table->float('avg_response_time')->nullable();
            $table->float('avg_resolution_time')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
            
            $table->index('department');
            $table->index('is_available');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agents');
    }
};