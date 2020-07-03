<?php

return [
    /* 系统生成开始 */
    'system' => [
        'name' => '系统',
        'icon' => 'fa-desktop',
        'menu' => [
            'admin' => [
                'name' => '管理员',
                'link' => 'admin.admin/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单   
                'sub' => [
                    [ 'name' => '添加管理员', 'link' => 'admin.admin/create', 'is_show' => 0,],
                    [ 'name' => '管理员列表', 'link' => 'admin.admin/index', 'is_show' => 1,],
                    [ 'name' => '编辑管理员', 'link' => 'admin.admin/edit', 'is_show' => 0,],
                    [ 'name' => '锁定管理员', 'link' => 'admin.admin/lock', 'is_show' => 0,],
                    [ 'name' => '解锁管理员', 'link' => 'admin.admin/unlock', 'is_show' => 0,],
                    [ 'name' => '授权', 'link' => 'admin.admin/menus', 'is_show' => 0,]
                ],
            ],
            'role' => [
                'name' => '角色管理',
                'link' => 'admin.role/index',
                'is_show' => 1,
                'is_sub' => 0,
                'sub' => [
                    [ 'name' => '添加角色', 'link' => 'admin.role/create', 'is_show' => 0,],
                    ['name' => '角色列表', 'link' => 'admin.role/index', 'is_show' => 1,],
                    ['name' => '编辑角色', 'link' => 'admin.role/edit', 'is_show' => 0,],
                    [ 'name' => '删除角色', 'link' => 'admin.role/delete', 'is_show' => 0,]
                ],
            ],
            'agent' => [
                'name' => '代理商管理',
                'link' => 'setting.agent/agent',
                'is_show' => 1,
                'is_sub' => 0,
                'sub' => [
                    [ 'name' => '添加代理商', 'link' => 'setting.agent/create', 'is_show' => 0,],
                    ['name' => '代理商列表', 'link' => 'setting.agent/index', 'is_show' => 1,],
                    ['name' => '修改代理商', 'link' => 'setting.agent/edit', 'is_show' => 0,],
                    [ 'name' =>'删除代理商', 'link' => 'setting.agent/delete', 'is_show' => 0,]
                ],
            ],
			  
        ],
    ],
    'user' => [
        'name' => '用户管理',
        'icon' => 'fa-user',
        'menu' => [
            'admin' => [
                'name' => '用户管理',
                'link' => 'member.member/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加用户', 'link' => 'user.user/create', 'is_show' => 0,],
                    [ 'name' => '用户列表', 'link' => 'user.user/list', 'is_show' => 0,],
                    [ 'name' => '修改用户', 'link' => 'user.user/edit', 'is_show' => 0,],
                    [ 'name' => '删除用户', 'link' => 'user.user/delete', 'is_show' => 0,],
                ],
            ],
        ],
    ],
    'miniapp' => [
        'name' => '小程序管理',
        'icon' => 'fa-weixin',
        'menu' => [
            'miniapp' => [
                'name' => '小程序管理',
                'link' => 'miniapp.miniapp/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => 'user.user/create', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => 'user.user/list', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => 'user.user/edit', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => 'user.user/delete', 'is_show' => 0,],
                ],
            ],
            'authorizer' => [
                'name' => '小程序授权管理',
                'link' => 'miniapp.authorizer/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '授权列表', 'link' => 'miniapp.authorizer/list', 'is_show' => 0,],
                    [ 'name' => '添加授权', 'link' => 'miniapp.authorizer/edit', 'is_show' => 0,],
                    [ 'name' => '修改授权', 'link' => 'miniapp.authorizer/create', 'is_show' => 0,],
                    [ 'name' => '删除授权', 'link' => 'miniapp.authorizer/delete', 'is_show' => 0,],
                ],
            ],
        ],

    ],
    'news' => [
        'name' => '动态',
        'icon' => 'fa-cogs',
        'menu' => [
            'tools' => [
                'name' => '动态',
                'link' => 'news.news/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],

];
