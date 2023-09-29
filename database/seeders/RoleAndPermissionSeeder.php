<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions for channels table
        Permission::create(['name' => 'create-channels']);
        Permission::create(['name' => 'edit-channels']);
        Permission::create(['name' => 'delete-channels']);

        // Permissions for entity_infos table
        Permission::create(['name' => 'create-entity-infos']);
        Permission::create(['name' => 'edit-entity-infos']);
        Permission::create(['name' => 'delete-entity-infos']);

        // Permissions for patterns table
        Permission::create(['name' => 'create-patterns']);
        Permission::create(['name' => 'edit-patterns']);
        Permission::create(['name' => 'delete-patterns']);

        // Permissions for orders table
        Permission::create(['name' => 'create-orders']);
        Permission::create(['name' => 'edit-orders']);
        Permission::create(['name' => 'delete-orders']);

        // Permissions for reviews table
        Permission::create(['name' => 'create-reviews']);
        Permission::create(['name' => 'edit-reviews']);
        Permission::create(['name' => 'delete-reviews']);

        Permission::create(['name' => 'accept-channel']);
        Permission::create(['name' => 'decline-channel']);

        Permission::create(['name' => 'accept-pattern']);
        Permission::create(['name' => 'decline-pattern']);

        $adminRole = Role::create(['name' => 'Admin']);
        $moderatorRole = Role::create(['name' => 'Moderator']);

        // Admin gets all permissions
        $adminRole->givePermissionTo([
            'create-channels',
            'edit-channels',
            'delete-channels',
            'create-entity-infos',
            'edit-entity-infos',
            'delete-entity-infos',
            'create-patterns',
            'edit-patterns',
            'delete-patterns',
            'create-orders',
            'edit-orders',
            'delete-orders',
            'create-reviews',
            'edit-reviews',
            'delete-reviews'
        ]);

    }
}
