<?php
//酒店功能配置; 引入公共配置； 公共配置 变量名 $public
require 'youge/extra/public.php';
//酒店私有的功能配置
$protected = [
    'hotel' => [
        'name' => '酒店',
        'icon' => 'fa-bed',
        'menu' => [
            'hotel' => [
                'name' => '酒店管理',
                'link' => '/miniapp/hotel.hotel/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加酒店', 'link' => '/miniapp/hotel.hotel/create', 'is_show' => 0,],
                    [ 'name' => '修改酒店', 'link' => '/miniapp/hotel.hotel/edit', 'is_show' => 0,],
                    [ 'name' => '删除酒店', 'link' => '/miniapp/hotel.hotel/delete', 'is_show' => 0,],
                    [ 'name' => '酒店列表', 'link' => '/miniapp/hotel.hotel/index', 'is_show' => 0,],
                    [ 'name' => '酒店相册列表', 'link' => '/miniapp/hotel.hotel/photo', 'is_show' => 0,],
                    [ 'name' => '酒店相册添加', 'link' => '/miniapp/hotel.hotel/photosave', 'is_show' => 0,],
                    [ 'name' => '酒店相册更新排序', 'link' => '/miniapp/hotel.hotel/photoupdate', 'is_show' => 0,],
                    [ 'name' => '酒店相册删除', 'link' => '/miniapp/hotel.hotel/photodelete', 'is_show' => 0,],
                    [ 'name' => '酒店区域设置', 'link' => '/miniapp/hotel.hotel/regionselect', 'is_show' => 0,],
                    [ 'name' => '酒店区域设置', 'link' => '/miniapp/hotel.hotel/region', 'is_show' => 0,],
                    [ 'name' => '酒店上下架', 'link' => '/miniapp/hotel.hotel/online', 'is_show' => 0,],
                    [ 'name' => '酒店选择器', 'link' => '/miniapp/hotel.hotel/select', 'is_show' => 0,],
                ],
            ],
            'room' => [
                'name' => '酒店房间',
                'link' => '/miniapp/hotel.room/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '酒店房间列表', 'link' => '/miniapp/hotel.room/index', 'is_show' => 0,],
                    [ 'name' => '添加酒店房间', 'link' => '/miniapp/hotel.room/create', 'is_show' => 0,],
                    [ 'name' => '删除酒店房间', 'link' => '/miniapp/hotel.room/delete', 'is_show' => 0,],
                    [ 'name' => '修改酒店房间', 'link' => '/miniapp/hotel.room/edit', 'is_show' => 0,],
                ],
            ],
            'baner' => [
                'name' => '酒店品牌',
                'link' => '/miniapp/hotel.hotelbrand/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '酒店房间列表', 'link' => '/miniapp/hotel.room/index', 'is_show' => 0,],
                    [ 'name' => '添加酒店房间', 'link' => '/miniapp/hotel.room/create', 'is_show' => 0,],
                    [ 'name' => '删除酒店房间', 'link' => '/miniapp/hotel.room/delete', 'is_show' => 0,],
                    [ 'name' => '修改酒店房间', 'link' => '/miniapp/hotel.room/edit', 'is_show' => 0,],
                ],
            ],
            'special' => [
                'name' => '出行特色',
                'link' => '/miniapp/hotel.hotelspecial/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '酒店房间列表', 'link' => '/miniapp/hotel.room/index', 'is_show' => 0,],
                    [ 'name' => '添加酒店房间', 'link' => '/miniapp/hotel.room/create', 'is_show' => 0,],
                    [ 'name' => '删除酒店房间', 'link' => '/miniapp/hotel.room/delete', 'is_show' => 0,],
                    [ 'name' => '修改酒店房间', 'link' => '/miniapp/hotel.room/edit', 'is_show' => 0,],
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
$setting = array_merge_recursive($public, $protected); //追加合并目录
return $setting ;