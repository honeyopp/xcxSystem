<?php
//酒店私有的功能配置
return  [
    /*公共模板*/
    'setting' => [
        'name' => '系统设置',
        'icon' => 'fa-cog',
        'menu' => [
            'setting' => [
                'name' => '站点设置',
                'link' => '/miniapp/setting.skin/create',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'baojia' => [
                'name' => 'Banner设置',
                'link' => '/miniapp/publicuse.banner/photo',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
            'yiyuan' => [
                'name' => '来院路线',
                'link' => '/miniapp/hospital.hospital/create',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
            'pinpai' => [
                'name' => '医院品牌',
                'link' => '/miniapp/hospital.contents/create',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
            'category' => [
                'name' => '分类设置',
                'link' => '/miniapp/publicuse.category/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
        ],
    ],
    'user' => [
        'name' => '会员管理',
        'icon' => 'fa-user',
        'menu' => [
            'setting' => [
                'name' => '会员管理',
                'link' => '/miniapp/user.user/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
    'anli' => [
        'name' => '医生管理',
        'icon' => 'fa-archive',
        'menu' => [
            'area' => [
                'name' => '医生管理',
                'link' => '/miniapp/hospital.doctor/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
    'ay' => [
        'name' => '预约管理',
        'icon' => 'fa-archive',
        'menu' => [
            'area' => [
                'name' => '预约挂号',
                'link' => '/miniapp/hospital.enroll/guahao',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'baomu' => [
                'name' => '快速问诊',
                'link' => '/miniapp/hospital.enroll/wenzhen',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
    'count' => [
        'name' => '统计报表',
        'icon' => 'fa-line-chart',
        'menu' => [
            'xiaochengxu' => [
                'name' => '访问分析',
                'link' => '/miniapp/tongji/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 1, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '访问趋势', 'link' => '/miniapp/tongji/index', 'is_show' => 1,],
                    [ 'name' => '访问分布', 'link' => '/miniapp/tongji/fenbu', 'is_show' => 1,],
                    [ 'name' => '访问留存', 'link' => '/miniapp/tongji/liucun', 'is_show' => 1,],
                ],
            ],
        ],
    ],
];
