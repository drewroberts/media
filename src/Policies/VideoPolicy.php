<?php

declare(strict_types=1);

namespace DrewRoberts\Media\Policies;

use DrewRoberts\Media\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Support\Contracts\Models\UserInterface;

class VideoPolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user)
    {
        return $user->hasPermissionTo('view videos');
    }

    public function view(UserInterface $user, Video $video)
    {
        return $user->hasPermissionTo('view videos');
    }

    public function create(UserInterface $user)
    {
        return $user->hasPermissionTo('create videos');
    }

    public function update(UserInterface $user, Video $video)
    {
        return $user->hasPermissionTo('update videos');
    }
}
