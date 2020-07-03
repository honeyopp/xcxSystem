<?php

return [
    /* 系统生成开始 */
    'miniapp' => [
        'name' => '我管理的小程序',
        'icon' => 'fa-weixin',
        'menu' => [
            'miniapp' => [
                'name' => '小程序管理',
                'link' => '/manage/miniapp/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                    [ 'name' => '添加小程序', 'link' => 'user.user/create', 'is_show' => 0,],
                    [ 'name' => '小程序列表', 'link' => 'user.user/list', 'is_show' => 0,],
                    [ 'name' => '修改小程序', 'link' => 'user.user/edit', 'is_show' => 0,],
                    [ 'name' => '删除小程序', 'link' => 'user.user/delete', 'is_show' => 0,],
                ],
            ],
        ],
    ],
    'minappshop' => [
        'name' => '模板商城',
        'icon' => 'fa-shopping-cart',
        'menu' => [
            'miniapp' => [
                'name' => '模板商城',
                'link' => '/manage/miniappshop/index',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [
                ],
            ],
        ],
    ],
    'member' => [
        'name' => '账户管理',
        'icon' => 'fa-user',
       'menu' => [
            'miniapp' => [
                'name' => '账户管理',
                'link' => '/manage/member/index',
				
                'is_show' => 1, //是否显示菜单
                 'is_sub' => 0, //是否显示下级菜单
                 'sub' => [
                     [ 'name' => '账户管理', 'link' => '/manage/member/index', 'is_show' => 0,], 
					 [ 'name' => '快速充值', 'link' => '/manage/money/recharge', 'is_show' => 1,], 					 
                ],
            ],
			'recharge' => [
                'name' => '快速充值',
                'link' => '/manage/money/recharge',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
			'lists' => [
                'name' => '充值订单',
                'link' => '/manage/money/lists',
                'is_show' => 1, //是否显示菜单
                'is_sub' => 0, //是否显示下级菜单
                'sub' => [],
            ],
       ],
    ],
	
	 // 'daili' => [
    //    'name' => '代理商资料',
     //   'icon' => 'fa-viacoin',
     //  'menu' => [
     //       'miniapp' => [
      //          'name' => '代理商资料',
     //          'link' => '/manage/member/usertext',
      //          'is_show' => 1, //是否显示菜单
       //          'is_sub' => 0, //是否显示下级菜单
         //       'sub' => [
           //          [ 'name' => '代理商资料', 'link' => '/manage/member/usertext', 'is_show' => 1,],  
//
  //              ],
    //        ],
      // ],
    //],
	
	
//    'help' => [ 我的账户
 
 
//        'name' => '帮助中心',
//        'icon' => 'fa-question-circle',
//        'menu' => [
//            'text' => [
//                'name' => '图文教程',
//                'link' => '',
//                'is_show' => 1, //是否显示菜单
//                'is_sub' => 1, //是否显示下级菜单
//                'sub' => [
//                    [ 'name' => '第一步:申请小程序', 'link' => '/manage/help.text/create', 'is_show' => 1,],
//                    [ 'name' => '第二步:官方后台配置', 'link' => '/manage/help.text/list', 'is_show' => 1,],
//                    [ 'name' => '第三步:微小宝后台配置', 'link' => '/manage/help.text/edit', 'is_show' => 1,],
//                ],
//            ],
//            'void' => [
//                'name' => '视频教程(优酷播放)',
//                'link' => '/manage/help.void/index',
//                'is_show' => 1, //是否显示菜单
//                'is_sub' => 0, //是否显示下级菜单
//                'sub' => [],
//            ],
//            'faq' => [
//                'name' => '疑问解答',
//                'link' => '/manage/help.faq/index',
//                'is_show' => 1, //是否显示菜单
//                'is_sub' => 0, //是否显示下级菜单
//                'sub' => [
//                ],
//            ],
//            'about' => [
//                'name' => '人工服务:第一次免费',
//                'link' => '/manage/help.faq/index',
//                'is_show' => 1, //是否显示菜单
//                'is_sub' => 0, //是否显示下级菜单
//                'sub' => [
//                ],
//            ],
//        ],
//
//    ],
];
