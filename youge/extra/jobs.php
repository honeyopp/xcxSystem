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
            'pay' => [
                'name' => '支付设置',
                'link' => '/miniapp/setting.skin/pay',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'price' => [
                'name' => '价格设置',
                'link' => '/miniapp/job.price/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '城市设置', 'link' => '/miniapp/job.price/index', 'is_show' => 1,],
                    [ 'name' => '选择城市', 'link' => '/miniapp/job.price/edit', 'is_show' => 0,],
                    [ 'name' => '选择经纬度', 'link' => '/miniapp/job.price/delete', 'is_show' => 0,],
                    [ 'name' => '查看图片权限', 'link' => '/miniapp/ujob.price/create', 'is_show' => 0,],
                ],
            ],
            'chongzhi' => [
                'name' => '充值说明',
                'link' => '/miniapp/job.privilege/create',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '城市设置', 'link' => '/miniapp/job.privilege/index', 'is_show' => 1,],
                    [ 'name' => '选择城市', 'link' => '/miniapp/job.privilege/edit', 'is_show' => 0,],
                    [ 'name' => '选择经纬度', 'link' => '/miniapp/job.privilege/delete', 'is_show' => 0,],
                    [ 'name' => '查看图片权限', 'link' => '/miniapp/ujob.privilege/create', 'is_show' => 0,],
                ],
            ],
            'city' => [
                'name' => '区域设置',
                'link' => '/miniapp/job.area/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
            'hangye' => [
                'name' => '行业设置',
                'link' => '/miniapp/job.industry/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '城市设置', 'link' => '/miniapp/job.industry/index', 'is_show' => 1,],
                    [ 'name' => '选择城市', 'link' => '/miniapp/job.industry/edit', 'is_show' => 0,],
                    [ 'name' => '选择经纬度', 'link' => '/miniapp/job.industry/delete', 'is_show' => 0,],
                    [ 'name' => '查看图片权限', 'link' => '/miniapp/ujob.industry/create', 'is_show' => 0,],
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
            ],
            'jinali' => [
                'name' => '会员简历',
                'link' => '/miniapp/job.resume/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
    'gongsi' => [
        'name' => '公司管理',
        'icon' => 'fa-users',
        'menu' => [
            'setting' => [
                'name' => '公司管理',
                'link' => '/miniapp/job.company/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加公司', 'link' => '/miniapp/job.company/index', 'is_show' => 0,],
                    [ 'name' => '删除公司', 'link' => '/miniapp/job.company/select', 'is_show' => 0,],
                    [ 'name' => '修改公司', 'link' => '/miniapp/job.company/map', 'is_show' => 0,],
                    [ 'name' => '查看公司', 'link' => '/miniapp/job.company/index', 'is_show' => 0,],
                ],
            ],
            'job' => [
                'name' => '招聘信息',
                'link' => '/miniapp/job.job/index',
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
    'order' => [
        'name' => 'VIP出售记录',
        'icon' => 'fa-money',
        'menu' => [
            'setting' => [
                'name' => '出售记录',
                'link' => '/miniapp/job.order/index',
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
        ],
    ],
];
