<?php
//酒店私有的功能配置
$protected = [
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
            'pay' => [
                'name' => '支付设置',
                'link' => '/miniapp/setting.setting/pay',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'store' => [
                'name' => '官网设置',
                'link' => '/miniapp/nongjialegw.store/edit',
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
            ],
        ],
    ],
    'store' => [
        'name' => '农家乐管理',
        'icon' => 'fa-leaf',
        'menu' => [
            'project' => [
                'name' => '项目管理',
                'link' => '/miniapp/nongjialegw.project/index',
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
                'link' => '/miniapp/nongjialegw.news/index',
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
    'room' => [
        'name' => '房间管理',
        'icon' => 'fa-bed',
        'menu' => [
            'comment' => [
                'name' => '房间管理',
                'link' => '/miniapp/nongjialegw.room/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/nongjialegw.room/index', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/nongjialegw.room/edit', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/nongjialegw.room/delete', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/nongjialegw.room/create', 'is_show' => 0,],
                ],
            ],
            'room' => [
                'name' => '房间价格管理',
                'link' => '/miniapp/nongjialegw.room/price',
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
    'taocan' => [
        'name' => '套餐管理',
        'icon' => 'fa-ticket',
        'menu' => [
            'comment' => [
                'name' => '产品管理',
                'link' => '/miniapp/nongjialegw.taocan/index',
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
                'name' => '套餐管理',
                'link' => '/miniapp/nongjialegw.package/index',
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
                'link' => '/miniapp/nongjialegw.order/index',
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
                'link' => '/miniapp/nongjialegw.comment/index',
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
                'link' => '/miniapp/nonojialegwe.hotelorder/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 1, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '会员增长数报表', 'link' => '/miniapp/nongjialegw.report/user', 'is_show' => 1,],
                    [ 'name' => '订单销量报表', 'link' => '/miniapp/nongjialegw.report/order', 'is_show' => 1,],
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
                    [ 'name' => '套餐排名', 'link' => '/miniapp/nongjialegw.ranking/hotel', 'is_show' => 1,],
                    [ 'name' => '房间排名', 'link' => '/miniapp/nongjialegw.ranking/room', 'is_show' => 1,],
                ],
            ],
            'jiesuan' => [
                'name' => '结算统计',
                'link' => '/miniapp/nongjialegw.count/count',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],

];
return $protected ;