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
            'about' => [
                'name' => '关于我们',
                'link' => '/miniapp/school.school/create',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'banner' => [
                'name' => 'Banner',
                'link' => '/miniapp/school.banner/photo',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'photo' => [
                'name' => '相册管理',
                'link' => '/miniapp/school.photo/photo',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
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
            ]
        ],
    ],
    'gongsi' => [
        'name' => '课程管理',
        'icon' => 'fa-bank',
        'menu' => [
            'setting' => [
                'name' => '课程管理',
                'link' => '/miniapp/school.classone/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ]
        ],
    ],
    'im' => [
        'name' => '文章管理',
        'icon' => 'fa-file-text',
        'menu' => [
            'setting' => [
                'name' => '师资力量',
                'link' => '/miniapp/school.teacher/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
            'studbnt' => [
                'name' => '学员风采',
                'link' => '/miniapp/school.student/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
        ],
    ],
    'huiodong' => [
        'name' => '活动管理',
        'icon' => 'fa-futbol-o',
        'menu' => [
            'setting' => [
                'name' => '活动管理',
                'link' => '/miniapp/school.activity/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
        ],
    ],
    'piao' => [
        'name' => '互动投票',
        'icon' => 'fa-user',
        'menu' => [
            'setting' => [
                'name' => '互动管理',
                'link' => '/miniapp/school.vote/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
        ],
    ],
    'fankui' => [
        'name' => '咨询反馈',
        'icon' => 'fa-user',
        'menu' => [
            'setting' => [
                'name' => '咨询反馈',
                'link' => '/miniapp/school.entry/index',
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
