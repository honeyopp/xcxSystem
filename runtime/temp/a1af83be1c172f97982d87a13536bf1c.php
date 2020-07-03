<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:64:"D:\phpstudy_pro\WWW\cms/youge/admin\view\member\member\edit.html";i:1513311248;s:52:"D:\phpstudy_pro\WWW\cms/youge/admin\view\layout.html";i:1513522666;s:59:"D:\phpstudy_pro\WWW\cms/youge/admin\view\public\header.html";i:1514657874;}*/ ?>
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
                    <form action="<?=url('member.member/edit',['member_id'=>$detail->member_id])?>" id="form-create"
                          method="post" class="form-horizontal" role="form">

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>手机号：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->mobile)?$detail->mobile:''?>" placeholder=""
                                       id="mobile" name="mobile" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red">*</span>密码(留空则不修改密码)：</label>
                            <div class="col-sm-9">
                                <input type="text" value=""
                                       placeholder="" id="password" name="password" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">昵称：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->nick_name)?$detail->nick_name:''?>"
                                       placeholder="" id="nick_name" name="nick_name" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">真实姓名：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->real_name)?$detail->real_name:''?>"
                                       placeholder="" id="real_name" name="real_name" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">邮箱：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->email)?$detail->email:''?>" placeholder=""
                                       id="email" name="email" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">QQ：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->qq)?$detail->qq:''?>" placeholder="" id="qq"
                                       name="qq" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">微信：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->weixin)?$detail->weixin:''?>" placeholder=""
                                       id="weixin" name="weixin" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">余额：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->money)?round($detail->money/100,2):''?>" placeholder=""
                                       id="money" name="money" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">短信剩余：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->sms_num)?$detail->sms_num:''?>"
                                       placeholder="" id="sms_num" name="sms_num" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">押金：</label>
                            <div class="col-sm-9">
                                <div class="radio i-checks">
                                    <label>
                                        <input name="is_deposit" id="is_deposit" value="1"
                                               type="radio" <?php if(isset($detail->is_deposit)&&
                                        $detail->is_deposit==1){?>checked="checked"<?php }?> > <i></i> 是</label>
                                    <label>
                                        <input name="is_deposit" id="is_deposit" value="0"
                                               type="radio" <?php if(isset($detail->is_deposit)&&
                                        $detail->is_deposit==0){?>checked="checked"<?php }?> > <i></i> 否</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">锁定：</label>
                            <div class="col-sm-9">
                                <div class="radio i-checks">
                                    <label>
                                        <input name="is_lock"  value="1" type="radio" <?php if(isset($detail->is_lock)&&$detail->is_lock==1){?>checked="checked"<?php }?> > <i></i> 锁定</label>
                                    <label>
                                        <input name="is_lock"  value="0" type="radio" <?php if(isset($detail->is_lock)&&$detail->is_lock==0){?>checked="checked"<?php }?> > <i></i> 正常</label>
                                </div>
                            </div>
                        </div>

				
						 <link rel="stylesheet" type="text/css" href="/public/common/webuploader-0.1.5/webuploader.css">
                        <script type="text/javascript" src="/public/common/webuploader-0.1.5/webuploader.js"></script>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"><span
                                    class="c-red"></span>代理商LOGO：<br><br>LOGO尺寸 110*65</label>
                            <div class="col-sm-9 ">

                                <div id="uploader-demo" style="max-width: 300px">
                                    <!--用来存放item-->
                                    <div id="head_imgList" class="uploader-list">
                                        <?php if(isset($detail['nick_dllogo'])){?>
                                        <div class="file-item thumbnail"><img width="110"
                                                                              src="/attachs/uploads/<?=$detail['nick_dllogo']?>"><input
                                                type="hidden" value="<?=$detail['nick_dllogo']?>" name="nick_dllogo"
                                                id="nick_dllogo"></div>
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
                                    '<input type="hidden" value="" name="nick_dllogo" id="nick_dllogo">' +
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
                                $("#nick_dllogo").val(response._raw);
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
                            <label class="col-sm-3 control-label no-padding-right">代理商名称：</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?=isset($detail->nick_dltitle)?$detail->nick_dltitle:''?>"
                                       placeholder="" id="nick_dltitle" name="nick_dltitle" class="form-control"/>
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
