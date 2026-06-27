// database/migrations/2024_01_01_000012_create_ai_categories_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ai_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('keywords')->nullable();
            $table->float('confidence_threshold')->default(0.5);
            $table->boolean('is_active')->default(true);
            $table->string('color')->default('#6B7280');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_categories');
    }
};