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

    'company' => [
        'name' => '商家114管理',
        'icon' => 'fa-weixin',
        'menu' => [
            'cat' => [
                'name' => '商家分类',
                'link' => '/miniapp/company.cat/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '新增分类', 'link' => 'company.cat/create', 'is_show' => 0,],
                    [ 'name' => '分类列表管理', 'link' => 'company.cat/index', 'is_show' => 0,],
                    [ 'name' => '编辑分类', 'link' => 'company.cat/edit', 'is_show' => 0,],
                    [ 'name' => '删除分类', 'link' => 'company.cat/delete', 'is_show' => 0,],
                ],
            ],
             'area' => [
                'name' => '商家区域',
                'link' => '/miniapp/company.area/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '新增区域', 'link' => 'company.area/create', 'is_show' => 0,],
                    [ 'name' => '区域列表管理', 'link' => 'company.area/index', 'is_show' => 0,],
                    [ 'name' => '编辑区域', 'link' => 'company.area/edit', 'is_show' => 0,],
                    [ 'name' => '删除区域', 'link' => 'company.area/delete', 'is_show' => 0,],
                ],
            ],
            'company' => [
                'name' => '商家列表',
                'link' => '/miniapp/company.company/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '新增商家', 'link' => 'company.company/create', 'is_show' => 0,],
                    [ 'name' => '商家管理', 'link' => 'company.company/index', 'is_show' => 0,],
                    [ 'name' => '商家编辑', 'link' => 'company.company/edit', 'is_show' => 0,],
                    [ 'name' => '商家删除', 'link' => 'company.company/delete', 'is_show' => 0,],
                ],
            ],
             'yuyue' => [
                'name' => '预约商家',
                'link' => '/miniapp/company.yuyue/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '预约管理', 'link' => 'company.yuyue/index', 'is_show' => 0,],
                    [ 'name' => '预约删除', 'link' => 'company.yuyue/delete', 'is_show' => 0,],
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
