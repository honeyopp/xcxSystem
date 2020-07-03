<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:58:"D:\phpstudy_pro\WWW\cms/youge/manage\view\index\index.html";i:1516026234;s:60:"D:\phpstudy_pro\WWW\cms/youge/manage\view\public\header.html";i:1514653468;s:58:"D:\phpstudy_pro\WWW\cms/youge/manage\view\public\left.html";i:1516024608;}*/ ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<title><?=$member->nick_dltitle?>后台管理中心</title>
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
<body class="fixed-sidebar full-height-layout gray-bg">
<!--头部 begin-->
<?php
    $SettingModel = new \app\common\model\setting\SettingModel();
     $setting = $SettingModel->fetchAll(true);
     $agent = $setting['agent'];
?>
<div class="head">
    <nav class="navbar navbar-default">
        <div>

            <div class="navbar-header">
                <a style="height:auto;overflow:hidden;display:block" href="<?=url('index/index')?>">
			<?php if(empty($agent[$member->type])) {?>
				<img src="/public/admin/img/logo.png" style="height:45px;  margin-right:10px;margin-top:10px;">
			<?php }else{ ?>
                  <img src="/attachs/uploads/<?=$member->nick_dllogo?>" style="height:60px;  margin-right:10px;">
			<?php } ?>
                </a>
            </div>
           
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><!--<i class="wi wi-user color-gray"></i>-->
                            
							
							<?php if(empty($agent[$member->type])) {?>
				<img src="/public/admin/img/logo.png" style="height:35px;  margin-right:10px;">
			<?php }else{ ?>
                  <img src="/attachs/uploads/<?=$member->nick_dllogo?>" style="height:35px; margin-right:10px;">
			<?php } ?>
                            <?=$member->nick_name?>(<?=empty($agent[$member->type]) ? '普通商户' : $agent[$member->type]['name'] ?>)<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu color-gray" role="menu">
                            <li>
                                <a href="<?=url('Passport/logout');?>" target="_top"><i class="wi wi-user color-gray"></i> 安全退出</a>
                            </li>
                            <li>
                                <a href="<?=url('Member/edit');?>" target="_blank"><i class="wi wi-account color-gray"></i> 修改密码</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="inviteL">
                   <!--  <a href="/home/invite/index.html?id=282" target="_blank" >
                        <img src="/public/home/images/invite/la.png"> 
                        <span class="inviteName">邀请朋友得￥56消费红包！</span><span class="inviteDot"></span>
                    </a>-->
                </div>
            </div>
        </div>
    </nav>
</div>
<!--头部 end-->
<div class="main">
    <div id="wrapper">
    <!--左侧导航开始-->
 <!--左侧导航开始-->
<?php
    $SettingModel = new \app\common\model\setting\SettingModel();
     $setting = $SettingModel->fetchAll(true);
     $agent = $setting['agent'];
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse left-menu">
      <div class="basemenu">
        <div class="left-scrool-panel">
          <div class="leftMenu-box">
            <div class="leftMenu active">
              <div class="leftMenu-header">
                <i class="fa fa-home"></i>
                <span>管理中心</span>
                <i class="indexImg firstMenuDown"></i>
                <i class="indexImg firstMenuUp"></i>
              </div>
              <ul class="secondMenu">
                <li class="">
                  <a class="J_menuItem" href="<?=url('index/main')?>">
                    <i class="secondMenuIcon"></i>
                    <span>应用大厅</span>
                  </a>
                </li>
              </ul>
            </div>
            <!--菜单循环 begin-->
            <?php foreach($leftMenus as $k=>$val){?>
            <div class="leftMenu active">
              <div class="leftMenu-header">
                <i class="fa <?=$val['icon']?>"></i>
                <span><?=$val['name']?></span>
                <i class="indexImg firstMenuDown"></i>
                <i class="indexImg firstMenuUp"></i>
              </div>
              <ul class="secondMenu">
                 <?php foreach($val['menu'] as $val1){ if($val1['is_show']==1){?>
                <li class="">
                  <a <?php if(isset($val1['blank'])){ echo 'id="_blank"'; } ?> class="J_menuItem" href="<?=$val1['is_sub'] == 1 ? '' : url('admin/'.$val1['link']);if(isset($val1['blank'])){ echo '?id='.$member->member_id; } ?>">
                    <i class="secondMenuIcon"></i>
                    <span><?=$val1['name']?></span>
                  </a>
                </li>
                <?php }} ?>
              </ul>
            </div>
            <?php } if(empty($agent[$member->type])) {}else{ ?> 
		<div class="leftMenu active">
              <div class="leftMenu-header">
                <i class="fa fa-viacoin"></i>
                <span>代理商资料</span>
                <i class="indexImg firstMenuDown"></i>
                <i class="indexImg firstMenuUp"></i>
              </div>
              <ul class="secondMenu">
                <li class="">
                  <a  class="J_menuItem" href="/manage/member/usertext">
                    <i class="secondMenuIcon"></i>
                    <span>代理商资料</span>
                  </a>
                </li>
            </ul>
        </div> <?php } ?>

            <!--菜单循环 end-->
          </div>
        </div>
      </div>
        
    </div>
</nav>
<script type="text/javascript">
  $(function(){
  /*左边菜单点击伸缩效果*/
  $(".leftMenu-header").click(function(){
    $(this).parent(".leftMenu").toggleClass("active")
  });

  var url = $('#_blank').attr('href');
  $('#_blank').attr('href','javascript:;');

  $('#_blank').click(function(){
      window.open(url);
  })
})
</script>
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row J_mainContent" style="width: 100%; height: 95%; margin:  0 auto; "   id="content-main">
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="/manage/miniapp/index" frameborder="0"  seamless></iframe>
            </div>
        </div>
        <!--右侧部分结束-->
    </div>
</div>
<script src="/public/admin/js/hplus.min.js?v=4.1.0"></script>
</body>
</html>

<style>
.inviteL{display: inline-block;float: right;padding-top: 26px;}
.inviteL img{padding-top: 37px;}
.inviteL a{color:#FFFFFF;}
</style>