<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaggablesTable extends Migration
{
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignIdFor(app('tag'));
            $table->morphs('taggable');

            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
        });
    }
}
