// database/migrations/2024_01_01_000004_create_knowledge_base_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('knowledge_base', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('category');
            $table->json('tags')->nullable();
            $table->integer('views')->default(0);
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->boolean('is_published')->default(true);
            $table->json('ai_metadata')->nullable();
            $table->timestamps();
            
            $table->index(['category', 'views']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('knowledge_base');
    }
};