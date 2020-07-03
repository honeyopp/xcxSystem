<?php
$public =  [
    /*公共模板*/
    'setting' => [
        'name' => '系统设置',
        'icon' => 'fa-cog',
        'menu' => [
            'setting' => [
                'name' => '站点设置',
                'link' => '/miniapp/setting.setting/setting',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'pay' => [
                'name' => '支付设置',
                'link' => '/miniapp/setting.setting/pay',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'city' => [
                'name' => '城市设置',
                'link' => '/miniapp/setting.city/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '城市设置', 'link' => '/miniapp/setting.city/index', 'is_show' => 1,],
                    [ 'name' => '选择城市', 'link' => '/miniapp/setting.city/select', 'is_show' => 0,],
                    [ 'name' => '选择经纬度', 'link' => '/miniapp/index/map', 'is_show' => 0,],
                    [ 'name' => '查看图片权限', 'link' => '/miniapp/upload.upload/index', 'is_show' => 0,],
                ],
            ],
            'juan' => [
                'name' => '优惠券设置',
                'link' => '/miniapp/setting.coupon/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
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
];
