<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('description', 300)->nullable();
            $table->integer('count');
            $table->bigInteger('price');
            $table->enum('status', ['pending', 'accepted', 'declined', 'check', 'finished'])->default('pending');
            $table->timestamp('post_date')->nullable();
            $table->timestamp('post_date_end')->nullable();
            $table->string('decline_reason', 300)->nullable();
            $table->foreignId('channel_id')->constrained();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('pattern_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
