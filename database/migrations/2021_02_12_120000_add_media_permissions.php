<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddMediaPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
         'view images' => ['Owner', 'Staff'],
         'create images' => ['Owner'],
         'update images' => ['Owner'],
         'view tags' => ['Owner', 'Staff'],
         'create tags' => ['Owner'],
         'update tags' => ['Owner'],
         'delete tags' => [],
         'view videos' => ['Owner', 'Staff'],
         'create videos' => ['Owner'],
         'update videos' => ['Owner'],
        ];

        $this->createPermissions($permissions);
    }
}

