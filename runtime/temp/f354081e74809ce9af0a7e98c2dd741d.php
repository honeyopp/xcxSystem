<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:43:"D:\WWW\demo2/youge/home\view\faq\index.html";i:1513522666;s:40:"D:\WWW\demo2/youge/home\view\layout.html";i:1514394384;}*/ ?>
﻿<!DOCTYPE html>
<html dir="ltr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>微点应用</title>
		<meta name="keywords" content="微点应用,微信小程序,小程序,小程序源码,小程序开发,拼团小程序,商城小程序,门店小程序,小程序代理,小程序加盟,微信小程序开发" />
		<meta name="description" content="微点应用-微信小程序生成平台|微信小程序开发|微信小程序源码|小程序代理" />
		<link type="text/css" rel="stylesheet" href="/public/styles/css/welcome.css">
		<link type="text/css" rel="stylesheet" href="/public/styles/css/common.css">
		<link type="text/css" rel="stylesheet" href="/public/styles/css/joincss.css">
		<link type="text/css" rel="stylesheet" href="/public/styles/css/aboutcss.css">
		<link type="text/css" rel="stylesheet" href="/public/styles/css/welcome2.css">
		<link type="text/css" rel="stylesheet" href="/public/styles/css/welcome3.css">
		<link type="text/css" rel="stylesheet" href="/public/styles/css/layer.css">
		<script type="text/javascript" src="/public/styles/js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="/public/styles/js/jquery.bxslider.min.js"></script>
		<script type="text/javascript" src="/public/styles/js/layer.js"></script>
	</head>
	<body>
		<style>
			.m-topLogo{width: 111px;height: 35px;background: url(/public/styles/images/vvez7fzn5nt5fn6yc2nvjt5nyv6tq1.png) no-repeat;background-size: 111px auto;}
			.m-topLogo.solid{background: url(/public/styles/images/m6imenbfx85rllz5jnlixx76finzjm.png) no-repeat;background-size: 111px auto;height: 35px;}
		</style>
		<div class="g-topNav moveDown" style="top: 0px;">
			<div class="wrapper">
				<a href="#" target="_self">
					<div class="m-topLogo ">
						<h1> 微点应用</h1>
					</div>
				</a>
				<div class="m-topNav ">
					<ul class="menu">
						<li class="product">
							<a href="<?=url('index/index')?>">
								首页<?=$footer == 1 ? '<span class="u-icon-caret"></span>' : ''?>
							</a>
						</li>
						<li class="product">
							<a href="<?=url('shop/index')?>">
								应用大厅<?=$footer == 2 ? '<span class="u-icon-caret"></span>' : ''?>
							</a>
						</li>
						<li class="kehu">
							<a href="<?=url('news/index')?>">
								最新动态<?=$footer == 3 ? '<span class="u-icon-caret"></span>' : ''?>
							</a>
						</li>
						<li >
							<a href="<?=url('faq/index')?>">
								帮助中心<?=$footer == 4? '<span class="u-icon-caret"></span>' : ''?>
							</a>
						</li>
						<li >
							<a href="<?=url('member/index')?>">
								招商加盟<?=$footer == 6 ? '<span class="u-icon-caret"></span>' : ''?>
							</a>
						</li>
						<!--<li class="qa">
							<a href="javascript:;" onclick="help();">
								帮助中心
								<span class="u-icon-caret"></span>
							</a>
						</li>-->
												<li >
							<a href="<?=url('about/index')?>">
								关于我们<?=$footer == 6 ? '<span class="u-icon-caret"></span>' : ''?>
							</a>
						</li>
											</ul>
				</div>
								<div class="m-login ">
					<a href="/manage/passport/login.html" class="u-btn signin">登录</a>
					<a href="/manage/passport/register.html" class="u-btn signup">注册</a>
				</div>
							</div>
		</div>
		<!--<script type="text/javascript">
			function help() {
				layer.open({
					type: 2,
					title: '帮助中心',
					shadeClose: true,
					shade: 0.8,
					area: ['90%', '90%'],
					content: "faq/index"
				});
			}
		</script>--><style type="text/css">
	.m-swiper-main .swiper-slide.view_left .group_bg {
	    position: absolute;
	    right: -370px;
	    top: 0;
	    text-align: right;
	    z-index: 1;
	}
</style>

