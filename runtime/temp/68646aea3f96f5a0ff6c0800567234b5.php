<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:62:"D:\phpstudy_pro\WWW\cms/youge/admin\view\admin\admin\edit.html";i:1503555816;s:52:"D:\phpstudy_pro\WWW\cms/youge/admin\view\layout.html";i:1513522666;s:59:"D:\phpstudy_pro\WWW\cms/youge/admin\view\public\header.html";i:1514657874;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<title>微点应用后台管理中心</title>
	<link href="/public/admin/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/admin/css/style.min.css" rel="stylesheet">
<link href="/public/admin/css/login.min.css" rel="stylesheet">
<script src="/public/admin/js/jquery.min.js"></script>
<script src="/public/admin/js/jquery-ui-1.10.4.min.js"></script>
<script src="/public/admin/js/jquery-ui.custom.min.js"></script>
<script src="/public/common/layer/layer.js" type="text/javascript"></script>
<script src="/public/common/laytpl.js" type="text/javascript"></script>
<script src="/public/common/laydate/laydate.js" type="text/javascript"></script>
<link href="/public/admin/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
<link href="/public/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
<link href="/public/admin/css/animate.min.css" rel="stylesheet">
<link href="/public/admin/css/style.min862f.css?v=4.1.0" rel="stylesheet">
<!--link href="/public/admin/css/mest.css" rel="stylesheet"-->
<script src="/public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/public/admin/js/admin.js"></script>
<script src="/public/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/public/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script type="text/javascript" src="/public/admin/js/contabs.min.js"></script>
<script src="/public/admin/js/plugins/pace/pace.min.js"></script>

</head>
<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-content">
                <div class="row">
                   <form  action="<?=url('admin.admin/edit',['admin_id'=>$detail->admin_id])?>" id="form-create" method="post" class="form-horizontal" role="form"> 
         <div class="form-group">
    <label class="col-sm-3 control-label no-padding-right">用户名：</label>
    <div class="col-sm-9">
            <?=$detail->username?>
    </div>
 </div>            
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right">密码：</label>
    <div class="col-sm-9">
            <input type="password"  value="******" placeholder="" id="password" name="password"  class="col-xs-10 col-sm-5" /> 
    </div>
 </div>
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right"><span class="c-red">*</span>角色：</label>
    <div class="col-sm-9">
        <select class="form-control" name="role_id" id="role_id"> 
            <option value="0">请选择</option>
                <?php foreach($roles as $val) {?>
                <option <?php if($val['role_id'] == $detail->role_id){?> selected="selected" <?php }?> value="<?=$val['role_id']?>"><?=$val['role_name']?></option>
                <?php }?>
        </select>
        
    </div>
 </div>
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right"><span class="c-red">*</span>真是姓名：</label>
    <div class="col-sm-9">
            <input type="text"  value="<?=isset($detail->real_name)?$detail->real_name:''?>" placeholder="" id="real_name" name="real_name"  class="col-xs-10 col-sm-5" /> 
    </div>
 </div>
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right"><span class="c-red">*</span>手机号码：</label>
    <div class="col-sm-9">
            <input type="text"  value="<?=isset($detail->mobile)?$detail->mobile:''?>" placeholder="" id="mobile" name="mobile"  class="col-xs-10 col-sm-5" /> 
    </div>
 </div>

                    <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                    <button mini="submit" for="form-create" class="btn btn-info" type="button">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            保存
                                    </button>

                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                            重置
                                    </button>
                            </div>
                    </div>
                   </form>

                </div></div>
        </div>
    </div>
</div>
        
        
        <script src="/public/admin/js/content.min.js?v=1.0.0"></script>
        <script src="/public/admin/js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function(){
                $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
            });
        </script>
        <style>
            .c-red{color: red;};
        </style>
</body>
</html>
