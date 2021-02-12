<?php

namespace DrewRoberts\Media\Tests\Support\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tipoff\Support\Models\TestModelStub;

class User extends Authenticatable
{
    use TestModelStub;
}