<link rel="stylesheet" href="/public/home/css/index.css"/>
<link rel="stylesheet" href="/public/home/css/helper.css"/>
<div class="helper-body">
    <div class="helper-top">
        <div class="helper-top-logo">
            <a href="#"></a>
        </div>
    </div>
    <div class="helper-list">
        <ul class="help_ul_css" id="help_ul_id">
       
            <li class="last_li">
                <div class="help_question_css active" id="help_question_id">
                    <div class="question_box_css">
                        <div class="question_css">Q:微信小程序有什么优势?</div>
                        <div class="answer_css">A:
                            1，不用安装，即开即用，用完就走。省流量，省安装时间，不占用桌面；
                            2，体验上虽然没法完全媲美原生APP，但综合考虑还是更优；
                            3，对于小程序拥有者来说，开发成本更低，他们可以更多财力，人力，精力放在如何运营好产品，做好内容本身；
                            4，对于用户来说，相较于各种APP，微信小程序UI和操作流程会更统一。这也会降低用户的使用难度；
                            5，对于小程序拥有者来说，相较于原生APP，推广更容易更简单，更省成本。
                        </div>
                    </div>
                    <div class="question_box_css">
                        <div class="question_css">Q:如何使用微点应用小程序</div>
                        <div class="answer_css">A:1、首先注册一个账号2、授权您的小程序3、选择应用4、提交微信审核5、正式上线</div>
                    </div>
                    <div class="question_box_css">
                        <div class="question_css">Q:做一个小程序难么，小程序贵么？</div>
                        <div class="answer_css">A:使用微点应用的微信程序试用是免费的，满意再付费商用，而且费用很便宜，低至一块钱一天！微点应用可以帮助您一键生成小程序！</div>
                    </div>
                    <div class="question_box_css">
                        <div class="question_css">Q:小程序该如何推广，流量怎么获取</div>
                        <div class="answer_css">A:
                            1、附近小程序功能：注册号商家小程序地址后，附近的用户打开：附近的小程序功能，都能找到你的小程序，范围是方圆5公里
                            2、门店扫码：这个很容易理解，就跟公众号推广一样，线下门店商家可以在门店内放一个二维码，只要顾客消费后扫一下码就能优惠，办法简单却特别有效。
                            3、推文发布：我们可以去一些高权重的新闻平台或论坛发一些推文，内容可以是介绍自己的产品或企业的，在最后放上自己的小程序二维码，具体平台选择和推文撰写这里就不细说了。
                            4、公众号推送：小程序现在支持以卡片的形式插入公众号文章中， 展现形式就跟文章中的普通图片一样，当然加减信息在这里建议一篇文章插入小程序的卡片次数要适当，就算推文内容很长插入次数也不要超过8次，多了就会影响文章的阅读性了。
                            5、大号互推：这个的形式跟上面的一样，我们可以跟一些粉丝多的大号合作互推，在他们推送的信息中插入我们小程序的二维码，也能起到很好的引流效果。
                        </div>
                    </div>
              
                </div>
            </li>
        </ul>
    </div>
</div>
<script>
	window.onload = function() {
		var xpadFlag = false;
		$(document).on("touchend", function() {
			$(".m-topNav .panel").hide();
		});
		$(".m-topNav li").on('touchend', function(e) {
			xpadFlag = true;
			e.stopPropagation();
			$(this).toggleClass("open");
			$(".m-topNav .panel").hide();
			$(this).children(".panel").show();
		}).children("a").on("click", function(e) {
			e.stopPropagation();
			if($(this).next(".panel").length && xpadFlag) {
				e.preventDefault();
			}
		});
	}
</script>
<script>
	/* 顶部导航动画 */
	$(window).on('scroll', function() {
		if($(document).scrollTop() > 10) {
			$('.g-topNav').addClass('solid');
			$('.g-topNav .m-topLogo').addClass('solid');
			$('.g-topNav .m-topNav').addClass('solid');
			$('.g-topNav .m-login').addClass('solid');
			$('.g-topNav').removeClass('moveDown');
			$('.g-yixin').addClass('moveUp');
		} else {
			$('.g-topNav').removeClass('solid');
			$('.g-topNav .solid').removeClass('solid');
			$('.g-topNav').addClass('moveDown');
			$('.g-yixin').removeClass('moveUp');
		}
	});
