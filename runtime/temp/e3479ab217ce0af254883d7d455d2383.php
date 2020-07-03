<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:64:"D:\phpstudy_pro\WWW\cms/youge/manage\view\miniappshop\index.html";i:1593765777;s:53:"D:\phpstudy_pro\WWW\cms/youge/manage\view\layout.html";i:1514694776;s:60:"D:\phpstudy_pro\WWW\cms/youge/manage\view\public\header.html";i:1514653468;}*/ ?>
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
<div class="wrapper wrapper-content animated fadeInRight">    <div class="row">        <?php foreach($list as $val) { ?>            <div class="col-sm-6 col-md-6 col-lg-6">                <a  href="<?=url('miniappshop/miappdetail',['miniapp_id'=>$val->miniapp_id,'member_miniapp_id'=>$member_miniapp_id])?>" >                <div class="widget style1 mininapp"    style="background-color: #ffffff">                    <div class="row">                        <div class="col-sm-4 col-lg-4">                            <img  alt="image" class="img-responsive" src="/attachs/uploads/<?=$val->photo?>">                        </div>                        <div class="col-sm-8 col-lg-8 text-left" style="">                            <h2 style=" margin-bottom: 1%;" class="font-bold"><?=$val->title?></h2>                            <p>服务周期 <b>:</b><a class="btn btn-white btn-bitbucket" style="margin-left: 2%; border-color: #00a0e9"><?=config('setting.service_day')?>天</a></p>                            <p>模板价格 <b>:</b><span style="color: red;margin-left: 2%"> 活动价：￥<?= sprintf("%.2f",$val->price/100)?></span><span style="margin-left: 10px; color: #c0c0c0;text-decoration:line-through">（原价：￥<?= sprintf("%.2f",$val->activity_price/100)?>元）</span></p>                            <?php if(!empty($agent[$member->type])) {?>                                   <p><?=$agent[$member->type]['name']?><b>:</b><span style="color: red;margin-left: 2%"> ：￥<?= sprintf("%.2f",$val->price * ($agent[$member->type]['discount']/100) /100)?></span></p>                            <?php } if(isset($buys[$val->miniapp_id])){if($buys[$val->miniapp_id]['expir_time']<$nowtime){?>                             <p>模板状态 <b>:</b><span class="badge-warning">已过期</span></p>                            <?php }elseif($buys[$val->miniapp_id]['expir_time']<$tixing){?>                            <p>模板状态 <b>:</b><span class="badge-warning">即将到期</span></p>                            <?php }else{?>                            <p>模板状态 <b>:</b><span class="badge-success">正常使用</span></p>                            <?php }}else{?>                            <p>模板状态   <b>:</b><span class="badge-white">未获得</span></p>                            <?php }?>                        </div>                    </div>                </div>                  </a>            </div>        <?php } ?>    </div></div>
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
