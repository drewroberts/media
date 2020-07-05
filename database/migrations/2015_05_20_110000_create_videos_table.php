<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique()->index(); // ID on YouTube.
            $table->string('source')->default('youtube');
            $table->string('name'); // Internal reference name for video
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('length')->nullable(); // Stored in seconds
            $table->foreignId('image_id')->nullable()->references('id')->on('images'); // Thumbnail image (maxres) for the video
            $table->integer('view_count')->nullable();
            $table->integer('like_count')->nullable();
            $table->integer('dislike_count')->nullable();
            $table->integer('comment_count')->nullable();
            $table->string('broadcast')->nullable(); // Options include 'none', 'live', 'upcoming'
            $table->string('privacy')->nullable(); // Options include 'public', 'private', 'unlisted'
            $table->string('location')->nullable();
            $table->boolean('embeddable')->default(1);

            $table->foreignId('creator_id')->nullable()->references('id')->on('users'); // If user added it via Admin (Nova)
            $table->dateTime('stream_started_at')->nullable(); // When stream actually started on YouTube
            $table->dateTime('stream_scheduled_at')->nullable(); // When the stream was scheduled to start on YouTube
            $table->dateTime('published_at')->nullable(); // When video was published on YouTube
            $table->timestamps();
        });
    }
}
