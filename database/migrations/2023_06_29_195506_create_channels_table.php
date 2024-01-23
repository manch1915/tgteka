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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('url')->unique();
            $table->string('channel_name', 64);
            $table->foreignId('topic_id')->constrained();
            $table->enum('type', ['chat', 'channel']);
            $table->string('language');
            $table->string('description', 300)->nullable();
            $table->string('subscribers_source', 300)->nullable();
            $table->bigInteger('format_one_price')->default(0);
            $table->bigInteger('format_two_price')->default(0);
            $table->bigInteger('format_three_price')->default(0);
            $table->bigInteger('no_deletion_price')->default(0);
            $table->boolean('repost_price')->default(false);
            $table->integer('repeat_discount')->default(0); // Repeat discount percentage
            $table->decimal('male_percentage', 5);
            $table->float('score')->default(0);
            $table->float('rating')->default(0);
            $table->integer('likes_count')->default(0); // Number of likes
            $table->integer('views_count')->default(0); // Number of views
            $table->enum('status', ['pending', 'accepted', 'declined', 'loading'])->default('pending');
            $table->date('channel_creation_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
