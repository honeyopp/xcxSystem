<?php

namespace app\common\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [['username','require', '用户名不能为空'],
['role_id','require', '角色不能为空'],
['real_name','require', '真是姓名不能为空'],
['mobile','require', '手机号码不能为空'],
];
    
    protected $scene = ["create"=>["username","role_id","real_name","mobile",],"edit"=>["role_id","real_name","mobile",]];
}