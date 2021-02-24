<?php

namespace DrewRoberts\Media\Tests\Support;

use DrewRoberts\Media\Traits\HasTags;
use Illuminate\Database\Eloquent\Model;

class TaggableStub extends Model
{
    use HasTags;
}
