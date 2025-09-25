<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('BDT');
            $table->text('message')->nullable();
            $table->boolean('anonymous')->default(false);
            $table->string('payment_gateway')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->unsignedBigInteger('status_updated_by')->nullable();
            $table->timestamp('donated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};