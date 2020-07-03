<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:69:"/www/wwwroot/xcx.cpasem.com/youge/admin/view/member/member/index.html";i:1508554658;s:56:"/www/wwwroot/xcx.cpasem.com/youge/admin/view/layout.html";i:1513522666;s:63:"/www/wwwroot/xcx.cpasem.com/youge/admin/view/public/header.html";i:1514657874;}*/ ?>
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
                            <form class="form-search" method="get" action="<?=url('member.member/index')?>" role="form">
                                <table>
                                    <tr>
                                        <td>手机号:<input class="form-control" name="mobile" id="mobile"
                                                       value="<?=$search['mobile']?>" type="text" placeholder="请输入手机号"
                                                       style=" width:200px"/></td>
                                        <td>押金:
                                              <select name="is_deposit" class="form-control">
                                                  <option  <?=$search['is_deposit'] == 0 ? 'selected' : ''?> class="form-control" value="0">请选择</option>
                                                  <option <?=$search['is_deposit'] == 1 ? 'selected' : ''?>   class="form-control" value="1">已交押金</option>
                                                  <option <?=$search['is_deposit'] == 2 ? 'selected' : ''?>  class="form-control" value="2">未交押金</option>
                                              </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <button type="submit" style="margin-top: 42%" class="btn form-control btn-sm btn-primary">
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
                        <a title="添加加盟商" href="<?=url('member.member/create')?>" class="btn btn-sm btn-success"><i
                                class=" fa fa-plus"></i>添加加盟商</a>
                        <a mini="list" for="mini_list" title="批量删除用户管理" href="<?=url('member.member/delete')?>"
                           class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>批量删除</a>
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
                                <th>手机号</th>
                                <th>昵称</th>
                                <th>代理等级</th>
                                <th>真实姓名</th>
                                <th>是否锁定</th>
                                <th>邮箱</th>
                                <th>QQ</th>
                                <th>微信</th>
                                <th>余额</th>
                                <th>短信剩余</th>
                                <th>最后登录时间</th>
                                <th>最后登录IP</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <form id="mini_list">
                                <tbody>
                                <?php foreach($list as $val){ ?>
                                <tr>
                                    <td class="center">
                                        <label class="pos-rel">
                                            <input id="member_id_<?=$val->member_id;?>" name="member_id[]"
                                                   value="<?=$val->member_id;?>" type="checkbox" class="ace"/>
                                            <span class="lbl"></span>
                                        </label>
                                    </td>
                                    <td><?= $val->member_id ?></td>
                                    <td><?=$val->mobile?></td>
                                    <td><?=$val->nick_name?></td>
                                    <td><?=empty($agent[$val->type]) ? '普通商户' :$agent[$val->type]['name'] ?></td>
                                    <td><?=empty($val->real_name) ? '没有填写' : $val->real_name?></td>
                                    <td><?=$val->is_lock == 1 ? '<span class="label  label-danger">锁定</span>' : '<span class="label label-primary">正常</span>'?></td>
                                    <td><?=empty($val->email) ? '没有填写' : $val->email?></td>
                                    <td><?=empty($val->qq) ?  '没有填写' : $val->qq ?></td>
                                    <td><?=empty($val->weixin) ? '没有填写' : $val->weixin?></td>
                                    <td><?=sprintf("%.2f",$val->money/100)?>元</td>
                                    <td><?=$val->sms_num?>条</td>
                                    <td><?=empty($val->last_time) ? '没有登录' : date("Y-m-d H:i:s",$val->last_time)?></td>
                                    <td><?=empty($val->last_ip) ? '没有登录' : $val->last_ip?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs btn-group">
                                            <a title="编辑用户管理" href="<?=url('member.member/edit','member_id='.$val->member_id)?>" class="btn btn-xs btn-info"><i class=" fa fa-edit bigger-120"></i>编辑</a>
                                            <a title="设置用户等级" mini="load" w="50%" h="50%" href="<?=url('member.member/settype','member_id='.$val->member_id)?>" class="btn btn-xs btn-info"><i class=" fa fa-edit bigger-120"></i>设置用户等级</a>
                                            <a title="进入用户中心" target="_blank" href="<?=url('member.member/member',['member_id'=>$val->member_id])?>" class="btn btn-xs btn-danger"><i class=" fa fa-user bigger-120"></i>进入加盟商中心</a>
                                            <a title="删除用户管理" mini="act" href="<?=url('member.member/delete','member_id='.$val->member_id)?>" class="btn btn-xs btn-warning"><i class="fa fa-trash bigger-120"></i>删除</a>
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
                </div><!-- /.main-container -->
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
