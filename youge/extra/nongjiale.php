<?php
//酒店私有的功能配置
$protected = [
    'store' => [
        'name' => '农家乐管理',
        'icon' => 'fa-shopping-cart',
        'menu' => [
            'hotel' => [
                'name' => '商户管理',
                'link' => '/miniapp/nongjiale.store/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '商户管理', 'link' => 'user.user/create', 'is_show' => 0,],
                    [ 'name' => '商户管理', 'link' => 'user.user/list', 'is_show' => 0,],
                    [ 'name' => '商户管理', 'link' => 'user.user/edit', 'is_show' => 0,],
                    [ 'name' => '商户管理', 'link' => 'user.user/delete', 'is_show' => 0,],
                ],
            ],
            'comment' => [
                'name' => '套餐管理',
                'link' => '/miniapp/hotel.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/hotel.comment/index', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/hotel.comment/edit', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/hotel.comment/delete', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/hotel.comment/create', 'is_show' => 0,],
                ],
            ],
            'room' => [
                'name' => '房间管理',
                'link' => '/miniapp/hotel.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/hotel.comment/index', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/hotel.comment/edit', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/hotel.comment/delete', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/hotel.comment/create', 'is_show' => 0,],
                ],
            ],
            'project' => [
                'name' => '项目管理',
                'link' => '/miniapp/hotel.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/hotel.comment/index', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/hotel.comment/edit', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/hotel.comment/delete', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/hotel.comment/create', 'is_show' => 0,],
                ],
            ],
            'news' => [
                'name' => '动态管理',
                'link' => '/miniapp/nongjiale.news/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/hotel.comment/index', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/hotel.comment/edit', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/hotel.comment/delete', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/hotel.comment/create', 'is_show' => 0,],
                ],
            ],
        ],
    ],

    'order' => [
        'name' => '订单管理',
        'icon' => 'fa-shopping-cart',
        'menu' => [
            'hotel' => [
                'name' => '订单管理',
                'link' => '/miniapp/hotel.hotelorder/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => 'user.user/create', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => 'user.user/list', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => 'user.user/edit', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => 'user.user/delete', 'is_show' => 0,],
                ],
            ],
            'comment' => [
                'name' => '订单评论',
                'link' => '/miniapp/hotel.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/hotel.comment/index', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/hotel.comment/edit', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/hotel.comment/delete', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/hotel.comment/create', 'is_show' => 0,],
                ],
            ],
        ],
    ],

    'count' => [
        'name' => '统计报表',
        'icon' => 'fa-line-chart',
        'menu' => [
            'baobiao' => [
                'name' => '报表',
                'link' => '/miniapp/hotel.hotelorder/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 1, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '商家增长数报表', 'link' => '/miniapp/hotel.report/store', 'is_show' => 1,],
                    [ 'name' => '会员增长数报表', 'link' => '/miniapp/hotel.report/user', 'is_show' => 1,],
                    [ 'name' => '订单销量报表', 'link' => '/miniapp/hotel.report/order', 'is_show' => 1,],
                ],
            ],
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
            'paiming' => [
                'name' => '排名统计',
                'link' => '/miniapp/hotel.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 1, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '酒店排名', 'link' => '/miniapp/hotel.ranking/hotel', 'is_show' => 1,],
                    [ 'name' => '房间排名', 'link' => '/miniapp/hotel.ranking/room', 'is_show' => 1,],
                ],
            ],
            'jiesuan' => [
                'name' => '结算统计',
                'link' => '/miniapp/hotel.count/count',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],

];
return $protected ;