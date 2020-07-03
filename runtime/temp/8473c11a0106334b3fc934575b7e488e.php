<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:61:"/www/wwwroot/xcx.cpasem.com/youge/home/view/mobile/index.html";i:1514189986;}*/ ?>
<!DOCTYPE html >
<html>
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>微点应用专注于旅游行业研发的微信小程序系统，公司开发了民宿小程序、酒店小程序、农家乐小程序！15169991113</title>
        <meta name="Keywords" content="民宿小程序、酒店预定小程序、农家乐小程序、门票小程序、景区小程序、周边游小程序、亲子游小程序！" />
        <meta name="Description" content="微点应用专注于旅游行业小程序解决方案！" />
        <link rel="stylesheet" href="/public/home/css/mobile.css"/>
        <script type="text/javascript" src="/public/home/js/jquery.min.js"></script>
    </head>
    <body>
        <div class="header">
            微点应用
        </div>
        <div class="page1">
            <div class="banner">
                <img src="/public/home/banner/mobile.png" />
            </div>
             <div class="product">
                <div class="title">我们的产品</div>
                <?php foreach($list as $k=>$val){?>
                <div  class="item">
                    <div class="img"><img src="/attachs/uploads/<?=getImg($val->photo);?>" /></div>
                    <div class="name"><?=$val->title?></div>
                </div>
                <?php }?>
            </div>
            <div class="info">
                <div class="title">小程序，刚刚开始的风口</div>
                <div class="content">
                    微信小程序是腾讯继公众号之后在微信平台内打造的一种全新的连接线下线上的功能应用。
                    通过小程序用户能更便捷的获得商家的服务。
                    微信开放了40多个流量入口，也使得小程序推广起来十分方便！
                    前100的应用中微信旅游占据了7%，另外出行也占据了7%，综合的说微信旅游潜力十分巨大！
                </div>
            </div>
            <!---微信旅游的优势--->
            <div class="youshi">
                <div class="title">小程序优势</div>

                <div class="item">
                    <div class="img"><img src="/public/home/images/icon/1.png" /></div>
                    <div class="tt">免下载</div>
                    <div class="con">消费者无需下载APP就可以访问小程序，便捷方便</div>
                </div>
                <div class="item">
                    <div class="img"><img src="/public/home/images/icon/2.png" /></div>
                    <div class="tt">快捷分享</div>
                    <div class="con">一键可以分享到微信群和朋友，熟人分享更容易成交！</div>
                </div>
                <div class="item">
                    <div class="img"><img src="/public/home/images/icon/3.png" /></div>
                    <div class="tt">访问快</div>
                    <div class="con">第一次加载后，会缓存在手机微信比传统H5访问更流畅</div>
                </div>
                <div class="item">
                    <div class="img"><img src="/public/home/images/icon/4.png" /></div>
                    <div class="tt">跨平台</div>
                    <div class="con">android/ios都支持小程序，只要有微信的地方都能浏览</div>
                </div>
                <div class="item">
                    <div class="img"><img src="/public/home/images/icon/5.png" /></div>
                    <div class="tt">省流量</div>
                    <div class="con">比传统H5要省更多流量，小程序是经过压缩处理过的</div>
                </div>
                <div class="item">
                    <div class="img"><img src="/public/home/images/icon/6.png" /></div>
                    <div class="tt">流量入口多</div>
                    <div class="con">微信小程序有多达40余种流量入口，腾讯也在进一步开放更多入口</div>
                </div>

            </div>

           
        </div>
        <div class="page2">
            
            <div class="logo">
                <img src="/public/home/images/logo.png" />
            </div>
            
            <div class="addr">
                <div class="lt">
                    <img src="/public/home/images/icon/tel.png" />
                </div>
                <div class="rt">
                    <div class="tp">联系电话</div>
                    <div class="cn"><a href="tel:15169991113">15169991113</a></div>
                </div>
            </div>
            <div class="addr">
                <div class="lt">
                    <img src="/public/home/images/icon/wx.png" />
                </div>
                <div class="rt">
                    <div class="tp">微信</div>
                    <div class="cn"><a href="tel:15169991113">15169991113</a></div>
                </div>
            </div>
            <div class="addr">
                <div class="lt">
                    <img src="/public/home/images/icon/qq.png" />
                </div>
                <div class="rt">
                    <div class="tp">QQ</div>
                    <div class="cn">67930603</div>
                </div>
            </div>
            <div class="addr">
                <div class="lt">
                    <img src="/public/home/images/icon/addr.png" />
                </div>
                <div class="rt">
                    <div class="tp">公司地址</div>
                    <div class="cn">微点应用小程序研发中心</div>
                </div>
            </div>
            
            <a href="tel:15169991113" class="btn">立刻咨询平台客服</a>
            
            <div class="cp">
                <div class="cnname">微点应用</div>
                <div class="enname">微点应用小程序平台无限制生成</div>
            </div>
        </div>
        <div class="kongbai">&nbsp;</div>

        <div class="footer">
            <div class="home on">
                <div class="img">
                    <img class="img1" src="/public/home/images/home1.png" />
                    <img class="img2" src="/public/home/images/home2.png" />
                </div>
                <div class="name">官网首页</div>
            </div>
            <div class="wode off">
                <div class="img ">
                    <img class="img1" src="/public/home/images/wode1.png" />
                    <img class="img2" src="/public/home/images/wode2.png" />
                </div>
                <div class="name">关于</div>
            </div>
        </div>
        <script>
            $(document).ready(function(e){
                $(".footer .wode").click(function(e){
                    $(this).removeClass('off');
                    $(this).addClass('on');
                    $(".page2").show();
                    $(".page1").hide();
                    $(".home").removeClass('on');
                    $(".home").addClass('off');
                    $(".header").text('联系我们');
                });
                 $(".footer .home").click(function(e){
                    $(this).removeClass('off');
                    $(this).addClass('on');
                    $(".page1").show();
                    $(".page2").hide();
                    $(".wode").removeClass('on');
                    $(".wode").addClass('off');
                    $(".header").text('微点应用');
                });
                $(".footer .home").click();
            });
        </script>
    </body>
</html>
