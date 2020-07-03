<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:46:"D:\WWW\demo2/youge/admin\view\login\index.html";i:1513558054;s:41:"D:\WWW\demo2/youge/admin\view\layout.html";i:1513522666;s:48:"D:\WWW\demo2/youge/admin\view\public\header.html";i:1514657875;}*/ ?>
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
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"

        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta name="renderer" content="webkit">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>找回密码</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="/public/home/plugs/swiper/dist/css/swiper.css">

    <link rel="stylesheet" href="/public/home/css/comment.css"/>

    <link rel="stylesheet" href="/public/home/css/register.css"/>

    <script src="/public/admin/js/jquery.min.js"></script>

    <script src="/public/common/layer/layer.js" type="text/javascript"></script>

    <script src="/public/admin/js/admin.js"></script>
<script src="/public/admin/js/particles.js"></script>
<style type="text/css">

        html,
        body {
            height: 100%;
        }

        html {
            display: table;
            margin: auto;
        }

        body {
            display: table-cell;
            vertical-align: middle;background-color: #2196F3 !important;
        }
.blue {
  background-color: #2196F3 !important;
}

        .margin {
            margin: 0 !important;
        }
        #particles {
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1;   //这个z-index 要是不设置 会对登录表单的点击产生干扰，会去抢风头，不好好做一个安静的背景。
        background-color: #26AFE3;
    }

    </style>
</head>

<body class="blue">
<div id="particles"></div>

    
    <div class="join_table" style="z-index: 999;opacity: 0.9;">

        <div class="register-title">微点应用管理中心</div>

        <form role="form"  action="<?=url('login/loging')?>" method="post" >

            <table width="100%" cellpadding="0" cellspacing="0" border="0">

                <tbody>



                <tr>

                    <td class="controls">

                        <input type="text" class="text_01 b_r3" id="username" data-rule-required="true" data-rule-mobile="true" name="username" placeholder="用户名">

                    </td>

                </tr>

                <tr>

                    <td class="controls">

                        <input type="password" class="text_01 b_r3" id="password" data-rule-required="true" data-rule-yzpassword="true" name="password" placeholder="请输入密码">

                    </td>

                </tr>




                <tr>

                    <td>

                        <input mini="submit" type="submit" class="s_btn tj_btn bg_orange b_r24 jq_reg" name="submit" value="登 录">

                    </td>

                </tr>

                </tbody>

            </table>

        </form>

    </div>

</div>

</body>

</html>

<script type="text/javascript">
particlesJS("particles", {
    "particles": {
        "number": {
            "value": 30,
            "density": {
                "enable": true,
                "value_area": 800
            }
        },
        "color": {
            "value": "#ffffff"
        },
        "shape": {
            "type": "circle",
            "stroke": {
                "width": 0,
                "color": "#000000"
            },
            "polygon": {
                "nb_sides": 5
            },
            "image": {
                "src": "img/github.svg",
                "width": 100,
                "height": 100
            }
        },
        "opacity": {
            "value": 0.5,
            "random": false,
            "anim": {
                "enable": false,
                "speed": 1,
                "opacity_min": 0.1,
                "sync": false
            }
        },
        "size": {
            "value": 10,
            "random": true,
            "anim": {
                "enable": false,
                "speed": 50,
                "size_min": 0.1,
                "sync": false
            }
        },
        "line_linked": {
            "enable": true,
            "distance": 300,
            "color": "#ffffff",
            "opacity": 0.4,
            "width": 2
        },
        "move": {
            "enable": true,
            "speed": 8,
            "direction": "none",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
                "enable": false,
                "rotateX": 600,
                "rotateY": 1200
            }
        }
    },
    "interactivity": {
        "detect_on": "canvas",
        "events": {
            "onhover": {
                "enable": false,
                "mode": "repulse"
            },
            "onclick": {
                "enable": false,
                "mode": "push"
            },
            "resize": true
        },
        "modes": {
            "grab": {
                "distance": 800,
                "line_linked": {
                    "opacity": 1
                }
            },
            "bubble": {
                "distance": 800,
                "size": 80,
                "duration": 2,
                "opacity": 0.8,
                "speed": 3
            },
            "repulse": {
                "distance": 400,
                "duration": 0.4
            },
            "push": {
                "particles_nb": 4
            },
            "remove": {
                "particles_nb": 2
            }
        }
    },
    "retina_detect": true
});
</script>






        
        
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
