<?php

declare(strict_types=1);

namespace DrewRoberts\Media\Policies;

use DrewRoberts\Media\Models\Image;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Support\Contracts\Models\UserInterface;

class ImagePolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user)
    {
        return $user->hasPermissionTo('view images');
    }

    public function view(UserInterface $user, Image $image)
    {
        return $user->hasPermissionTo('view images');
    }

    public function create(UserInterface $user)
    {
        return $user->hasPermissionTo('create images');
    }

    public function update(UserInterface $user, Image $image)
    {
        return $user->hasPermissionTo('update images');
    }
}
