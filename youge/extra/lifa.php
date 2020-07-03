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
                'sub' => [],
            ],
            'pay' => [
                'name' => '支付设置',
                'link' => '/miniapp/setting.skin/pay',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'dianpu' => [
                'name' => '店铺设置',
                'link' => '/miniapp/hair.hair/create',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
            'baojia' => [
                'name' => 'Banner设置',
                'link' => '/miniapp/hair.banner/photo',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
            'fenlei' => [
                'name' => '类型及价目表',
                'link' => '/miniapp/hair.category/index',
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
                'sub' => [],
            ],
            'juan' => [
                'name' => '会员优惠券管理',
                'link' => '/miniapp/user.coupon/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
        ],
    ],
    'anli' => [
        'name' => '经典案例',
        'icon' => 'fa-archive',
        'menu' => [
            'area' => [
                'name' => '经典案例',
                'link' => '/miniapp/hair.works/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
        ],
    ],
    'shejishi' => [
        'name' => '设计师',
        'icon' => 'fa-paint-brush',
        'menu' => [
            'nav' => [
                'name' => '设计师管理',
                'link' => '/miniapp/hair.designer/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
            'comment' => [
                'name' => '设计师评论',
                'link' => '/miniapp/hair.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
        ],
    ],
    'shenqing' => [
        'name' => '预约管理',
        'icon' => 'fa-clock-o',
        'menu' => [
            'xiaochengxu' => [
                'name' => '设计师预约',
                'link' => '/miniapp/hair.enroll/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
        ],
    ],
    'order' => [
        'name' => '支付信息',
        'icon' => 'fa-shopping-cart',
        'menu' => [
            'hotel' => [
                'name' => '支付信息',
                'link' => '/miniapp/hair.order/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
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
