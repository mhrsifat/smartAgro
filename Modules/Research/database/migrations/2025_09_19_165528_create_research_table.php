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
      Schema::create('researches', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->string('slug')->unique();
    $table->string('image')->nullable();
    $table->json('authors')->nullable();
    $table->enum('status', ['draft', 'under_review', 'published'])->default('draft');
    $table->boolean('is_featured')->default(false);
    $table->string('download_url')->nullable();
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
    $table->timestamps();
});
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research');
    }
};
