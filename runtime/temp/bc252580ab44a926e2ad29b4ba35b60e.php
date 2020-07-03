<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"D:\WWW\demo2/youge/home\view\member\index.html";i:1513522666;s:40:"D:\WWW\demo2/youge/home\view\layout.html";i:1514394384;}*/ ?>
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
<div class="g-section intro" style="color: #ffffff;margin-top: -1px;background: url(/public/styles/picture/joinbg.jpg) center 100% no-repeat;background-size: cover;text-align: center;padding-bottom: 82px;">
    <div class="m-intro">
        <p style="color: #ffffff;font-size: 38px;margin-top: 20px;"  class="u-title1">
            微点应用招募全国合作伙伴共赢市场
        </p>
        <div class="list">
            <div class="item" style="height: 100px;margin-top: 5px;" >
                <p  style="height: 80px;color: #ffffff;font-size: 16px;font-weight: 400;margin-left: 25px;margin-right: 25px;" class="value">
                    微点应用开启小程序合伙人计划，诚邀各位共同发掘小程序千亿蓝海。2017，微信小程序元年，千亿市场等你来!                </p>
            </div>

            <a href="/manage/passport/register.html" data-arm="#j-joinForm"  style="padding-top: 10px;padding-bottom: 10px;padding-left: 25px; padding-right: 25px;" class="u-btn btn-fill" target="_blank">申请成为服务商</a>

        </div>
    </div>
</div>

<div class="j-tabContent"  style="display: block;">
    <div class="g-section g-section-whyme" >
        <div class="m-whyme">
            <h3 class="title1">为什么选择微点应用？</h3>
            <div class="items">
                <div class="itm " >
                    <p class="img">
                        <img src="/public/styles/images/Accounting.png" width="120">
                    </p>
                    <p class="ttl">产品优秀</p>
                    <p class="summary" >
                        在同质化高的产品市场中<br>
                        拥有强势的核心竞争力

                    </p>
                </div>
                <div class="itm" >
                    <p class="img">
                        <img src="/public/styles/images/Check.png" width="120">
                    </p>
                    <p class="ttl">OEM模式</p>
                    <p class="summary" >
                        服务商拥有自己的平台，为代<br>
                        理商保护自我品牌提供技术支撑

                    </p>
                </div>
                <div class="itm">
                    <p class="img">
                        <img src="/public/styles/images/MoneyTransfer.png" width="120">
                    </p>
                    <p class="ttl">收益丰厚</p>
                    <p class="summary">
                        高额的利润空间<br>
                        稳定的产品迭代更新

                    </p>
                </div>
                <div class="itm ">
                    <p class="img">
                        <img src="/public/styles/images/Goal.png" width="120">
                    </p>
                    <p class="ttl">渠道优势</p>
                    <p class="summary">
                        保证市场的规范性<br>
                        充分保证服务商利益


                    </p>
                </div>
                <div class="itm">
                    <p class="img">
                        <img src="/public/styles/images/ReceiveCash.png" width="120">
                    </p>
                    <p class="ttl">服务完善</p>
                    <p class="summary">
                        完善的营销体系和代理培训<br>
                        支持代理伙伴开展推广工作


                    </p>
                </div>
                <div class="itm ">
                    <p class="img">
                        <img src="/public/styles/images/SearchProperty.png" width="120">
                    </p>
                    <p class="ttl">生态共享</p>
                    <p class="summary">
                        加入我们共建小程序第三方市场<br>
                        互惠互利，创造更多价值

                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="g-section g-section-return">
        <div class="m-return">
            <h3 class="title1">代理微点应用，您能获得什么？</h3>
            <div class="items">
                <div class="itm first">
                    <p class="img">
                        <img src="/public/styles/images/project.png" width="70">
                    </p>
                    <p class="ttl">产品支持</p>
                    <p class="summary">
                        服务商可根据第一线市
                        场反馈，经过总部产品
                        部门评估后进行产品优
                        化，促销服务商销售工
                        作。


                    </p>
                </div>
                <div class="itm">
                    <p class="img">
                        <img src="/public/styles/images/Resources.png" width="70">
                    </p>
                    <p class="ttl">资源支持</p>
                    <p class="summary">
                        总部各渠道获取到的资
                        源线索将根据地区、转
                        化率分发给各个服务商
                        服务商将建成更多元的
                        资源获取途径。

                    </p>
                </div>
                <div class="itm">
                    <p class="img">
                        <img src="/public/styles/images/materiel.png" width="70">
                    </p>
                    <p class="ttl">物料支持</p>
                    <p class="summary">
                        总部将定期更新产品物
                        料包括产品手册、使用
                        说、介绍PPT、客户案
                        例等，便于服务商及时
                        获取产品信息。

                    </p>
                </div>
                <br>
                <div class="itm first line2">
                    <p class="img">
                        <img src="/public/styles/images/train.png" width="70">
                    </p>
                    <p class="ttl">培训支持</p>
                    <p class="summary">
                        多元化培训方式，帮助
                        服务商对销售、客服、
                        技术进行业务能力的提
                        高，迅速打造一支战斗
                        力惊人的团队。

                    </p>
                </div>
                <div class="itm line2">
                    <p class="img">
                        <img src="/public/styles/images/market.png" width="70">
                    </p>
                    <p class="ttl">市场支持</p>
                    <p class="summary">
                        展会、交流会、发布会
                        等市场活动，总部给予
                        专业市场支持，指派相
                        关人员协助市场活动开
                        展。

                    </p>
                </div>
                <div class="itm line2">
                    <p class="img">
                        <img src="/public/styles/images/address.png" width="70">
                    </p>
                    <p class="ttl">区域保护</p>
                    <p class="summary">
                        不允许服务商跨区域销
                        售以保证市场的规范性
                        充分保证服务商利益。

                </div>
            </div>
        </div>
    </div>
    <div class="g-section g-section-condition j-condition">
        <div class="m-condition" >
            <h3 class="title1">代理微点应用，您需要具备什么基础条件？</h3>
            <div class="items" >
                <div class="itm first">
                    <p class="img">
                        <img src="/public/styles/images/icon-condition1.png" width="72">
                    </p>
                    <p class="summary">
                        有互联网行业经验者优先<br>
                        具备管理经验者优先
                    </p>
                </div>
                <div class="itm" id="auto-id-1509435496606">
                    <p class="img">
                        <img src="/public/styles/images/icon-condition2.png" width="72">
                    </p>
                    <p class="summary" >
                        5人及以上的销售团队，并拥有专门<br>
                        的售前技术和售后服务体系
                    </p>
                </div>
                <div class="itm">
                    <p class="img">
                        <img src="/public/styles/images/icon-condition3.png" width="72">
                    </p>
                    <p class="summary">
                        认同微点应用发展前景和对形势的<br>判断，双方拥有共同的价值观
                    </p>
                </div>
            </div>
        </div>
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