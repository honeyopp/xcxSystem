<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:67:"D:\phpstudy_pro\WWW\cms/youge/admin\view\miniapp\miniapp\index.html";i:1504156166;s:52:"D:\phpstudy_pro\WWW\cms/youge/admin\view\layout.html";i:1513522666;s:59:"D:\phpstudy_pro\WWW\cms/youge/admin\view\public\header.html";i:1514657874;}*/ ?>
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
                            <form class="form-search" method="get" action="<?=url('miniapp.miniapp/index')?>"
                                  role="form">
                                <table>
                                    <tr>
                                        <td>小程序标题:<input class="form-control" name="title" id="title"
                                                         value="<?=$search['title']?>" type="text"
                                                         placeholder="请输入小程序标题" style=" width:200px"/></td>
                                        <td>价格:<input class="form-control" name="price" id="price" value="<?=empty($search['price']) ? '' : $search['price']?>" type="text" placeholder="请输入价格" style=" width:200px"/></td>
                                        <td>是否上架:
                                                <select name="is_online" class="form-control">
                                                    <option  <?=$search['is_online'] == 0 ? 'selected' : ''?> class="form-control" value="0">请选择</option>
                                                    <option  <?=$search['is_online'] == 1 ? 'selected' : ''?> class="form-control" value="1">上架</option>
                                                    <option  <?=$search['is_online'] == 2 ? 'selected' : ''?> class="form-control" value="2">未上架</option>
                                                </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <button type="submit" style="margin-top: 42%"
                                                        class="btn form-control btn-sm btn-primary">
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
                        <a title="添加小程序管理" href="<?=url('miniapp.miniapp/create')?>" class="btn btn-sm btn-success"><i class=" fa fa-plus"></i>添加小程序管理</a>
                        <a mini="list" for="mini_list" title="批量删除小程序管理" href="<?=url('miniapp.miniapp/delete')?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>批量删除</a>
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
                                <th>小程序标题</th>
                                <th>展示图片</th>
                                <th>版本号</th>
                                <th>体验天数</th>
                                <th>价格</th>
                                <th>活动价</th>
                                <th>模板</th>
                                <th>小程序目录</th>
                                <th>是否上架</th>
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
                                            <input id="miniapp_id_<?=$val->miniapp_id;?>" name="miniapp_id[]"
                                                   value="<?=$val->miniapp_id;?>" type="checkbox" class="ace"/>
                                            <span class="lbl"></span>
                                        </label>
                                    </td>
                                    <td><?= $val->miniapp_id ?></td>
                                    <td><?=$val->title?></td>
                                    <td><img width="80" src="/attachs/uploads/<?=$val->photo?>"/></td>
                                    <td><?=$val->version?></td>
                                    <td>可试用<?=$val->expire_day?>天</td>
                                    <td><?=sprintf("%.2f",$val->activity_price/100)?>￥</td>
                                    <td><?=sprintf("%.2f",$val->price/100)?>￥</td>
                                    <td><?=$val->template_id?></td>
                                    <td><?=$val->miniapp_dir?></td>
                                    <td><?=$val->is_online == 1  ? '<span class="label label-primary">是</span>' : '<span class="label label-danger">否</span>'  ?></td>
                                    <td><?=$val->orderby?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs btn-group">
                                            <a title="小程序介绍" mini="load" w="80%" h="80%" href="<?=url('miniapp.describe/index','miniapp_id='.$val->miniapp_id)?>" class="btn btn-xs btn-info"><i class=" fa fa-edit bigger-120"></i>小程序介绍</a>
                                      
                                            <a title="小程序示例图"  mini="load" w="80%" h="80%" href="<?=url('miniapp.miniapp/photo','miniapp_id='.$val->miniapp_id)?>" class="btn btn-xs btn-info"><i class=" fa fa-edit bigger-120"></i>小程序示例图</a>
                                            <a title="编辑小程序管理" href="<?=url('miniapp.miniapp/edit','miniapp_id='.$val->miniapp_id)?>" class="btn btn-xs btn-info"><i class=" fa fa-edit bigger-120"></i>编辑</a>
                                            <a title="删除小程序管理" mini="act" href="<?=url('miniapp.miniapp/delete','miniapp_id='.$val->miniapp_id)?>" class="btn btn-xs btn-warning"><i class="fa fa-trash bigger-120"></i>删除</a>
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
                </div><!--main-container-->
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
