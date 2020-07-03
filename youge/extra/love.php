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
            'city' => [
                'name' => '充值说明',
                'link' => '/miniapp/love.setting/create',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '城市设置', 'link' => '/miniapp/setting.city/index', 'is_show' => 1,],
                    [ 'name' => '选择城市', 'link' => '/miniapp/setting.city/select', 'is_show' => 0,],
                    [ 'name' => '选择经纬度', 'link' => '/miniapp/index/map', 'is_show' => 0,],
                    [ 'name' => '查看图片权限', 'link' => '/miniapp/upload.upload/index', 'is_show' => 0,],
                ],
            ],
            'chongzhio' => [
                'name' => '充值金额设置',
                'link' => '/miniapp/love.price/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '城市设置', 'link' => '/miniapp/setting.city/index', 'is_show' => 1,],
                    [ 'name' => '选择城市', 'link' => '/miniapp/setting.city/select', 'is_show' => 0,],
                    [ 'name' => '选择经纬度', 'link' => '/miniapp/index/map', 'is_show' => 0,],
                    [ 'name' => '查看图片权限', 'link' => '/miniapp/upload.upload/index', 'is_show' => 0,],
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
        'name' => '基本资料管理',
        'icon' => 'fa-user',
        'menu' => [
            'setting' => [
                'name' => '会员信息',
                'link' => '/miniapp/love.user/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加公司', 'link' => '/miniapp/job.company/index', 'is_show' => 0,],
                    [ 'name' => '删除公司', 'link' => '/miniapp/job.company/select', 'is_show' => 0,],
                    [ 'name' => '修改公司', 'link' => '/miniapp/job.company/map', 'is_show' => 0,],
                    [ 'name' => '查看公司', 'link' => '/miniapp/job.company/index', 'is_show' => 0,],
                ],
            ]
        ],
    ],
    'im' => [
        'name' => '会员聊天记录',
        'icon' => 'fa-user',
        'menu' => [
            'setting' => [
                'name' => '消息提醒查看',
                'link' => '/miniapp/love.msg/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加公司', 'link' => '/miniapp/job.company/index', 'is_show' => 0,],
                    [ 'name' => '删除公司', 'link' => '/miniapp/job.company/select', 'is_show' => 0,],
                    [ 'name' => '修改公司', 'link' => '/miniapp/job.company/map', 'is_show' => 0,],
                    [ 'name' => '查看公司', 'link' => '/miniapp/job.company/index', 'is_show' => 0,],
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
            'log' => [
                'name' => '购买日志',
                'link' => '/miniapp/love.log/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '访问趋势', 'link' => '/miniapp/tongji/index', 'is_show' => 1,],
                    [ 'name' => '访问分布', 'link' => '/miniapp/tongji/fenbu', 'is_show' => 1,],
                    [ 'name' => '访问留存', 'link' => '/miniapp/tongji/liucun', 'is_show' => 1,],
                ],
            ],
        ],
    ],
];
