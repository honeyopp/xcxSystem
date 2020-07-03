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
            'baojia' => [
                'name' => 'Banner设置',
                'link' => '/miniapp/publicuse.banner/photo',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
            'photo' => [
                'name' => '门店设置',
                'link' => '/miniapp/ktv.ktv/create',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
            'huodong' => [
                'name' => '优惠券活动',
                'link' => '/miniapp/setting.activity/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
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
            'juan' => [
                'name' => '会员优惠券管理',
                'link' => '/miniapp/user.coupon/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],

        ],
    ],
    'anli' => [
        'name' => '包厢管理',
        'icon' => 'fa-archive',
        'menu' => [
            'area' => [
                'name' => '包厢管理',
                'link' => '/miniapp/ktv.room/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
    'order' => [
        'name' => '买单记录',
        'icon' => 'fa-shopping-cart',
        'menu' => [
            'waimai' => [
                'name' => '买单记录',
                'link' => '/miniapp/mendian.order/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                    [ 'name' => '买单列表', 'link' => 'mendian.order/index', 'is_show' => 0,],

                ],
            ],

        ],
    ],
    'shenqing' => [
        'name' => '预约管理',
        'icon' => 'fa-clock-o',
        'menu' => [
            'order' => [
                'name' => '预约管理',
                'link' => '/miniapp/ktv.enroll/index',
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
