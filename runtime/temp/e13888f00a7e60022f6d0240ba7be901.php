<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:68:"D:\phpstudy_pro\WWW\cms/youge/admin\view\miniapp\describe\index.html";i:1503555818;s:52:"D:\phpstudy_pro\WWW\cms/youge/admin\view\layout.html";i:1513522666;s:59:"D:\phpstudy_pro\WWW\cms/youge/admin\view\public\header.html";i:1514657874;}*/ ?>
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
        <div class="col-xs-12">
            <div class="ibox-content">
                <div class="row">
                    <div class="tableTools-container">
                        <a title="添加小程序描述" href="<?=url('miniapp.describe/create',['miniapp_id'=>$miniapp->miniapp_id])?>" class="btn btn-sm btn-success"><i
                                class=" fa fa-plus"></i>添加小程序描述</a>
                        <a mini="list" for="mini_list" title="批量删除小程序描述" href="<?=url('miniapp.describe/delete')?>"
                           class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>批量删除</a>
                    </div>
                    <div class="table-responsive">
                        <table id="simple-table" class="table table-striped">
                            <thead>
                            <tr>
                                <th class="center">
                                    <label class="pos-rel">
                                        <input type="checkbox" class="ace"/>
                                        <span class="lbl"></span>
                                    </label>
                                </th>
                                <th>ID</th>
                                <th>小程序</th>
                                <th>描述内容</th>
                                <th>图片</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <form id="mini_list">
                                <tbody>
                                <?php foreach($list as $val){ ?>
                                <tr>
                                    <td class="center">
                                        <label class="pos-rel">
                                            <input id="describe_id_<?=$val->describe_id;?>" name="describe_id[]"
                                                   value="<?=$val->describe_id;?>" type="checkbox" class="ace"/>
                                            <span class="lbl"></span>
                                        </label>
                                    </td>
                                    <td><?= $val->describe_id ?></td>
                                    <td><?=$val->miniapp_id?></td>
                                    <td><textarea class="form-control"><?=$val->describe?></textarea></td>
                                    <td><?=empty($val->photo) ? '' : "<img width='80' src='/attachs/uploads/$val->photo'/>" ?></td>
                                    <td><?=$val->orderby?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs btn-group">
                                            <a title="编辑小程序描述"
                                               href="<?=url('miniapp.describe/edit','describe_id='.$val->describe_id)?>"
                                               class="btn btn-xs btn-info"><i class=" fa fa-edit bigger-120"></i>编辑</a>
                                            <a title="删除小程序描述" mini="act"
                                               href="<?=url('miniapp.describe/delete','describe_id='.$val->describe_id)?>"
                                               class="btn btn-xs btn-warning"><i
                                                    class="fa fa-trash bigger-120"></i>删除</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </form>
                        </table>
                        <div>
                            <?php echo $page; ?>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.main-container -->
            </div>
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
