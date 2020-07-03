<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:59:"D:\phpstudy_pro\WWW\cms/youge/manage\view\member\index.html";i:1508725452;s:53:"D:\phpstudy_pro\WWW\cms/youge/manage\view\layout.html";i:1514694776;s:60:"D:\phpstudy_pro\WWW\cms/youge/manage\view\public\header.html";i:1514653468;}*/ ?>
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
        <div class="col-sm-6">
        <div class="widget style1 lazur-bg">
            <div class="row vertical-align">
                <div class="col-xs-2">
                    <i class="fa fa-rmb fa-5x"></i>
                </div>
                <div class="col-xs-7 text-center">
                    <h1 class="font-bold">账户余额 <?=sprintf("%.2f",$member->money/100)?> 元</h1>
                </div>
                <div class="col-xs-3 text-right">
                    <a mini="load" w="800px" h="600px" href="<?=url('money/recharge');?>" style="margin-top: 5%" class="btn btn-warning dim"><i class="fa fa-warning"></i>
                        去充值
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="widget style1 lazur-bg">
            <div class="row vertical-align">
                <div class="col-xs-3">
                    <i class="fa fa-envelope-o fa-5x"></i>
                </div>
                <div class="col-xs-6 text-center">
                    <h1 class="font-bold">短信总剩余 <?=$member->sms_num?> 条</h1>
                </div>
                <div class="col-xs-3 text-right">
                    <a href="javascript:void(0);" style="margin-top: 5%" onclick="pay()" class="btn btn-warning dim" ><i class="fa fa-warning"></i>
                        去充值
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 2%">
         <div class="col-sm-6" style="padding: 0 1%">
            <div class="ibox-content">
                <div class="row">
                    <div class="table-responsive">
                        <table  class="table table-striped">
                            <thead>
                            <tr>
                                <th>日志ID</th>
                                <th>使用途径</th>
                                <th>余额数</th>
                                <th>当前结余</th>
                                <th>操作日期</th>
                            </tr>
                            </thead>
                                <tbody>
                                 <?php foreach($money as $val) { ?>
                                    <tr>
                                        <td><?=$val->log_id?></td>
                                        <td><?=empty(config('dataattr.moneylognames')[$val->way]) ? '' : config('dataattr.moneylognames')[$val->way]?></td>
                                        <td><?=$val->is_consume == 1 ? '<i style="color: red" class="fa fa-minus fa-1x"></i> ' : '<i style="color: green" class="fa fa-plus fa-1x"></i>'?><?=sprintf("%.2f",$val->money/100)?></td>
                                        <td><?=sprintf("%.2f",$val->this_money/100)?></td>
                                        <td><?=date('Y-m-d H:i:s',$val->add_time)?></td>
                                    </tr>
                                 <?php }?>
                                </tbody>
                        </table>
                        <div>
                            <!--page-->
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.main-container -->
            </div>
        </div>
        <div class="col-sm-6" style="padding: 0 1%">
            <div class="ibox-content">
                <div class="row">
                    <div class="table-responsive">
                        <table id="simple-table" class="table table-striped">
                            <thead>
                            <tr>
                                <th>日志ID</th>
                                <th>使用途径</th>
                                <th>短信条数</th>
                                <th>当前剩余条数</th>
                                <!--<th>分配小程序</th>-->
                                <th>操作日期</th>
                            </tr>
                            </thead>
                                <tbody>
                                <?php foreach($sms as $val) { ?>
                                <tr>
                                    <td><?=$val->pay_id?></td>
                                    <td><?=empty(config('dataattr.smspaywaynames')[$val->way]) ? '' : config('dataattr.smspaywaynames')[$val->way]?></td>
                                    <td><?=$val->is_consume == 1 ? '<i style="color: red" class="fa fa-minus fa-1x"></i> ' : '<i style="color: green" class="fa fa-plus fa-1x"></i>'?> <?=$val->sms_num?></td>
                                    <td><?=$val->this_sms_num?></td>
                                    <!--<td>微小宝云</td>-->
                                    <td><?=date('Y-m-d H:i:s',$val->add_time)?></td>
                                </tr>
                                <?php }?>
                                </tbody>
                        </table>
                        <div>
                            <!--page-->
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.main-container -->
            </div>
        </div>
       
    </div>
</div>
<script>
    function pay() {
        //示范一个公告层
        var num = '<?=config("setting.min_sms_num")?>';
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
                    onclick: 'show()'
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
            url: '/manage/member/smspay',// 跳转到 action
            data: {
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
