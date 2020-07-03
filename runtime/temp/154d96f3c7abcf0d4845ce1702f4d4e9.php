<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:60:"D:\phpstudy_pro\WWW\cms/youge/manage\view\miniapp\index.html";i:1504171804;s:53:"D:\phpstudy_pro\WWW\cms/youge/manage\view\layout.html";i:1514694776;s:60:"D:\phpstudy_pro\WWW\cms/youge/manage\view\public\header.html";i:1514653468;}*/ ?>
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
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- PAGE CONTENT BEGINS -->
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox-content">
                <div class="row">
                    <div class="tableTools-container">
                        <a title="添加小程序" href="<?=url('miniapp/create')?>" class="btn btn-sm btn-success"><i class=" fa fa-plus"></i>添加小程序</a>
                    </div>
                    <div class="table-responsive">
                        <table id="simple-table" class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                 <th>小程序名</th>
                                 <th>头像</th>
                                 <th>公司</th>
                                <th>短信剩余</th>
                                <th>模板</th>
                                <th>过期时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <form id="mini_list">
                                <tbody>
                                <?php foreach($list as $val){ ?>
                                     <tr>
                                            <td><?=$val->member_miniapp_id?></td>
                                           
                                            <td><?=$val->nick_name?></td>
                                             <td><img src="<?=$val->head_img?>" width="80" /></td>    
                                            <td><?=$val->principal_name?></td> 
                                   
                                     
                                         <td><?=$val->sms_num?>条</td>
                                         <td>
                                             <?php if(empty($val->miniapp_id)) { ?> 没有绑定模板  <?php }else{ ?>
                                              <?=empty($miniapp[$val->miniapp_id]) ? '模板已删除' : $miniapp[$val->miniapp_id]->title;} ?>
                                         </td>
                                         <td><?=empty($val->expir_time) ? '没有绑定模板' : date("Y-m-d H:i:s",$val->expir_time)?></td>
                                         <td>
                                             <?php if(empty($val->miniapp_id)) { ?>
                                                <a title="购买应用"  href="<?=url('miniappshop/index','member_miniapp_id='.$val->member_miniapp_id)?>" class="btn btn-xs btn-success "><i class=" fa fa-buysellads bigger-120"></i>购买应用</a>

                                             <?php }else{ ?>
                                                 <a title="进入小程序后台"
                                                target="_blank" href="<?=url('miniapp/backstage',['member_miniapp_id'=>$val->member_miniapp_id])?>"
                                                class="btn btn-xs btn-info"><i class=" fa  fa-eye bigger-120"></i>管理小程序</a>
                                                <a title="应用管理"  href="<?=url('miniappshop/index','member_miniapp_id='.$val->member_miniapp_id)?>" class="btn btn-xs btn-success "><i class=" fa fa-edit bigger-120"></i>应用管理</a>
                                                 <a title="分配短信条数"  onclick="pay(<?=$val->member_miniapp_id?>,<?=$val->sms_num?>)" href="javascript:void (0)" class="btn btn-xs btn-warning"><i class=" fa fa-edit bigger-120"></i>短信设置</a>
                                                <?php } ?>
                                             <a title="授权管理员" mini="load" w="90%" h="90%"  href="<?=url('manage/index','miniapp_id='.$val->member_miniapp_id)?>" class="btn btn-xs btn-primary "><i class=" fa fa-user bigger-120"></i>授权管理员</a>
                                             <a title="重新授权"  href="<?=url('miniapp/refresh','miniapp_id='.$val->member_miniapp_id)?>" class="btn btn-xs btn-danger "><i class=" fa fa-refresh bigger-120"></i>重新授权</a>

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
<script>
    function pay(member_miniapp_id,num) {
        //示范一个公告层
        layer.open({
            type: 1
            ,
            title: false //不显示标题栏
            ,
            closeBtn: false
            ,
            area: '300px;'
            ,
            shade: 0.8
            ,
            id: 'LAY_layuipro' //设定一个id，防止重复弹出
            ,
            btn: ['立即分配', '我在看看']
            ,
            moveType: 1 //拖拽模式，0或者1
            ,
            content: ' <div style=" line-height: 22px; padding: 6%; font-weight: 300;"> <h3 style="float: left;margin-right: 1rem">短信条数:</h3><a style="float: left;margin-right: 1%" on="0"  onclick="price($(this),' + num + ')" id="add" class="btn btn-white btn-price btn-bitbucket">-100</a><input id="num" class="form-control"   value=" '+ num +'" type="text" placeholder="" style=" width: 5rem;padding:0;text-align: center;background-color: #ffffff;float: left"/><a style="float: left;margin-left: 1%" on="1" onclick="price($(this),'+ num +')" class="btn btn-price btn-white btn-bitbucket">+100</a> </div>'
            ,
            success: function (layero) {
                var btn = layero.find('.layui-layer-btn');
                btn.css('text-align', 'center');
                btn.find('.layui-layer-btn0').attr({
                    onclick: 'show('+member_miniapp_id + ',' + num +')'
                    , target: '_blank'
                });
            }
        });
    }
</script>
<script>
    function price(_this,_num) {
        var on = _this.attr('on');
        var num = parseFloat( $("#num").val());
        if (on == 0) {
            if (num <= _num ) {
                return false;
            } else {
                num-=100;
                $("#num").val(num);
            }
        } else if (on == 1) {
             num = num + 100;
            $("#num").val(num);
        }

    }

    function show (member_miniapp_id) {
        var load = layer.load(1);
        var num = $("#num").val();
        $.ajax({
            url: '/manage/miniapp/smsnum',// 跳转到 action
            data: {
                member_miniapp_id: member_miniapp_id,
                sms_num: num,
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            success: function (data) {
                layer.close(load);
                if (data.code == 0) {
                    layer.msg(data.msg);
                }
                if (data.code == 1) {
                    layer.msg(data.msg);
                    window.location.reload();
                }
            },
            error: function () {
                // view("异常！");
                alert("异常！");
            }
        })
    }
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
