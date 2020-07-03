<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:71:"D:\phpstudy_pro\WWW\cms/youge/admin\view\miniapp\authorizer\create.html";i:1505615686;s:52:"D:\phpstudy_pro\WWW\cms/youge/admin\view\layout.html";i:1513522666;s:59:"D:\phpstudy_pro\WWW\cms/youge/admin\view\public\header.html";i:1514657874;}*/ ?>
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
                    <form action="<?=url('miniapp.authorizer/create')?>" id="form-create" method="post"
                          class="form-horizontal" role="form">

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>用户：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->member_id)?$detail->member_id:''?>"
                                       placeholder="" id="member_id" name="member_id" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>小程序：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->miniapp_id)?$detail->miniapp_id:''?>"
                                       placeholder="" id="miniapp_id" name="miniapp_id" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span class="c-red">*</span>授权用户APPID：</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       value="<?=isset($detail->authorizer_appid)?$detail->authorizer_appid:''?>"
                                       placeholder="" id="authorizer_appid" name="authorizer_appid"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span class="c-red">*</span>授权用户ACCESS_TOKEN：</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       value="<?=isset($detail->authorizer_access_token)?$detail->authorizer_access_token:''?>"
                                       placeholder="" id="authorizer_access_token" name="authorizer_access_token"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span class="c-red">*</span>授权用户REFRESH_TOKEN：</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       value="<?=isset($detail->authorizer_refresh_token)?$detail->authorizer_refresh_token:''?>"
                                       placeholder="" id="authorizer_refresh_token" name="authorizer_refresh_token"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><span
                                    class="c-red">*</span>授权用户REFRESH_TOKEN过期时间：</label>
                            <div class="col-sm-9">
                                <input value="<?=isset($detail->authorizer_refresh_token_expir_time)?date('Y-m-d H:i:s',$detail->authorizer_refresh_token_expir_time):''?>"
                                       name="authorizer_refresh_token_expir_time"
                                       id="authorizer_refresh_token_expir_time"
                                       class="laydate-icon form-control layer-date">

                            </div>
                        </div>
                        <script>
                            laydate({
                                elem: '#authorizer_refresh_token_expir_time',
                                event: 'focus',
                                format: 'YYYY-MM-DD hh:mm:ss',
                                istime: true
                            });
                        </script>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span class="c-red">*</span>授权用户REFRESH_TOKEN过期时间：</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       value="<?=isset($detail->authorizer_refresh_token_expir_time)?$detail->authorizer_refresh_token_expir_time:''?>"
                                       placeholder="" id="authorizer_refresh_token_expir_time"
                                       name="authorizer_refresh_token_expir_time" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>微信名称：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->nick_name)?$detail->nick_name:''?>"
                                       placeholder="" id="nick_name" name="nick_name" class="form-control"/>
                            </div>
                        </div>
                        <link rel="stylesheet" type="text/css" href="/public/common/webuploader-0.1.5/webuploader.css">
                        <script type="text/javascript" src="/public/common/webuploader-0.1.5/webuploader.js"></script>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>头像：</label>
                            <div class="col-sm-9 ">

                                <div id="uploader-demo" style="max-width: 300px">
                                    <!--用来存放item-->
                                    <div id="head_imgList" class="uploader-list">
                                        <?php if(isset($detail['head_img'])){?>
                                        <div class="file-item thumbnail"><img width="110"
                                                                              src="/attachs/uploads/<?=$detail['head_img']?>"><input
                                                type="hidden" value="<?=$detail['head_img']?>" name="head_img"
                                                id="head_img"></div>
                                        <?php }?>
                                    </div>
                                    <div id="head_imgPicker">选择图片</div>
                                </div>


                            </div>
                        </div>


                        <script>


                            var uploaderhead_img = WebUploader.create({
                                auto: true,
                                swf: '/public/admin/Widget/webuploader-0.1.5/Uploader.swf',
                                server: '<?=url("upload.upload/index",['mdl'=>"authorizer_head_img"])?>',
                                pick: '#head_imgPicker',
                                resize: false,
                                duplicate: true
                            });

                            $(document).on('click', '.file-item', function () {
                                $(this).remove();
                            });

                            // 当有文件添加进来的时候
                            uploaderhead_img.on('fileQueued', function (file) {
                                var $li = $(
                                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                                    '<img>' +
                                    '<input type="hidden" value="" name="head_img" id="head_img">' +
                                    '<div class="info">' + file.name + '</div>' +
                                    '</div>'
                                    ),
                                    $img = $li.find('img');


                                // $list为容器jQuery实例
                                $("#head_imgList").html($li);

                                // 创建缩略图
                                // 如果为非图片文件，可以不用调用此方法。
                                // thumbnailWidth x thumbnailHeight 为 100 x 100
                                uploaderhead_img.makeThumb(file, function (error, src) {
                                    if (error) {
                                        $img.replaceWith('<span>不能预览</span>');
                                        return;
                                    }

                                    $img.attr('src', src);
                                }, 100, 100);
                            });


                            // 文件上传过程中创建进度条实时显示。
                            uploaderhead_img.on('uploadProgress', function (file, percentage) {
                                var $li = $('#' + file.id),
                                    $percent = $li.find('.progress span');

                                // 避免重复创建
                                if (!$percent.length) {
                                    $percent = $('<p class="progress"><span></span></p>')
                                        .appendTo($li)
                                        .find('span');
                                }

                                $percent.css('width', percentage * 100 + '%');
                            });

                            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
                            uploaderhead_img.on('uploadSuccess', function (file, response) {
                                $('#' + file.id).addClass('upload-state-done');
                                $("#head_img").val(response._raw);
                            });

                            // 文件上传失败，显示上传出错。
                            uploaderhead_img.on('uploadError', function (file) {
                                var $li = $('#' + file.id),
                                    $error = $li.find('div.error');

                                // 避免重复创建
                                if (!$error.length) {
                                    $error = $('<div class="error"></div>').appendTo($li);
                                }

                                $error.text('上传失败');
                            });

                            // 完成上传完了，成功或者失败，先删除进度条。
                            uploaderhead_img.on('uploadComplete', function (file) {
                                $('#' + file.id).find('.progress').remove();
                            });

                        </script>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>小程序名：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->user_name)?$detail->user_name:''?>"
                                       placeholder="" id="user_name" name="user_name" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>二维码地址：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->qrcode_url)?$detail->qrcode_url:''?>"
                                       placeholder="" id="qrcode_url" name="qrcode_url" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>公司名称：</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       value="<?=isset($detail->principal_name)?$detail->principal_name:''?>"
                                       placeholder="" id="principal_name" name="principal_name" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>签名：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->signature)?$detail->signature:''?>"
                                       placeholder="" id="signature" name="signature" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><span class="c-red">*</span>过期时间：</label>
                            <div class="col-sm-9">
                                <input value="<?=isset($detail->expir_time)?date('Y-m-d H:i:s',$detail->expir_time):''?>"
                                       name="expir_time" id="expir_time" class="laydate-icon form-control layer-date">

                            </div>
                        </div>
                        <script>
                            laydate({
                                elem: '#expir_time',
                                event: 'focus',
                                format: 'YYYY-MM-DD hh:mm:ss',
                                istime: true
                            });
                        </script>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>过期时间：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->expir_time)?$detail->expir_time:''?>"
                                       placeholder="" id="expir_time" name="expir_time" class="form-control"/>
                            </div>
                        </div>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button mini="submit" for="form-create" class="btn btn-info" type="button">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    保存
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
