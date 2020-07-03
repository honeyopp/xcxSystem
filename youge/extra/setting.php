<?php
//系统配置
return [
   
    //  最小充值不得低于 单位分
    'min_money' => '1000',
    //押金数量
    'deposit_num' => '20000',
    //服务周期 单位天;
    'service_day' => '365',
    //最小分配 短信条数 AND 充值 最小条数
    'min_sms_num' => '100',
    //每条短信价格； 单位分;
    'sms_price' => '5',
    //模板低于多少天 警告用户；
    'miniapp_warning_day' => 15,
    //缩略图配置 ；
    'attachs' => [
        'city_photo' => [
            'small' => '150X150',
            'middle' => '640X320',
        ],
        'miniapp' => [
            'small' => '150X150',
            'middle' => '254X448',
        ]
    ]
];