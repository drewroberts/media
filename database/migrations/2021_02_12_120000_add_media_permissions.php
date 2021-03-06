<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddMediaPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
         'view images',
         'create images',
         'update images',
         'view tags',
         'create tags',
         'update tags',
         'delete tags',
         'view videos',
         'create videos',
         'update videos',
        ];

        $this->createPermissions($permissions);
    }
}

