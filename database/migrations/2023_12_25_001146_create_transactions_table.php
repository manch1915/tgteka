<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('appointment', 26);
            $table->string('service', 26)->nullable();
            $table->decimal('amount', 10);
            $table->string('transaction_id');
            $table->enum('status', ['pending', 'succeeded', 'failed', 'in_progress', 'completed', 'rejected', 'created', 'under_review'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('details')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
