<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->string('slug')->unique()->index();
            $table->string('type')->nullable();
            $table->integer('order_column')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable()->foreign()->references('id')->on('users'); // If user added it via Admin
            $table->unsignedBigInteger('updater_id')->nullable()->foreign()->references('id')->on('users'); // Nullable since videos can be created & updated outside Nova
            $table->timestamps();
        });
    }
}
