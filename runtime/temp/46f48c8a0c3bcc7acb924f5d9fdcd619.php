<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:65:"D:\phpstudy_pro\WWW\cms/youge/admin\view\setting\agent\agent.html";i:1508554196;s:52:"D:\phpstudy_pro\WWW\cms/youge/admin\view\layout.html";i:1513522666;s:59:"D:\phpstudy_pro\WWW\cms/youge/admin\view\public\header.html";i:1514657874;}*/ ?>
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
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>
                        请仔细阅读：如果设置的代理商中，有应用给用户 ，但是在此页面，删除了对应的代理商，则该用户的代理商级别回归到普通用户级别
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                    <form action="<?=url('setting.agent/agent')?>" id="form-create" method="post" class="form-horizontal" role="form">
                        <div id="type">
                        <?php if(empty($agent['agent'])) {?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"><span
                                        class="c-red">*</span>代理商：</label>
                                <div class="col-sm-2">
                                    代理商名称
                                    <input type="text" value="" placeholder="代理商的名称 如:普通代理商"
                                           name="data[agent_name][]" class="form-control"/>
                                </div>
                                <div class="col-sm-2">
                                    折扣
                                    <select class="form-control" name="data[price][]">
                                        <option value="10">一折</option>
                                        <option value="20">二折</option>
                                        <option value="30">三折</option>
                                        <option value="40">四折</option>
                                        <option value="50">五折</option>
                                        <option value="60">六折</option>
                                        <option value="70">七折</option>
                                        <option value="75">七五折</option>
                                        <option value="80">八折</option>
                                        <option value="85">八五折</option>
                                        <option value="90">九折</option>
                                        <option value="95">九五折</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <i style="margin-top: 5%;margin-right: 10px;cursor:pointer"
                                       class=" btn-add ace-icon fa fa-plus bigger-110 fa-3x"></i>
                                    <i style="margin-top: 5%;cursor:pointer"
                                       class="btn-del ace-icon fa  fa-minus bigger-110 fa-3x"></i>
                                </div>
                            </div>
                        <?php }else{ foreach($agent['agent'] as $val) { ?>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"><span
                                        class="c-red">*</span>代理商：</label>
                                <div class="col-sm-2">
                                    代理商名称
                                    <input type="text" value="<?=$val['name']?>" placeholder="代理商的名称 如:普通代理商"
                                           name="data[agent_name][]" class="form-control"/>
                                </div>
                                <div class="col-sm-2">
                                    折扣
                                    <select class="form-control" name="data[price][]">
                                        <option  <?=$val['discount'] == 10 ? 'selected' : ''?> value="10"> 一折 </option>
                                        <option <?=$val['discount'] == 20 ? 'selected' : ''?> value="20">二折</option>
                                        <option <?=$val['discount'] == 30 ? 'selected' : ''?> value="30">三折</option>
                                        <option <?=$val['discount'] == 40 ? 'selected' : ''?> value="40">四折</option>
                                        <option <?=$val['discount'] == 50 ? 'selected' : ''?> value="50">五折</option>
                                        <option <?=$val['discount'] == 60 ? 'selected' : ''?> value="60">六折</option>
                                        <option <?=$val['discount'] == 70 ? 'selected' : ''?> value="70">七折</option>
                                        <option <?=$val['discount'] == 75 ? 'selected' : ''?> value="75">七五折</option>
                                        <option <?=$val['discount'] == 80 ? 'selected' : ''?> value="80">八折</option>
                                        <option <?=$val['discount'] == 85 ? 'selected' : ''?> value="85">八五折</option>
                                        <option <?=$val['discount'] == 90 ? 'selected' : ''?> value="90">九折</option>
                                        <option <?=$val['discount'] == 95 ? 'selected' : ''?> value="95">九五折</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <i style="margin-top: 5%;margin-right: 10px;cursor:pointer"
                                       class=" btn-add ace-icon fa fa-plus bigger-110 fa-3x"></i>
                                    <i style="margin-top: 5%;cursor:pointer"
                                       class="btn-del ace-icon fa  fa-minus bigger-110 fa-3x"></i>
                                </div>
                            </div>
                        <?php } } ?>
                        </div>
                        <script>
                            $(document).on('click', '.btn-add', function () {
                                $("#type").append($(this).parent().parent().clone(true));
                            })
                            $(document).on('click', '.btn-del', function () {
                                if ($('.btn-add').length <= 1) {
                                    layer.msg('最少添加一个');
                                } else {
                                    var _this = $(this);
                                    layer.confirm('确定删除吗?', {
                                        btn: ['确定', '取消'] //按钮
                                    }, function () {
                                        layer.closeAll();//关闭所有的窗口
                                        _this.parent().parent().remove();
                                    });
                                }
                            })
                        </script>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button mini="submit" for="form-create" class="btn btn-info" type="button">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    确定保存
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
