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
            'permission' => ['TPS.web.index', 'Dapil.web.index', 'Partai.web.index', 'Caleg.web.index', 'Capres.web.index'],
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
                ],
                [
                    'title' => 'Caleg',
                    'url' => 'admin/master/caleg',
                    'permission' => ['Caleg.web.index'],
                ],
                [
                    'title' => 'Capres',
                    'url' => 'admin/master/capres',
                    'permission' => ['Capres.web.index'],
                ]
            ]
        ],
        [
            'title' => 'Survey',
            'icon' => 'ti ti-pencil',
            'url' => 'admin/survey/*',
            'permission' => ['Survey.web.index'],
            'sub' => [
                [
                    'title' => 'Judul Survey',
                    'url' => 'admin/survey/judul',
                    'permission' => ['Survey.web.index'],
                ],
                [
                    'title' => 'Kategori Survey',
                    'url' => 'admin/survey/kategori',
                    'permission' => ['Survey.web.index'],
                ],
                [
                    'title' => 'Pertanyaan Survey',
                    'url' => 'admin/survey/pertanyaan',
                    'permission' => ['Survey.web.index'],
                ],
                [
                    'title' => 'Perkondisian Pertanyaan',
                    'url' => 'admin/survey/perkondisian',
                    'permission' => ['Survey.web.index'],
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
