<?php
//酒店私有的功能配置
$protected = [
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
            'user' => [
                'name' => '会员管理',
                'link' => '/miniapp/user.user/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'address' => [
                'name' => '收货地址',
                'link' => '/miniapp/user.address/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加地址', 'link' => 'user.address/create', 'is_show' => 0,],
                    [ 'name' => '地址列表', 'link' => 'user.address/index', 'is_show' => 0,],
                    [ 'name' => '编辑地址', 'link' => 'user.address/edit', 'is_show' => 0,],
                    [ 'name' => '删除地址', 'link' => 'user.address/delete', 'is_show' => 0,],
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
    'product' => [
        'name' => '商品管理',
        'icon' => 'fa-chrome',
        'menu' => [
            'category' => [
                'name' => '商品分类',
                'link' => '/miniapp/group.category/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加分类', 'link' => 'group.category/create', 'is_show' => 0,],
                    [ 'name' => '分类列表', 'link' => 'group.category/index', 'is_show' => 0,],
                    [ 'name' => '编辑分类', 'link' => 'group.category/edit', 'is_show' => 0,],
                    [ 'name' => '删除分类', 'link' => 'group.category/delete', 'is_show' => 0,],
                ],
            ],
            'product' => [
                'name' => '商品管理',
                'link' => '/miniapp/group.goods/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加商品', 'link' => 'group.goods/create', 'is_show' => 0,],
                    [ 'name' => '商品列表', 'link' => 'group.goods/index', 'is_show' => 0,],
                    [ 'name' => '编辑商品', 'link' => 'group.goods/edit', 'is_show' => 0,],
                    [ 'name' => '删除商品', 'link' => 'group.goods/delete', 'is_show' => 0,],
                ],
            ],

        ],
    ],
    'order' => [
        'name' => '订单管理',
        'icon' => 'fa-shopping-cart',
        'menu' => [
            'waimai' => [
                'name' => '订单管理',
                'link' => '/miniapp/group.order/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加订单', 'link' => 'group.order/create', 'is_show' => 0,],
                    [ 'name' => '订单列表', 'link' => 'group.order/index', 'is_show' => 0,],
                    [ 'name' => '编辑订单', 'link' => 'group.order/edit', 'is_show' => 0,],
                    [ 'name' => '删除订单', 'link' => 'group.order/delete', 'is_show' => 0,],
                ],
            ],
            'group' => [
                'name' => '开团记录',
                'link' => '/miniapp/group.group/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加订单', 'link' => 'group.group/create', 'is_show' => 0,],
                    [ 'name' => '订单列表', 'link' => 'group.group/index', 'is_show' => 0,],
                    [ 'name' => '编辑订单', 'link' => 'group.group/edit', 'is_show' => 0,],
                    [ 'name' => '删除订单', 'link' => 'group.group/delete', 'is_show' => 0,],
                ],
            ],
            'comment' => [
                'name' => '订单评论',
                'link' => '/miniapp/group.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加订单', 'link' => 'group.comment/create', 'is_show' => 0,],
                    [ 'name' => '订单列表', 'link' => 'group.comment/index', 'is_show' => 0,],
                    [ 'name' => '编辑订单', 'link' => 'group.comment/edit', 'is_show' => 0,],
                    [ 'name' => '删除订单', 'link' => 'group.comment/delete', 'is_show' => 0,],
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
            ]
        ],
    ],

];

return $protected ;