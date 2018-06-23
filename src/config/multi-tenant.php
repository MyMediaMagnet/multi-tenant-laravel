<?php

return [

    'table_name' => env('MULTI_TENANT_TABLE_NAME', 'tenants'),

    'use_role_and_permissions' => (bool) env('MULTI_TENANT_ROLES_PERMISSIONS', true),

    'wildcard_domains' => (bool) env('MULTI_TENANT_WILDCARD_DOMAINS', false),

    'user_class' => env('MULTI_TENANT_USER_CLASS', 'App\User'),

    'tenant_class' => env('MULTI_TENANT_TENANT_CLASS', 'App\Tenant')

];
