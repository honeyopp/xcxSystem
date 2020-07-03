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
                'sub' => [
                ],
            ],
            'baojia' => [
                'name' => 'Banner设置',
                'link' => '/miniapp/fitment.banner/photo',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
            'photo' => [
                'name' => '图库(代表作)',
                'link' => '/miniapp/fitment.photo/photo',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
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
    'anli' => [
        'name' => '经典案例',
        'icon' => 'fa-archive',
        'menu' => [
            'area' => [
                'name' => '经典案例',
                'link' => '/miniapp/fitment.example/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
    'company' => [
        'name' => '优惠活动',
        'icon' => 'fa-life-bouy',
        'menu' => [
            'area' => [
                'name' => '小区团装',
                'link' => '/miniapp/fitment.group/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'area1' => [
                'name' => '优惠活动',
                'link' => '/miniapp/fitment.activi/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
    'shejishi' => [
        'name' => '设计师',
        'icon' => 'fa-paint-brush',
        'menu' => [
            'nav' => [
                'name' => '设计师管理',
                'link' => '/miniapp/fitment.designer/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
        ],
    ],
    'xiaoguotu' => [
        'name' => '看工地',
        'icon' => 'fa-paypal',
        'menu' => [
            'nav' => [
                'name' => '看工地',
                'link' => '/miniapp/fitment.work/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
    'kangongdi' => [
        'name' => '装修资讯',
        'icon' => 'fa-file-text',
        'menu' => [
            'nav' => [
                'name' => '资讯分类',
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

        ],
    ],

    'shenqing' => [
        'name' => '预约管理',
        'icon' => 'fa-clock-o',
        'menu' => [
            'xiaochengxu' => [
                'name' => '装修预约',
                'link' => '/miniapp/fitment.yvyue/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
            'xiaoqu' => [
                'name' => '小区团装预约',
                'link' => '/miniapp/fitment.encroll/group',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
            'huodong' => [
                'name' => '活动预约',
                'link' => '/miniapp/fitment.encroll/activit',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
            'shejishi' => [
                'name' => '设计师预约',
                'link' => '/miniapp/fitment.encroll/shejishi',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [

                ],
            ],
            'gongdi' => [
                'name' => '工地参观预约',
                'link' => '/miniapp/fitment.encroll/work',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
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
