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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 16)->unique();
            $table->string('email')->unique();
            $table->string('mobile_number')->nullable();
            $table->string('telegram_username')->unique()->nullable();
            $table->string('telegram_user_id')->unique()->nullable();
            $table->integer('vk_id')->unique()->nullable();
            $table->decimal('balance', 8, 2)->default(0.00);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignUuid('referral_id')->nullable()->constrained()->cascadeOnDelete();
            $table->rememberToken();
            $table->string('two_factor_code')->nullable();
            $table->dateTime('two_factor_expires_at')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