</script>
<script>
	$(".m-modalTipDemo .close").on("click", function() {
		$(".m-modalTipDemo").hide();
		$(document.body).css("overflow", "auto");
	});
	$(document).on("keydown", function(event) {
		if(event.keyCode == 27) {
			$(".m-modalTipDemo").hide();
			$(document.body).css("overflow", "auto");
		}
	});
</script>
<link rel="stylesheet" href="/public/styles/css/global.css">
<div class="qc-footer-service"  style="background-color: #191b1f"  >
	<div class="footer-service" >
		<div class="fs-list">
			<div class="fs-ct">
				<p href="" class="item" target="_blank">
					<span class="icon icon-1"></span>
					<span class="term">百倍故障赔偿</span>
				</p>
			</div>
			<div class="fs-ct">
				<p href="" class="item" target="_blank">
					<span class="icon icon-2"></span>
					<span class="term">5天无理由退款</span>
				</p>
			</div>
			<div class="fs-ct">
				<p href="" class="item" target="_blank">
					<span class="icon icon-3"></span>
					<span class="term">阿里云异地灾备</span>
				</p>
			</div>
			<div class="fs-ct">
				<p href="" class="item" target="_blank">
					<span class="icon icon-4"></span>
					<span class="term">1V1大客户服务</span>
				</p>
			</div>
			<div class="fs-ct item-last">
				<p href="" class="item" target="_blank">
					<span class="icon icon-5"></span>
					<span class="term">7x24小时服务</span>
				</p>
			</div>
		</div>
	</div>
</div>


<footer id="footer">
			<div class="footer-top" style="background-color: #191b1f;padding-top: 25px;padding-bottom: 25px;" >
				<div class="wrap">
					<dl>
						<dt>产品服务</dt>
												<dd>
							<a href="">微站</a>
						</dd>
												<dd>
							<a href="">门店</a>
						</dd>
												<dd>
							<a href="">商城</a>
						</dd>
												<dd>
							<a href="">拼团</a>
						</dd>
											</dl>
					<dl>
						<dt>关于</dt>
							<dd><a href="<?=url('index/index')?>">官网首页</a></dd>
							<dd><a href="<?=url('shop/index')?>">应用大厅</a></dd>
							<dd><a href="<?=url('news/index')?>" >最新动态</a></dd>
							<dd><a href="<?=url('faq/index')?>" >帮助中心</a></dd>
							<dd><a href="<?=url('member/index')?>" >招商加盟</a></dd>
							<dd><a href="<?=url('about/index')?>" >关于我们</a></dd>
			
					</dl>
					<dl>
						<dt>联系我们</dt>
						<dd>
							<a href="javascript:;">联系电话：15169991113</a>
						</dd>
						<dd>
							<a href="javascript:;">QQ：67930603</a>
						</dd>
						<dd>
							<a href="javascript:;">微信：yanervip</a>
						</dd>
						<dd>
							<a href="javascript:;">地址：微点应用小程序研发中心</a>
						</dd>
					</dl>
				</div>
			</div>

		</footer>

<div class="qc-footer J-qc-footer">
	<div class="qc-footer-blogroll" style="background-color: #15161a;padding-bottom: 15px;padding-top: 15px;" >
			<div class="blogroll-inner" style="text-align: center">
				<div class="link-set">

					<div class="links">
						<p class="line copyright">
							<span class="slide">
							Copyright (c)2015 - 2017 微点应用版权所有 版权所有 | 鲁ICP备16033895号-1						</span>
						</p>
					</div>
				</div>
				<div class="locale J-footerSwitchLang">
					<a href="" class="locale-link">   </a>
				</div>
			</div>
	</div>
</div>






		<div id="toolitembar">
			<a href="http://wpa.qq.com/msgrd?v=3&uin=67930603&site=qq&menu=yes" id="toolitembar-support"><i class="icon-live-help"></i>
				<span>在线客服</span>
			</a>
			<a href="tel:15169991113" id="toolitembar-phone"><i class="icon-call"></i>
				<span>电话咨询</span>
			</a>
			<a href="javascript:;" id="toolitembar-info"><i class="icon-info"></i>
				<span>查看演示</span>
			</a>
			<a href="/manage/passport/register.html" id="toolitembar-signin"><i class="icon-description"></i>
				<span>免费注册</span>
			</a>
			<a href="javascript:;" id="back-top"><i class="icon-chevron-thin-up"></i>
				<span>返回顶部</span>
			</a>
		</div>
	</body>
</html>