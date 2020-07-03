<?php
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
            'city' => [
                'name' => '城市设置',
                'link' => '/miniapp/taocan.city/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '城市设置', 'link' => '/miniapp/taocan.city/index', 'is_show' => 1,],
                    [ 'name' => '选择城市', 'link' => '/miniapp/taocan.city/select', 'is_show' => 0,],
                    [ 'name' => '选择经纬度', 'link' => '/miniapp/index/map', 'is_show' => 0,],
                    [ 'name' => '查看图片权限', 'link' => '/miniapp/upload.upload/index', 'is_show' => 0,],
                ],
            ],
            'banner' => [
                'name' => 'banner设置',
                'link' => '/miniapp/taocan.banner/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/taocan.banner/create', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/taocan.banner/index', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/taocan.banner/edit', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/taocan.banner/delete', 'is_show' => 0,],
                ],
            ],
            'nav' => [
                'name' => '导航(分类)设置',
                'link' => '/miniapp/taocan.nav/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/taocan.nav/create', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/taocan.nav/index', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/taocan.nav/edit', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/taocan.nav/delete', 'is_show' => 0,],
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
    'store' => [
        'name' => '商家管理',
        'icon' => 'fa-users',
        'menu' => [
            'taocan' => [
                'name' => '商家管理',
                'link' => '/miniapp/taocan.store/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/taocan.store/create', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/taocan.store/index', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/taocan.store/edit', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/taocan.store/delete', 'is_show' => 0,],
                ],
            ],
        ],
    ],
    'taocan' => [
        'name' => '套餐管理',
        'icon' => 'fa-ticket',
        'menu' => [
            'taocan' => [
                'name' => '套餐管理',
                'link' => '/miniapp/taocan.taocan/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加酒店', 'link' => '/miniapp/taocan.taocan/create', 'is_show' => 0,],
                    [ 'name' => '修改酒店', 'link' => '/miniapp/taocan.taocan/edit', 'is_show' => 0,],
                    [ 'name' => '删除酒店', 'link' => '/miniapp/taocan.taocan/delete', 'is_show' => 0,],
                    [ 'name' => '酒店列表', 'link' => '/miniapp/taocan.taocan/index', 'is_show' => 0,],
                    [ 'name' => '酒店相册列表', 'link' => '/miniapp/taocan.taocan/photo', 'is_show' => 0,],
                    [ 'name' => '酒店相册添加', 'link' => '/miniapp/taocan.taocan/photosave', 'is_show' => 0,],
                    [ 'name' => '酒店相册更新排序', 'link' => '/miniapp/taocan.taocan/photoupdate', 'is_show' => 0,],
                    [ 'name' => '酒店相册删除', 'link' => '/miniapp/taocan.taocan/photodelete', 'is_show' => 0,],
                    [ 'name' => '酒店区域设置', 'link' => '/miniapp/taocan.taocan/regionselect', 'is_show' => 0,],
                    [ 'name' => '酒店区域设置', 'link' => '/miniapp/taocan.taocan/region', 'is_show' => 0,],
                    [ 'name' => '酒店上下架', 'link' => '/miniapp/taocan.taocan/online', 'is_show' => 0,],
                    [ 'name' => '酒店选择器', 'link' => '/miniapp/taocan.taocan/select', 'is_show' => 0,],
                ],
            ],
            'package' => [
                'name' => '套餐内容管理',
                'link' => '/miniapp/taocan.package/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加酒店', 'link' => '/miniapp/taocan.package/create', 'is_show' => 0,],
                ],
            ],
            'destination' => [
                'name' => '目的地推荐',
                'link' => '/miniapp/taocan.destination/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加酒店', 'link' => '/miniapp/taocan.destination/create', 'is_show' => 0,],
                ],
            ],
        ],
    ],
    'toutiao' => [
        'name' => '发现文章管理',
        'icon' => 'fa-file-text',
        'menu' => [
            'nav' => [
                'name' => '文章分类',
                'link' => '/miniapp/toutiao.nav/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '新增分类', 'link' => 'user.user/create', 'is_show' => 0,],
                    [ 'name' => '分类列表管理', 'link' => 'user.user/index', 'is_show' => 0,],
                    [ 'name' => '编辑分类', 'link' => 'user.user/edit', 'is_show' => 0,],
                    [ 'name' => '删除分类', 'link' => 'user.user/delete', 'is_show' => 0,],
                ],
            ],
            'toutiao' => [
                'name' => '文章管理',
                'link' => '/miniapp/toutiao.toutiao/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '新增头条', 'link' => 'toutiao.toutiao/create', 'is_show' => 0,],
                    [ 'name' => '头条管理', 'link' => 'toutiao.toutiao/index', 'is_show' => 0,],
                    [ 'name' => '头条编辑', 'link' => 'toutiao.toutiao/edit', 'is_show' => 0,],
                    [ 'name' => '头条删除', 'link' => 'toutiao.toutiao/delete', 'is_show' => 0,],
                ],
            ],
            '评论管理' => [
                'name' => '文章评论',
                'link' => '/miniapp/toutiao.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '评论管理', 'link' => 'toutiao.comment/index', 'is_show' => 0,],
                    [ 'name' => '评论删除', 'link' => 'toutiao.comment/delete', 'is_show' => 0,],
                ],
            ],
        ],
    ],
    'order' => [
        'name' => '订单管理',
        'icon' => 'fa-shopping-cart',
        'menu' => [
            'taocan' => [
                'name' => '订单管理',
                'link' => '/miniapp/taocan.order/index',
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
                'link' => '/miniapp/taocan.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/taocan.comment/index', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/taocan.comment/edit', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/taocan.comment/delete', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/taocan.comment/create', 'is_show' => 0,],
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
                'link' => '/miniapp/taocan.taocanorder/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 1, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '商家增长数报表', 'link' => '/miniapp/taocan.report/store', 'is_show' => 1,],
                    [ 'name' => '会员增长数报表', 'link' => '/miniapp/taocan.report/user', 'is_show' => 1,],
                    [ 'name' => '订单销量报表', 'link' => '/miniapp/taocan.report/order', 'is_show' => 1,],
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
            'jiesuan' => [
                'name' => '结算统计',
                'link' => '/miniapp/taocan.count/count',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
];
return $protected;