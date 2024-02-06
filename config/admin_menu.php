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
            'permission' => ['TPS.web.index', 'Dapil.web.index'],
            'sub' => [
                [
                    'title' => 'Petugas',
                    'url' => 'admin/master/petugas',
                    'permission' => ['Petugas.web.index'],
                ],
                [
                    'title' => 'TPS',
                    'url' => 'admin/master/tps',
                    'permission' => ['TPS.web.index'],
                ],
                [
                    'title' => 'Dapil',
                    'url' => 'admin/master/dapil',
                    'permission' => ['Dapil.web.index'],
                ],
                [
                    'title' => 'Partai',
                    'url' => 'admin/master/partai',
                    'permission' => ['Partai.web.index'],
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
