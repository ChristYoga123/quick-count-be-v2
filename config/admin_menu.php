<?php

return [
    "admin" => [
        [
            'title' => 'Dashboard',
            'icon' => 'ti ti-home',
            'url' => 'admin/dashboard',
            'permission' => '',
        ],
        [
            'title' => 'Setelan',
            'icon' => 'ti ti-settings',
            'url' => 'admin/settings/*',
            'permission' => ['Peran.web.index'],
            'sub' => [
                [
                    'title' => 'Peran & Hak Akses',
                    'url' => 'admin/settings/roles',
                    'permission' => ['Peran.web.index'],
                ]
            ]
        ]
    ]
];
