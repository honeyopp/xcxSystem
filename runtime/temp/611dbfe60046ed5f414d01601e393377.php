<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:70:"D:\phpstudy_pro\WWW\cms/youge/manage\view\miniappshop\miappdetail.html";i:1508556256;s:53:"D:\phpstudy_pro\WWW\cms/youge/manage\view\layout.html";i:1514694776;s:60:"D:\phpstudy_pro\WWW\cms/youge/manage\view\public\header.html";i:1514653468;}*/ ?>
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
<link href="/public/admin/css/mest.css" rel="stylesheet">
<script src="/public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/public/admin/js/admin.js"></script>
<script src="/public/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/public/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script type="text/javascript" src="/public/admin/js/contabs.min.js"></script>
<script src="/public/admin/js/plugins/pace/pace.min.js"></script>

</head>
<body class="gray-bg">
<link href="/public/admin/css/plugins/blueimp/css/blueimp-gallery.min.css" rel="stylesheet">
<style>.lightBoxGallery img {
    margin: 1rem;
    width: 14%;
}

.text-p::first-letter {
    font-size: 3rem;
}
</style>
<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row" style="background-color: #ffffff">
        <div class="ibox float-e-margins" style="background-color: #ffffff">
            <div class="col-sm-2" style="background-color: #ffffff;height: auto;">
                <img width="400" href="400" alt="image" style="margin-top:30px;" class="img-responsive"
                     src="/attachs/uploads/<?=getImg($detail->photo)?>">
            </div>
            <div class="col-sm-10 text-left" style=" line-height: 4rem;background-color: #ffffff">
                <h2 style=" margin-bottom: 1%;" class="font-bold"><?=$detail->title?></h2>
                <p>模板价格 <b>:</b>

                    <?php if(!empty($agent[$member->type])) {?>
                    <span style="color: red;margin-left: 2%"> <?=$agent[$member->type]['name']?>：￥<?= sprintf("%.2f",$detail->price * ($agent[$member->type]['discount']/100) /100)?></span><span
                    style="margin-left: 10px; color: #c0c0c0;text-decoration:line-through"></span>
                     <?php } ?>
                    <span style="color: red;margin-left: 2%"> 活动价：￥<?= sprintf("%.2f",$detail->price/100)?></span><span
                            style="margin-left: 10px; color: #c0c0c0;text-decoration:line-through">（原价：￥<?= sprintf("%.2f",$detail->
                        activity_price/100)?>元）</span></p>
                <p>服务周期 <b>:</b><a class="btn btn-white btn-bitbucket"
                                   style="margin-left: 2%; border-color: #00a0e9"><?=config('setting.service_day')?>
                    天</a></p>

                <?php if(!empty($buys)){if($buys['expir_time']<$nowtime){?>
                <p>应用状态 <b>:</b><span style="margin-left: 2%;" class=" btn label-warning">已过期</span></p>
                <?php }elseif($buys['expir_time']<$tixing){?>
                <p>应用状态 <b>:</b><span style="margin-left: 2%;" class="btn label-danger">即将到期</span></p>

                <?php }else{?>
                <p>应用状态 <b>:</b><span style="margin-left: 2%;" class="btn label-success">正常使用</span></p>
                <?php }}else{?>
                <p>应用状态 <b>:</b><span style="margin-left: 2%;" class="btn label-info">未购买应用</span></p>
                <?php }?>
                <p>
                    <?php if(empty($buys)){?>
                    <a href="<?=url('miniappshop/shiyong',['miniapp_id'=>$detail->miniapp_id,'member_miniapp_id'=>$member_miniapp_id]);?>"
                       style="margin-right: 2%;" class="btn btn-primary btn-outline">试用(<?=$detail->expire_day?>)天
                    </a>

                    <a href="<?=url('miniappshop/buy',['miniapp_id'=>$detail->miniapp_id,'type'=>1,'member_miniapp_id'=>$member_miniapp_id]);?>"
                       style="margin-right: 2%;" class="btn btn-primary btn-outline">
                        立即购买
                    </a>
                    <?php }else{?>
                    <a href="<?=url('miniappshop/buy',['miniapp_id'=>$detail->miniapp_id,'type'=>2,'member_miniapp_id'=>$member_miniapp_id]);?>"
                       style="margin-right: 2%;" class="btn btn-primary btn-outline">
                        续费使用
                    </a>

                    <a mini="act" title="立刻使用该模版" href="<?=url('miniappshop/used',['order_id'=>$buys['order_id']]);?>"
                       style="margin-right: 2%;" class="btn btn-primary btn-outline">
                        立刻使用
                    </a>
                    <?php }?>

                </p>
            </div>
        </div>
    </div>
</div>
<!--相册-->
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <h1>应用截图：</h1>
                <div class="lightBoxGallery" id="layer-photos-demo">
                    <?php foreach($photos as $val) {?>
                    <img src="/attachs/uploads/<?=getImg($val->photo)?>">
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!--图文介绍-->
<div class="row" style="">
    <div class="col-lg-12 ">
        <div class="ibox" style="padding: 0 14px;">
            <div class="ibox-content">
                <div class="pull-right">
                    <button class="btn btn-white btn-xs" type="button"><?=$detail->title?></button>
                    <button class="btn btn-white btn-xs" type="button">价格：<?=sprintf("%.2f",$detail->price/100)?>元
                    </button>
                    <button class="btn btn-white btn-xs" type="button">试用<?=$detail->expire_day?>天</button>
                </div>
                <div class="text-center article-title">
                    <h1>
                        <span style="font-size: 4rem">应用介绍</span>（<?=$detail->title?>）
                    </h1>
                </div>
                <?php foreach($describes as $val) { ?>
                <p class="text-p" style="text-indent:2em;font-size: 2rem;">
                    <?=str_replace("\r\n", "<br>",$val->describe); if(!empty($val->photo)) { ?>
                    <img style="width: 100%;height: 50%; max-width: 100%" alt="image" class="img-responsive"
                         src="/attachs/uploads/<?=getImg($val->photo)?>">
                    <?php } ?>
                </p>
                <?php } ?>
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
    .c-red{color: red;}
</style>
</body>
</html>
