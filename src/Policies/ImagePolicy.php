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
        return $user->hasPermissionTo('view images') ? true : false;
    }

    public function view(UserInterface $user, Image $image)
    {
        return $user->hasPermissionTo('view images') ? true : false;
    }

    public function create(UserInterface $user)
    {
        return $user->hasPermissionTo('create images') ? true : false;
    }

    public function update(UserInterface $user, Image $image)
    {
        return $user->hasPermissionTo('update images') ? true : false;
    }

    public function delete(UserInterface $user, Image $image)
    {
        return false;
    }

    public function restore(UserInterface $user, Image $image)
    {
        return false;
    }

    public function forceDelete(UserInterface $user, Image $image)
    {
        return false;
    }
}
