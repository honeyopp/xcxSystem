<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:58:"D:\phpstudy_pro\WWW\cms/youge/manage\view\money\lists.html";i:1516027236;s:53:"D:\phpstudy_pro\WWW\cms/youge/manage\view\layout.html";i:1514694776;s:60:"D:\phpstudy_pro\WWW\cms/youge/manage\view\public\header.html";i:1514653468;}*/ ?>
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="zh-CN" xml:lang="zh-CN">
<link rel="stylesheet" href="/public/home/css/promo.css?v=201708230932"/>
<style>
    .promoImg{height:125px;}
</style>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-content">
                <div class="row">
                          <div class="ibox float-e-margins">
                  <div class="ibox-title">
                        <h5>订单列表</h5>
                    </div> 
                    <div class="ibox-content" style="margin-bottom:0px;padding-bottom:0px;">
                        <p>如有充值有遇到问题请及时联系客服</p>
                        <p>客服联系方式：</p>
                        <ol>
                            <li>客服QQ：67930603（唯一QQ客服）</li>
                            <li>客服微信：yanervip（唯一微信客服）</li>
                            <li>客服电话：15169991113</li>
                        </ol>
                    </div>

                <div class="ibox-content" style="margin-top:17px;" class="weixin-form">
                   <iframe src="/phpwxpay/list.php?memberid=<?=$member_id?>" frameborder="0" width="100%" height="350"></iframe>
                </div>
                </div>
				</div>
                    
				
            </div>
        </div>
    </div>
</div>

<script>
  $("#pay100").click(function(){
      $("#pirce").val("100");
  })
  $("#pay500").click(function(){
      $("#pirce").val("500");
  })
  $("#pay1000").click(function(){
      $("#pirce").val("1000");
  })

</script>
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
