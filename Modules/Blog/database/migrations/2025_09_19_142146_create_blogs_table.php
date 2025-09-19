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
    Schema::create('blogs', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->text('excerpt');
        $table->string('slug')->unique();
        $table->string('image')->nullable();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->timestamps();
    });
}
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
