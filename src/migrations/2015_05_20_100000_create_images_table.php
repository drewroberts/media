<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->unique()->index(); // Includes path to file and the file type
            $table->integer('width');
            $table->integer('height');
            $table->text('description')->nullable();
            $table->string('alt')->nullable();
            $table->string('credit')->nullable(); // Used for amp-img attribution

            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->unsignedBigInteger('updater_id')->nullable();
            $table->foreign('updater_id')->references('id')->on('users');
            $table->timestamps();
        });
    }
}
