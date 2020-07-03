<?php

namespace app\common\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [['username','require', '用户名不能为空'],




    
    protected $scene = ["create"=>["username","role_id","real_name","mobile",],"edit"=>["role_id","real_name","mobile",]];
}