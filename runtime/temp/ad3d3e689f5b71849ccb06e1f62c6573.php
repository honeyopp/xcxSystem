<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:46:"D:\WWW\demo2/youge/admin\view\index\index.html";i:1514656951;s:48:"D:\WWW\demo2/youge/admin\view\public\header.html";i:1514657875;s:46:"D:\WWW\demo2/youge/admin\view\public\left.html";i:1514656958;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<title>微点应用小程序神器系统后台管理中心</title>
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
<body class="fixed-sidebar full-height-layout gray-bg">
<div id="wrapper">
    <!--左侧导航开始-->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span><img alt="image" class="img-circle" src="/public/admin/img/profile_small.jpg" /></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                <span class="block m-t-xs"><strong class="font-bold"><?=$adminInfo['real_name'];?></strong></span>
                                <span class="text-muted text-xs block"><?=$adminRole['role_name'];?><b class="caret"></b></span>
                                </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                    
                        <li><a href="<?=url('login/logout');?>">安全退出</a>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">@
                </div>
            </li>
            <?php foreach($leftMenus as $k=>$val){?>
            <li>
                <a href="#"><i class="fa <?=$val['icon']?>"></i> <span class="nav-label"><?=$val['name']?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <?php foreach($val['menu'] as $val1){ if($val1['is_show']==1){?>
                         <li><a class="J_menuItem" href="<?=$val1['is_sub'] == 1 ? '' : url('admin/'.$val1['link']);?>"><?=$val1['name']?> <?=$val1['is_sub'] == 1 ? '<span class="fa arrow">' : ''?></a>
                             <?php if($val1['is_sub'] == 1) { ?>
                             <ul class="nav nav-third-level">
                                 <?php foreach($val1['sub'] as $val2){ if($val2['is_show']==1){?>
                                 <li><a class="J_menuItem" href="<?=url('admin/'.$val2['link']);?>"><?=$val2['name']?></a>
                                 </li>
                                <?php }} ?>
                             </ul>
                             <?php } ?>
                    </li>
                    <?php } } ?>
                </ul>
            </li>
          <?php } ?>
        </ul>
    </div>
</nav>
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row content-tabs">
                <button class="roll-nav roll-left J_tabLeft navbar-minimalize"><i class="fa fa-backward"></i>
                </button>
                <nav class="page-tabs  J_menuTabs">
                    <div class="page-tabs-content">
                        <a href="javascript:;" class="active J_menuTab" data-id="<?=url('index/main');?>">首页</a>
                    </div>
                </nav>
                <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
                </button>
                <div class="btn-group roll-nav roll-right">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li class="J_tabShowActive"><a>定位当前选项卡</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                        </li>
                        <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                        </li>
                    </ul>
                </div>
                <a href="<?=url('login/logout');?>" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="row J_mainContent" style="width: 100%; height: 95%; margin:  0 auto; "   id="content-main">
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="/admin/index/main" frameborder="0"  seamless></iframe>
            </div>
            <div class="footer">
                <div class="pull-right"> xcx.mestudio.cn (c) 2017<a href="https://xcx.mestudio.cn/" target="_blank">微点应用</a>
                </div>
            </div>
        </div>
        <!--右侧部分结束-->
    </div>
<script src="/public/admin/js/hplus.min.js?v=4.1.0"></script>
</body>
</html>