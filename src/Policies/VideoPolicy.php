<?php

declare(strict_types=1);

namespace App\Policies;

use DrewRoberts\Media\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Support\Contracts\Models\UserInterface;

class VideoPolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user)
    {
        return $user->hasPermissionTo('view videos') ? true : false;
    }

    public function view(UserInterface $user, Video $video)
    {
        return $user->hasPermissionTo('view videos') ? true : false;
    }

    public function create(UserInterface $user)
    {
        return $user->hasPermissionTo('create videos') ? true : false;
    }

    public function update(UserInterface $user, Video $video)
    {
        return $user->hasPermissionTo('update videos') ? true : false;
    }

    public function delete(UserInterface $user, Video $video)
    {
        return false;
    }

    public function restore(UserInterface $user, Video $video)
    {
        return false;
    }

    public function forceDelete(UserInterface $user, Video $video)
    {
        return false;
    }
}
