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
                'link' => '/miniapp/setting.setting/setting',
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
            ]
        ],
    ],

    'toutiao' => [
        'name' => '定制游订单',
        'icon' => 'fa-weixin',
        'menu' => [
            'nav' => [
                'name' => '定制游订单',
                'link' => '/miniapp/customized.order/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '定制游订单', 'link' => 'customized.order/index', 'is_show' => 0,],
                  
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
