<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:62:"D:\phpstudy_pro\WWW\cms/youge/admin\view\admin\role\index.html";i:1503555816;s:52:"D:\phpstudy_pro\WWW\cms/youge/admin\view\layout.html";i:1513522666;s:59:"D:\phpstudy_pro\WWW\cms/youge/admin\view\public\header.html";i:1514657874;}*/ ?>
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
                            <form method="get" action="<?=url('admin.role/index')?>" role="form">
                                <table class="table table-striped">
                                    <tr>
                                        <td style="width: 300px;">
                                            <input type="text"  placeholder="请输入关键词"   name="role_name" id="role_name"  value="<?=$search['role_name']?>"  class="input-sm form-control" />
                                        </td>
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
                    <div class=" tableTools-container">
                        <a  title="添加角色" mini="load" w="800px" h="500px" href="<?=url('admin.role/create')?>"  class="btn btn-sm btn-success">
                            <i class="ace-icon  fa glyphicon-plus align-top bigger-125"></i>
                            添加角色
                        </a>
                        <a mini="list" for="mini_list" title="批量删除" href="<?=url('admin.role/delete')?>"   class="btn btn-sm btn-danger">
                            <i class="ace-icon  fa fa-trash-o align-top bigger-125"></i>
                            批量删除
                        </a>     
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="center">
                                        <label class="pos-rel">
                                            <input type="checkbox"  class="ace" />
                                            <span class="lbl"></span>
                                        </label>
                                    </th>
                                    <th class="center">
                                        ID
                                    </th>
                                    <th class="center">
                                        角色名
                                    </th>

                                    <th>操作</th>
                                </tr>
                            </thead>
                            <form id="mini_list">
                                <tbody  >
                                    <?php foreach($list as $val){?>
                                    <tr>
                                        <td class="center">
                                            <label class="pos-rel">
                                                <input type="checkbox" name="role_id[]" id="role_id[]" value="<?=$val->role_id?>" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </td>
                                        <td  class="center"><?=$val->role_id?></td>
                                        <td  class="center"><?=$val->role_name?></td>


                                        <td>
                                            <div class="hidden-sm hidden-xs btn-group">
                                                <a title="编辑角色<?=$val->role_name?>" mini="load" w="800px" h="500px" href="<?=url('admin.role/edit','role_id='.$val->role_id)?>"   class="btn btn-xs btn-success">
                                                    <i class="ace-icon fa fa-edit bigger-120"></i>
                                                    编辑
                                                </a>
                                                <a   title="删除角色<?=$val->role_name?>" mini="act"  href="<?=url('admin.role/delete','role_id='.$val->role_id)?>"  class="btn btn-xs btn-danger">
                                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                    删除
                                                </a>     

                                                <a title="设置角色<?=$val->role_name?>" mini="load" w="800px" h="500px" href="<?=url('admin.role/setting','role_id='.$val->role_id)?>"   class="btn btn-xs btn-info">
                                                    <i class="ace-icon fa fa-cog bigger-120"></i>
                                                    设置
                                                </a>
                                            </div>                                      
                                        </td>
                                    </tr>
                                    <?php }?>

                                </tbody>
                            </form>
                        </table>
                    </div>
                </div>
                <div>
                    <?php echo $page;?>      
                </div>
            </div><!-- /.span -->


        </div><!-- /.row -->




    </div><!-- /.main-container -->
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
