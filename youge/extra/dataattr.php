<?php
return [
    'membertype' => [
            1 => '普通用户',
            2 => '普通代理商',
            3 => '合作伙伴',
            4 => 'OEM代理',
    ],
    'moneylognames' => [
        1 => '余额充值',
        2 => '模板购买',
        3 => '购买短信',
        4 => '提交押金',
    ],
    'smspaywaynames' => [
        1 => '充值短信',
        2 => '分配小程序',
    ],
    //1景点，2商圈，3行政区域，4车站，5高校，6医院
    'regionnames' => [
        1 => '景点',
        2 => '商圈',
        3 => '行政',
        4 => '车站',
        5 => '高校',
        6 => '医院',
    ],
    //字段名
    'regionfield' => [
        1 => 'scenic_spot',       //景点
        2 => 'area',              //商圈
        3 => 'administration',    //行政区域
        4 => 'station',           //车站
        5 => 'colleges',          //高校
        6 => 'hospital',          //医院
    ],
    /*
     * 酒店配置信息
     */
    'hotellevelnames' => [
        1 => '快捷酒店',
        2 => '五星级酒店',
        3 => '四星级酒店',
        4 => '三星级酒店',
        5 => '二星级酒店',
        6 => '一星级酒店',
        7 => '豪华级酒店',
        8 => '公寓客栈',
    ],

    'hotelroomtypenames' => [
        1 => '单人间',
        2 => '标准间',
        3 => '商务间',
        4 => '豪华间/高级间',
        5 => '套间',
        6 => '双套间',
        7 => '组合套间',
        8 => '多套间',
        9 => '高级套间',
        10 => '复试套间',
        11 => '总统套房',
        12 => '主题套间',
    ],
    //床的类型
    'hotelbedtype' => [
        1 => '榻榻米',
        2 => '单人床',
        3 => '双人床',
        4 => '大床',
        5 => '沙发',
        6 => '炕',
    ],

    'couponwaynames' => [
          1 => '抢红包活动',
          2 => '新用户登录',
          3 => '首单赠送',
    ],

    'coupontypenames' => [
        1 => '酒店预订',
        2 => '抢购',
        3 => '商城',
    ],
    'orderstatus' => [
        0 => '待支付',
        1 => '等待商家审核',
        2 => '待入住',
        3 => '取消订单',
        8 => '已完成',
    ],


    //民宿配置
    'minsubedtype' => [
        1 => '榻榻米',
        2 => '单人床',
        3 => '双人床',
        4 => '大床',
        5 => '沙发',
        6 => '炕',
    ],



//    农家乐配置；
    'baototoico' => [
         ['name' => '亲子','url'=>'20170810\25c3637ae4ba68a3576c240898e6db8a.png'],
         ['name' => '海滩','url'=>'20170810\a45cf9e6e3a1d3e87baf3a5be8aff17a.png'],
         ['name' => '山水','url'=>'20170810\854929171c0eb703f4f5de0e9fd2d84e.png'],
         ['name' => '轻度假','url'=>'20170810\65e6f14a31d4541fc9187105d0480f11.png'],
         ['name' => '温泉','url'=>'20170810\30cc0ac9d386f55a72eddaece9d26b93.png'],
         ['name' => '门票','url'=>'20170810\0359e96d69967917755e1f63d6710cbb.png'],
         ['name' => '主题公园','url'=>'20170810\5d85e44714bc8cb1e3723e4b837d1758.png'],
         ['name' => '动植物园','url'=>'20170810\efac56abb600a1a9be471f6d9b9b2cee.png'],
         ['name' => '溜娃攻略','url'=>'20170810\0e792bb7e9ffb8b1570f294009284deb.png'],
    ],


//理发ico
    'hari' => [
        ['url'=>'ico\hair\01.png'],
        ['url'=>'ico\hair\02.png'],
        ['url'=>'ico\hair\03.png'],
        ['url'=>'ico\hair\04.png'],
        ['url'=>'ico\hair\05.png'],
        ['url'=>'ico\hair\06.png'],
        ['url'=>'ico\hair\07.png'],
    ],

    'tongcheng' => [
        ['name' => '宠物市场','url'=>'ico\tongcheng\01.png'],
        ['name' => '宠物市场','url'=>'ico\tongcheng\02.png'],
        ['name' => '宠物市场','url'=>'ico\tongcheng\03.png'],
        ['name' => '促销活动','url'=>'ico\tongcheng\04.png'],
        ['name' => '二手车城','url'=>'ico\tongcheng\05.png'],
        ['name' => '二手车城','url'=>'ico\tongcheng\06.png'],
        ['name' => '促销活动','url'=>'ico\tongcheng\07.png'],
        ['name' => '促销活动','url'=>'ico\tongcheng\08.png'],
        ['name' => '商务服务','url'=>'ico\tongcheng\09.png'],
        ['name' => '装修建材','url'=>'ico\tongcheng\10.png'],
        ['name' => '生活服务','url'=>'ico\tongcheng\11.png'],
        ['name' => '生活服务','url'=>'ico\tongcheng\12.png'],
        ['name' => '办公大厦','url'=>'ico\tongcheng\13.png'],
        ['name' => '全职兼职','url'=>'ico\tongcheng\14.png'],
        ['name' => '全职兼职','url'=>'ico\tongcheng\15.png'],
        ['name' => '房源信息','url'=>'ico\tongcheng\16.png'],
    ],
    
    'taocantypenames' =>[
        1 => '套餐',
        2 => '酒店',
        3 => '门票',
        4 => '餐饮',
        6 => '线路',
        5 => '其他',

    ],


    //婚庆配置；
    'hunqingcat' => [
        1=>'婚纱摄影',
        2=>'婚礼策划',
        3=>'婚纱礼服',
        4=>'婚礼跟拍',
        5=>'新娘跟妆',
        6=>'婚宴酒店',
        7=>'婚车租赁',
        8=>'婚礼司仪',
    ],

    //装修配置；
    'zhuangxiupid' =>[
        1  => '装修公司',
        2  => '材料商',
    ],

   //拼团配置服务；
    'group' => [
        1 => [
            'id' => 1,
            'title' => '自营仓保税发货',
            'detail' => '本商品为商家自营保税发货',
        ],
        2 => [
            'id' => 2,
            'title'  => '12小时内发货',
            'detail' => '12小时内发货，最快24小时到货',
        ],
        3 => [
            'id' => 3,
            'title'  => '7天拆封无条件退货',
            'detail' => '本商品支持7天内拆封无条件退货',
        ],
        4 => [
            'id' => 4,
             'title' => '7天无理由退货',
             'detail' => '本商品支持7天无条件退货',
        ],
        5 => [
            'id' => 5,
            'title'  => '官方授权',
            'detail' => '由品牌官方授权予商家售卖'
        ],
        6 =>[
            'id' => 6,
            'title'  => '不支持退货',
            'detail' => '本商品不支持退货',
        ],
        7 => [
            'id' => 7,
            'title' => '不可换货',
            'detail' => '本商品不支持换货',
        ],
        8 => [
            'id' => 8,
            'title' => '极速退款',
            'detail' => '本商品支持客服极速退款'
        ],
        9 => [
            'id' => 9,
            'title' => '正品保证',
            'detail' => '本商品正品保证假一赔十'
        ],
    ],


    'delivery' =>[
        1 =>  '工作日/双休/节假日均可收货',
        2 =>  '仅工作日收货',
        3 =>  '双休/节假日均可收货',
    ],
    'jzfltype' => [
        1 => '维修类服务',
        2 => '阿姨服务'
    ],
    'jzaytype' =>[
        1 => '家政保姆',
        2 => '月嫂育婴',
    ],

    'jzorder' => [
        0 => '待付款',
        1 => '等待接单',
        2 => '已接单',
        3 => '拒绝订单',
        4 => '申请退款',
        5 => '已退款',
        6 => '已取消',
        8 => '已完成',
    ],


    'pinche'=>[
        1 => '人找车',
        2 => '车找人',
    ]
];