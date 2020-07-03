<?php

namespace app\common\validate;

use think\Validate;

class Demo extends Validate
{
    protected $rule = [['title','require', '标题不能为空'],
['orderby','require', '排序不能为空'],
['photo','require', '图片不能为空'],
['photos','require', '图片组不能为空'],
['details','require', '详情不能为空'],
['cat_id','require', '分类ID不能为空'],
];
    
    protected $scene = ["create"=>["title","orderby","photo","photos","details","cat_id",],"edit"=>["title","orderby","photo","photos","details","cat_id",]];
}