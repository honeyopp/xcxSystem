<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:67:"/www/wwwroot/xcx.cpasem.com/youge/admin/view/admin/admin/index.html";i:1508241838;s:56:"/www/wwwroot/xcx.cpasem.com/youge/admin/view/layout.html";i:1513522666;s:63:"/www/wwwroot/xcx.cpasem.com/youge/admin/view/public/header.html";i:1514657874;}*/ ?>
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



    <!-- PAGE CONTENT BEGINS -->
    <div class="row">

        <div class="col-xs-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>搜索</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="table-responsive">
                            <form method="get"  action="<?=url('admin.admin/index')?>" role="form">
                                <table class="table table-striped">
                                    <tr>
                                        <td style=" width:200px">请输入用户名<input name="username" id="username" class="input-sm form-control"  value="<?=$search['username']?>" type="text"  placeholder=""/></td>
                                        <td style=" width:200px">请输入手机号码<input name="mobile" id="mobile"  class="input-sm form-control"  value="<?=$search['mobile']?>" type="text"  placeholder=""/></td>

                                        <td>
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    搜索
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                </table>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

                <div class="col-xs-12">

                            <div class="ibox-content">
                                <div class="row">
                                    <div class="tableTools-container">
                                        <div class="tableTools-container">
                                            <a title="添加管理员" mini="load" w="1000px" h="800px" href="<?=url('admin.admin/create')?>"  class="btn btn-sm btn-success"><i class=" fa fa-plus"></i>添加管理员</a>
                                            <a mini="list" for="mini_list" title="批量锁定管理员" href="<?=url('admin.admin/lock')?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>批量锁定</a>

                                        </div>
                                    </div>
                                    <div class="table-responsive">

                                        <table id="simple-table"  class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="center">
                                                        <label class="pos-rel">
                                                            <input type="checkbox" class="ace" />
                                                            <span class="lbl"></span>
                                                        </label>
                                                    </th>
                                                    <th>ID</th>
                                                    <th>用户名</th>
                                                    <th>角色</th>
                                                    <th>真是姓名</th>
                                                    <th>手机号码</th>
                                                    <th>创建时间</th>

                                                    <th >操作</th>


                                                </tr>
                                            </thead>
                                            <form  id="mini_list">
                                                <tbody>
                                                    <?php foreach($list as $val){?>
                                                    <tr>
                                                        <td class="center">
                                                            <label class="pos-rel">
                                                                <input id="admin_id_<?=$val->admin_id?>" name="admin_id[]" value="<?=$val->admin_id?>" type="checkbox" class="ace" />
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </td>

                                                        <td><?=$val->admin_id?></td>
                                                        <td><?=$val->username?></td>
                                                        <td><?php echo isset($roles[$val['role_id']]) ? $roles[$val['role_id']]['role_name'] : '未分组';?></td>
                                                        <td><?=$val->real_name?></td>
                                                        <td><?=$val->mobile?></td>
                                                        <td><?=date("Y-m-d H:i:s",$val->add_time)?></td>


                                                        <td>
                                                            <div class="hidden-sm hidden-xs btn-group">
                                                                <a title="编辑管理员" mini="load"  w="1000px" h="800px"  href="<?=url('admin.admin/edit','admin_id='.$val->admin_id)?>"  class="btn btn-xs btn-info" ><i class=" fa fa-edit bigger-120"></i>编辑</a>

                                                                <?php if($val['is_delete'] ==0){?>
                                                                <a title="锁定管理员" mini="act"  href="<?=url('admin.admin/lock','admin_id='.$val->admin_id)?>"  class="btn btn-xs btn-warning"><i class="fa fa-lock bigger-120"></i>锁定</a>
                                                                <?php }else{?>
                                                                <a title="解锁管理员" mini="act"  href="<?=url('admin.admin/unlock','admin_id='.$val->admin_id)?>"  class="btn btn-xs btn-warning"><i class="fa fa-unlock bigger-120"></i>解锁</a>

                                                                <?php }if($val['role_id']!=1){?>
                                                                <a title="授权<?=$val->username?>" mini="load" w="1000px" h="600px" href="<?=url('admin.admin/menus','admin_id='.$val->admin_id)?>"  class="btn btn-xs btn-danger" ><i class="fa fa-key bigger-120"></i>授权</a> 
                                                                <?php }?>
                                                            </div>                                      
                                                        </td>
                                                    </tr>
                                                    <?php }?>

                                                </tbody>
                                            </form>
                                        </table>
                                    </div>

                                    <div>
                                        <?php echo $page;?>      
                                    </div>


                            </div><!-- /.row -->




                        </div><!-- /.main-container -->


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
