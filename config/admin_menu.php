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
            'title' => 'Data Master',
            'icon' => 'ti ti-folder',
            'url' => 'admin/master/*',
            'permission' => ['TPS.web.index'],
            'sub' => [
                [
                    'title' => 'TPS',
                    'url' => 'admin/master/tps',
                    'permission' => ['TPS.web.index'],
                ]
            ]
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
