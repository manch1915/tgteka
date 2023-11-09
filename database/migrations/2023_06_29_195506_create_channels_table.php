<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelsTable extends Migration
{
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('channel_url')->unique();
            $table->string('channel_name', 64);
            $table->string('telegram_username')->unique()->nullable();
            $table->string('avatar')->nullable();
            $table->string('topic', 100);
            $table->enum('type', ['chat', 'channel']);
            $table->string('language');
            $table->string('description', 2400)->nullable();
            $table->bigInteger('format_one')->default(0);
            $table->bigInteger('format_two')->default(0);
            $table->bigInteger('format_three')->default(0);
            $table->bigInteger('no_deletion')->default(0);
            $table->boolean('repost')->default(false);
            $table->integer('repeat_discount')->default(0); // Repeat discount percentage
            $table->float('score')->default(0);
            $table->float('rating')->default(0);
            $table->integer('likes_count')->default(0); // Number of likes
            $table->integer('views_count')->default(0); // Number of views
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
