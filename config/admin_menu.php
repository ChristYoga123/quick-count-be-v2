<?php

return [
    "admin" => [
        [
            'title' => 'Dashboard',
            'icon' => 'ti ti-home',
            'url' => '/admin/dashboard',
            'permission' => '',
        ],
        [
            'title' => 'Settings',
            'icon' => 'ti ti-settings',
            'url' => '/admin/settings/*',
            'permission' => ['Roles.admin.index'],
            'sub' => [
                [
                    'title' => 'Roles',
                    'url' => '/admin/settings/roles',
                    'permission' => ['Roles.admin.index'],
                ]
            ]
        ]
    ]
];
