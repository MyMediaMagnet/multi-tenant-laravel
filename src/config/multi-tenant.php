<?php

return [

    'table_name' => 'tenants',

    'use_roles_and_permissions' => true,

    'wildcard_domains' => false,

    'user_class' => 'App\User',

    'tenant_class' => 'App\Tenant',

    'role_class' => 'App\Role',

    'permission_class' => 'App\Permission',

    'feature_class' => 'App\Feature',

    /**
     * Features are various aspects of your application that should only be available to
     * tenants who have the expected permissions.
     *
     * Use php artisan tenant:sync to sync your features and permissions
     */

    'features' => [
        // Add features here with a key and the associated model
        // 'posts' => ['label' => 'Posts', 'model' => 'App\Post']
    ],

    /**
     * Roles can be assigned to users and can contain various permissions
     *
     * Use php artisan tenant:sync to sync your features and permissions
     */

    'roles' => [
        'owner' => 'Owner',
        'manager' => 'Manager',
        'author' => 'Author',
        'editor' => 'Editor'
        // Add any additional roles
    ],

    /**
     * Each new feature will have a set of permissions for it.  These will be automatically
     * generated with the php artisan tenant:sync command
     *
     * Each users role will be able to be granted any permissions that have been created
     *
     * Use php artisan tenant:sync to sync
     */

    'permission_types' => [
        'view',
        'edit',
        'create',
        'delete'
        // Add add any additional features to be included with each feature
    ]

];
