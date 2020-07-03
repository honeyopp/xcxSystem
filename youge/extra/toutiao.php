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
        'name' => '头条',
        'icon' => 'fa-weixin',
        'menu' => [
            'nav' => [
                'name' => '头条分类',
                'link' => '/miniapp/toutiao.nav/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '新增分类', 'link' => 'toutiao.nav/create', 'is_show' => 0,],
                    [ 'name' => '分类列表管理', 'link' => 'toutiao.nav/index', 'is_show' => 0,],
                    [ 'name' => '编辑分类', 'link' => 'toutiao.nav/edit', 'is_show' => 0,],
                    [ 'name' => '删除分类', 'link' => 'toutiao.nav/delete', 'is_show' => 0,],
                ],
            ],
            'toutiao' => [
                'name' => '文章头条',
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
                'name' => '头条评论',
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
