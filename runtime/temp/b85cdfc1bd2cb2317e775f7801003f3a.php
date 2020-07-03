<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:67:"D:\phpstudy_pro\WWW\cms/youge/admin\view\miniapp\miniapp\photo.html";i:1506510112;s:52:"D:\phpstudy_pro\WWW\cms/youge/admin\view\layout.html";i:1513522666;s:59:"D:\phpstudy_pro\WWW\cms/youge/admin\view\public\header.html";i:1514657874;}*/ ?>
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
	<link rel="stylesheet" href="/public/common/zyupload/skins/zyupload-1.0.0.css " type="text/css">
<script type="text/javascript" src="/public/common/zyupload/zyupload.basic-1.0.0.js"></script>
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- PAGE CONTENT BEGINS -->
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox-content">
                <div id="zyupload" class="zyupload"></div>
                <script type="text/javascript">
                    $(function () {
                        // 初始化插件
                        $("#zyupload").zyUpload({
                            width: "650px", // 宽度
                            height: "auto", // 宽度
                            itemWidth: "140px", // 文件项的宽度
                            itemHeight: "115px", // 文件项的高度
                            url: "<?=url('miniapp.miniapp/photosave',['miniapp_id'=>$miniapp_id,'mdl'=>'miniapp'])?>", // 上传文件的路径
                            fileType: ["jpg", "png", 'gif', 'jpeg', 'bmp'], // 上传文件的类型
                            fileSize: 51200000, // 上传文件的大小
                            multiple: true, // 是否可以多个文件上传
                            dragDrop: false, // 是否可以拖动上传文件
                            tailor: false, // 是否可以裁剪图片
                            del: true, // 是否可以删除文件
                            finishDel: false, // 是否在上传文件完成后删除预览
                            /* 外部获得的回调接口 */
                            onSelect: function (selectFiles, allFiles) {    // 选择文件的回调方法  selectFile:当前选中的文件  allFiles:还没上传的全部文件
                                console.info("当前选择了以下文件：");
                                console.info(selectFiles);
                            },
                            onDelete: function (file, files) {              // 删除一个文件的回调方法 file:当前删除的文件  files:删除之后的文件
                                console.info("当前删除了此文件：");
                                console.info(file.name);
                            },
                            onSuccess: function (file, response) {          // 文件上传成功的回调方法
                                console.info("此文件上传成功：");
                                console.info(file.name);
                                console.info("此文件上传到服务器地址：");
                                console.info(response);
                                //$("#uploadInf").append("<p>上传成功，文件地址是：" + response + "</p>");
                            },
                            onFailure: function (file, response) {          // 文件上传失败的回调方法
                                console.info("此文件上传失败：");
                                console.info(file.name);
                            },
                            onComplete: function (response) {           	  // 上传完成的回调方法
                                location.href = "<?=url('miniapp.miniapp/photo',['miniapp_id'=>$miniapp_id])?>";
                            }
                        });

                    });
                </script>
                <div class="page-header">
                    <h1>
                        <small>
                            <i class="ace-icon fa fa-angle-double-right"></i>
                            <?=$detail['title']?>
                        </small>
                    </h1>
                </div><!-- /.page-header -->
                <div class="row">
                    <!-- PAGE CONTENT BEGINS -->
                    <table id="simple-table" class="table  table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>图片</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <form id="mini_list">
                            <tbody>
                            <?php foreach($photos as $val){?>
                            <tr>
                                <td><?=$val->photo_id?></td>

                                <td><img width="80" src="/attachs/uploads/<?=$val->photo?>"/></td>
                                <td>
                                    <input type="text" name="orderby[<?=$val->photo_id;?>]"
                                           id="orderby[<?=$val->photo_id;?>]" value="<?=$val->orderby;?>"/>
                                </td>

                                <td>
                                    <div class="hidden-sm hidden-xs btn-group">
                                        <a title="删除积分商品" mini="act"
                                           href="<?=url('miniapp.miniapp/photodelete','photo_id='.$val->photo_id)?>"
                                           class="btn btn-xs btn-warning"><i class="fa fa-trash bigger-120"></i>删除</a>
                                    </div>
                                </td>
                            </tr>
                            <?php }?>
                            </tbody>
                        </form>
                    </table>
                    <div class="tableTools-container">
                        <a mini="list" for="mini_list" title="更新排序" href="<?=url('miniapp.miniapp/photoupdate')?>"
                           class="btn btn-sm btn-danger">更新排序</a>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

        
        
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
