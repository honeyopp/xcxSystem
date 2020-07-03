<?php
//民宿功能配置; 引入公共配置； 公共配置 变量名 $public
require 'youge/extra/public.php';
$public['setting']['menu']['banner'] = [
    'name' => 'banner设置',
    'link' => '/miniapp/minsu.banner/index',
    'is_show' => 1, //是否显示菜单
    'is_sub' => 0, //是否显示下级菜单
    'sub' => [
        [ 'name' => '添加小程序', 'link' => '/miniapp/minsu.banner/create', 'is_show' => 0,],
        [ 'name' => '小程序列表', 'link' => '/miniapp/minsu.banner/index', 'is_show' => 0,],
        [ 'name' => '修改小程序', 'link' => '/miniapp/minsu.banner/edit', 'is_show' => 0,],
        [ 'name' => '删除小程序', 'link' => '/miniapp/minsu.banner/delete', 'is_show' => 0,],
    ],

];
//民宿私有的功能配置
$protected = [
    'minsu' => [
        'name' => '民宿',
        'icon' => 'fa-bed',
        'menu' => [
            'minsu' => [
                'name' => '民宿管理',
                'link' => '/miniapp/minsu.minsu/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加民宿', 'link' => '/miniapp/minsu.minsu/create', 'is_show' => 0,],
                    [ 'name' => '修改民宿', 'link' => '/miniapp/minsu.minsu/edit', 'is_show' => 0,],
                    [ 'name' => '删除民宿', 'link' => '/miniapp/minsu.minsu/delete', 'is_show' => 0,],
                    [ 'name' => '民宿列表', 'link' => '/miniapp/minsu.minsu/index', 'is_show' => 0,],
                    [ 'name' => '民宿相册列表', 'link' => '/miniapp/minsu.minsu/photo', 'is_show' => 0,],
                    [ 'name' => '民宿相册添加', 'link' => '/miniapp/minsu.minsu/photosave', 'is_show' => 0,],
                    [ 'name' => '民宿相册更新排序', 'link' => '/miniapp/minsu.minsu/photoupdate', 'is_show' => 0,],
                    [ 'name' => '民宿相册删除', 'link' => '/miniapp/minsu.minsu/photodelete', 'is_show' => 0,],
                    [ 'name' => '民宿区域设置', 'link' => '/miniapp/minsu.minsu/regionselect', 'is_show' => 0,],
                    [ 'name' => '民宿区域设置', 'link' => '/miniapp/minsu.minsu/region', 'is_show' => 0,],
                    [ 'name' => '民宿上下架', 'link' => '/miniapp/minsu.minsu/online', 'is_show' => 0,],
                    [ 'name' => '民宿选择器', 'link' => '/miniapp/minsu.minsu/select', 'is_show' => 0,],
                ],
            ],
            'room' => [
                'name' => '民宿房间',
                'link' => '/miniapp/minsu.room/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '民宿房间列表', 'link' => '/miniapp/minsu.room/index', 'is_show' => 0,],
                    [ 'name' => '添加民宿房间', 'link' => '/miniapp/minsu.room/create', 'is_show' => 0,],
                    [ 'name' => '删除民宿房间', 'link' => '/miniapp/minsu.room/delete', 'is_show' => 0,],
                    [ 'name' => '修改民宿房间', 'link' => '/miniapp/minsu.room/edit', 'is_show' => 0,],
                ],
            ],
            'baner' => [
                'name' => '民宿品牌',
                'link' => '/miniapp/minsu.minsubrand/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '民宿房间列表', 'link' => '/miniapp/minsu.room/index', 'is_show' => 0,],
                    [ 'name' => '添加民宿房间', 'link' => '/miniapp/minsu.room/create', 'is_show' => 0,],
                    [ 'name' => '删除民宿房间', 'link' => '/miniapp/minsu.room/delete', 'is_show' => 0,],
                    [ 'name' => '修改民宿房间', 'link' => '/miniapp/minsu.room/edit', 'is_show' => 0,],
                ],
            ],
            'special' => [
                'name' => '民宿专题',
                'link' => '/miniapp/minsu.minsuspecial/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '民宿房间列表', 'link' => '/miniapp/minsu.room/index', 'is_show' => 0,],
                    [ 'name' => '添加民宿房间', 'link' => '/miniapp/minsu.room/create', 'is_show' => 0,],
                    [ 'name' => '删除民宿房间', 'link' => '/miniapp/minsu.room/delete', 'is_show' => 0,],
                    [ 'name' => '修改民宿房间', 'link' => '/miniapp/minsu.room/edit', 'is_show' => 0,],
                ],
            ],
        ],
    ],
    'order' => [
        'name' => '订单管理',
        'icon' => 'fa-shopping-cart',
        'menu' => [
            'minsu' => [
                'name' => '订单管理',
                'link' => '/miniapp/minsu.minsuorder/index',
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
                'link' => '/miniapp/minsu.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => '/miniapp/minsu.comment/index', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => '/miniapp/minsu.comment/edit', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => '/miniapp/minsu.comment/delete', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => '/miniapp/minsu.comment/create', 'is_show' => 0,],
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
                'link' => '/miniapp/minsu.minsuorder/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 1, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '商家增长数报表', 'link' => '/miniapp/minsu.report/store', 'is_show' => 1,],
                    [ 'name' => '会员增长数报表', 'link' => '/miniapp/minsu.report/user', 'is_show' => 1,],
                    [ 'name' => '订单销量报表', 'link' => '/miniapp/minsu.report/order', 'is_show' => 1,],
                ],
            ],
            'paiming' => [
                'name' => '排名统计',
                'link' => '/miniapp/minsu.comment/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 1, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '民宿排名', 'link' => '/miniapp/minsu.ranking/minsu', 'is_show' => 1,],
                    [ 'name' => '房间排名', 'link' => '/miniapp/minsu.ranking/room', 'is_show' => 1,],
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
                'link' => '/miniapp/minsu.count/count',
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