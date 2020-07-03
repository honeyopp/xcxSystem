<?php

return [
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
                'is_sub' => 1, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '城市设置', 'link' => '/miniapp/setting.city/index', 'is_show' => 1,],
                    [ 'name' => '区域设置', 'link' => '/miniapp/setting.region/index', 'is_show' => 1,],
                ],
            ],
        ],
    ],


];
