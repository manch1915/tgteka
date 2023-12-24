<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['payment', 'payout']);
            $table->decimal('amount', 10 ,2);
            $table->string('transaction_id');
            $table->enum('status', ['pending', 'succeeded', 'failed'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('details')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
