<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagTables extends Migration
{
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->string('slug')->unique()->index();
            $table->string('type')->nullable();
            $table->integer('order_column')->nullable();
            $table->foreignId('creator_id')->nullable()->references('id')->on('users'); // If user added it via Admin (Nova)
            $table->foreignId('updater_id')->nullable()->references('id')->on('users'); // Nullable since videos can be created & updated outside Nova
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->morphs('taggable');

            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
        });
    }
}
