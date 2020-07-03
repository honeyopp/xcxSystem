<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:45:"D:\WWW\demo2/youge/home\view\index\index.html";i:1514394446;s:40:"D:\WWW\demo2/youge/home\view\layout.html";i:1514394384;}*/ ?>
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
<div class="g-firstSlide">
	<div class="m-swiper m-swiper-main j-swiper" style="opacity: 0">
		<div class="swiper-wrapper">
			<!--幻灯片 S-->
			<div class="swiper-slide view_left" style="background-color: #151d3e">
				<div class="container">
					<div class="group_info">
						<div class="vertical">
							<div STYLE="color: #FFF;font-size: 45px;line-height: 68px;font-weight: 100;!important; " class="info_img">
								微点应用小程序
								<BR/> 国内领先、免费的小程序产品
							</div>
							<div STYLE="color: #9a9ca8;font-size: 15px;line-height: 68px" class="info_img">
								一键授权，快速拥有，助您畅享小程序流量红利
							</div>
							<div class="inf_btns">
								<a href="/manage/passport/register.html" class="u-btn btn-fill" target="_blank">免费注册</a>
								<a href="/manage/passport/login.html" class="u-btn btn-outline" target="_blank">立即登录</a>
							</div>
						</div>
					</div>
					<div class="group_bg">
						<img class="img" src="/public/styles/picture/othx70mazthb7cta9ppxar9k7rbbr8.png">
					</div>
				</div>
			</div>

			<div class="swiper-slide view_left" style="background-color: #1242ac">
				<div class="container">
					<div class="group_info">
						<div class="vertical">
							<div STYLE="color: #FFF;font-size: 48px;line-height: 68px;font-weight: 100;!important; " class="info_img">
								全行业解决方案
								<BR/> 免费·安全·专业·便捷
							</div>
							<div STYLE="color: #9a9ca8;font-size: 16px;line-height: 68px" class="info_img">
								全面助力传统企业快速进入移动互联网时代
							</div>

							<div class="inf_btns">
								<a href="/manage/passport/register.html" class="u-btn btn-fill" target="_blank">免费试用</a>
							</div>
						</div>
					</div>
					<div class="group_bg">
						<img class="img" src="/public/styles/picture/sj6jzyhwjj064qcwsj3yv4vczhd06q.png">
					</div>
				</div>
			</div>

			<div class="swiper-slide view_left" style="background-color: #181647">
				<div class="container">
					<div class="group_info">
						<div class="vertical">
							<div STYLE="color: #FFF;font-size: 48px;line-height: 68px;font-weight: 100;!important; " class="info_img">
								轻松打造小程序
								<BR/> 无需代码、可视化操作
							</div>

							<div class="inf_btns">
								<a href="/manage/passport/register.html" class="u-btn btn-fill" target="_blank">免费试用</a>
								<!--<a href="" class="u-btn btn-outline" target="_blank">查看详情</a>-->
							</div>
						</div>
					</div>
					<div class="group_bg">
						<img class="img" src="/public/styles/picture/ii5cmja7m17jn8yr5adztrrg55d7j5.png">
					</div>
				</div>
			</div>
			<div class="swiper-slide view_left" style="background-color: #181647">
				<div class="container">
					<div class="group_info">
						<div class="vertical" style="text-align: center;">
							<div STYLE="color: #FFF;font-size: 48px;line-height: 68px;font-weight: 100;!important; " class="info_img">
								招募服务商
								<BR/> 微信小程序元年，千亿市场等你来
							</div>

							<div class="inf_btns">
								<a href="/manage/passport/register.html" class="u-btn btn-fill" target="_blank">申请成为服务商</a>
							</div>
						</div>
					</div>
					<div class="group_bg">
						<img class="img" src="/public/styles/picture/qkrqzrior1loq3gml1idqaqt1cm34o.jpg">
					</div>
				</div>
			</div>
			<!--<div class="swiper-slide view_left" style="background-color: #181647">
				<div class="container">
					<div class="group_bg">
						<img class="img" src="">
					</div>
				</div>
			</div>-->
		</div>
		<div style="bottom: 90px" class="pagination">

		</div>
	</div>
</div>

<div class="g-section g-section-efficiency">
	<div class="g-section__main">
		<div class="m-section-efficiency">
			<div class="m-section__head">
				<span class="u-title big black center">坚如磐石，稳定服务</span>
			</div>
			<div class="m-section__body">
				<ul class="m-section__list">
					<li class="m-section__item">
						<p style="font-weight: 400;margin-top: 15px;font-size: 85px;color: #558cff" class="img">
							快
						</p>
						<p class="text">更新快，服务快，售后快</p>
					</li>
					<li class="m-section__item">
						<p style="font-weight: 500;margin-top: 15px;font-size: 85px;color: #558cff" class="img">
							准
						</p>
						<p class="text">趋势准，行业准，方式准</p>
					</li>

					<li class="m-section__item">
						<p style="font-weight: 500;margin-top: 15px;font-size: 85px;color: #558cff" class="img">
							稳
						</p>
						<p class="text">代码稳，安全稳，公司稳</p>
					</li>

					<li class="m-section__item">
						<p style="font-weight: 500;margin-top: 15px;font-size: 85px;color: #558cff" class="img">
							美
						</p>
						<p class="text">界面美，体验美，售后美</p>
					</li>

				</ul>

			</div>
		</div>
	</div>
</div>

<div class="g-section g-section-value">
	<div class="g-section__main">
		<div class="m-section-value" style=" padding-bottom: 100px;">
			<div class="m-section__head">
				<span class="u-title big white center">打造行业领先价值</span>
			</div>
			<div class="m-section__body">
				<ul class="m-section__list">
					<li class="m-section__item">
						<p class="img"><img src="/public/styles/picture/value.png" width="50%"/></p>
						<p class="text">商业价值</p>
						<p class="summary">
							共享微信全新生态， 触手可及微信9亿用户，低门槛 、易推广
						</p>
					</li>
					<li class="m-section__item">
						<p class="img"><img src="/public/styles/picture/fast.png" width="50%"/></p>
						<p class="text">一键授权</p>
						<p class="summary">
							只需扫码一键授权，无需任何开发，即可拥有自己的小程序
						</p>
					</li>
					<li class="m-section__item">
						<p class="img"><img src="/public/styles/picture/user.png" width="50%"/></p>
						<p class="text">用户体验</p>
						<p class="summary">
							全新的用户体验与性能,各种场景核心功能效果堪比原生APP

						</p>
					</li>
					<li class="m-section__item">
						<p class="img"><img src="/public/styles/picture/free.png" width="50%"/></p>
						<p class="text">免费试用</p>
						<p class="summary">
							微点应用承诺所有产品免费试用，免除您的后顾之忧

						</p>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<section class="home-row-32" style="background:#eff4f5" id="floor1">
	<div class="wrap" style="padding-left: 16px;">
		<div class="m-section__head">
			<p class="u-title big black center">微点应用解决方案</p>
			<p class="u-summary center" style="    margin-top: 20px;">
				国内领先、免费、最全的小程序产品，助您畅享小程序流量红利
			</p>
		</div>

		<div class="wrap s-bg-gy" style="background: #eff4f5;">
			<dl class="g-box m-dl">

				<dd>
					<ul class="f1" style="margin-left: 50px;">
					
					<?php foreach($list as $val) { ?>
												<li>
							<div class="pricing-row1-item list<?=$val->orderby?>" style="padding-top: 15px; padding-bottom: 15px;">
								<img style="height: 140px;" src="/attachs/uploads/<?=getImg($val->photo)?>">
								<div class="member"><?=$val->title?></div>
								<div class="seat">微点应用,打造行业领先价值</div>
							</div>
							<a href="" target="_blank" class="f1-h">
								<p class="g-tac f1-t1"><?=$val->title?></p>
								<p class="g-mt14 s-fc-gy"> <?=$val->describe?></p>
								<p class="g-mt14"><strong>适用于：</strong><span class="s-fc-gy">企业官网、婚纱、汽车、房产、自媒体等</span>
								</p>
								<p class="f1-lnk"><span class="tip" onclick="location.href='<?=url('/home/shop/detail',['miniapp_id'=>$val->miniapp_id])?>';return false;"> 了解详情</span></p>
							</a>
						</li>
					         <?php } ?>	

							
											</ul>
				</dd>
			</dl>
		</div>

	</div>
</section>

<section class="indexMod module1" id="floor2">

	<div class="m-section-times__head" style="padding-top: 90px;padding-bottom: 50px;text-align: center">
		<span class="u-title big black center">微点应用小程序案例</span>
		<p style="padding-top: 25px" class="u-summary center">微点应用拥有丰富的场景应用，一键生成自己的专属微信小程序 </p>
	</div>

	<div class="projectDesc">
		<div class="wrap" style="width: 1080px;!important; ">
			<ul class="clearfix animateSwing">

				<div class="casebox">
					<ul class="clearfix caselist" style="margin-left: 50px;margin-right: 50px;">
						
		<?php foreach($list as $val) { ?>
                <li>
				<div class="casema"><?php if(!empty($val->qrcode)){?>
					<img src="/attachs/uploads/<?=getImg($val->qrcode)?>" width="180" height="180">  <?php }?>
				</div><img style=" border-radius: 5px;border: 1px solid #E8E7E7;" src="/attachs/uploads/<?=getImg($val->photo)?>" width="100%">
				<div class="p15">
				<h2 class="anlitupian" class="mid-font"><?=$val->title?></h2>
				</div>
                   
                <?php } ?>
				</li>
				</ul>

				</div>
			</ul>
		</div>
	</div>

</section>

<div class="g-section g-section-times">
	<div class="g-section__main" style=" width: 1080px">
		<div class="m-section-times">
			<div class="m-section-times__head">
				<span class="u-title big black center">适用行业</span>
				<p style="padding-top: 25px" class="u-summary center">微点应用拥有丰富的场景应用，为不同行业提供小程序核心功能 </p>
			</div>
			<div class="scenes wrap">
				<ul class="clearfix" style="width: 1080px;">
					<li class="li-2">
						<div class="texts">
							<h3>餐饮行业</h3>
						</div>
						<span style="background-image: url(/public/styles/images/canyin.png);"></span>
					</li>

					<li class="li-2">
						<div class="texts">
							<h3>移动电商</h3>
						</div>
						<span style="background-image: url(/public/styles/images/dianshang1.png);"></span>
					</li>

					<li class="li-2">
						<div class="texts">
							<h3>房产行业</h3>
						</div>

						<span style="background-image: url(/public/styles/images/fangchan.png);"></span>
					</li>

					<li class="li-2">
						<div class="texts">
							<h3>果蔬生鲜</h3>
						</div>

						<span style="background-image: url(/public/styles/images/gssx.png);"></span>
					</li>

					<li class="li-2">
						<div class="texts">
							<h3>糕点烘焙</h3>
						</div>

						<span style="background-image: url(/public/styles/images/hongbei.png);"></span>
					</li>

					<li class="li-2">
						<div class="texts">
							<h3>家居家纺</h3>
						</div>

						<span style="background-image: url(/public/styles/images/jiaju.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>运动健身</h3>
						</div>

						<span style="background-image: url(/public/styles/images/jianshen.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>金融行业</h3>
						</div>

						<span style="background-image: url(/public/styles/images/jinrong.jpg);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>教育行业</h3>
						</div>

						<span style="background-image: url(/public/styles/images/jiaoyu.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>美甲行业</h3>
						</div>

						<span style="background-image: url(/public/styles/images/meijia.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>美容美肤</h3>
						</div>

						<span style="background-image: url(/public/styles/images/meirong.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>母婴亲子</h3>
						</div>

						<span style="background-image: url(/public/styles/images/muying.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>汽车行业</h3>
						</div>

						<span style="background-image: url(/public/styles/images/qiche.png);"></span>
					</li>

					<li class="li-2" style="text-align: center;margin-top: 1%;">
						<div class="texts">
							<h3>婚纱摄影</h3>
						</div>

						<span style="background-image: url(/public/styles/images/sheying.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>珠宝行业</h3>

						</div>
						<span style="background-image: url(/public/styles/images/zhubao.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>家装行业</h3>

						</div>
						<span style="background-image: url(/public/styles/images/jiafang.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>箱包服饰</h3>
						</div>

						<span style="background-image: url(/public/styles/images/xbfs.png);"></span>
					</li>

					<li class="li-2" style="margin-top: 1%;">
						<div class="texts">
							<h3>更多行业……</h3>

						</div>
						<span style="background-image: url(/public/styles/images/hangye1.png);"></span>
					</li>

				</ul>
			</div>

		</div>
	</div>
</div>


<div class="g-section g-section-choice">
	<div class="g-section__main">
		<div class="m-section-choice">
			<div class="m-section__head">
				<p class="u-title big black center">有前瞻性的企业，都选择了微点应用</p>
				<p class="u-summary center">
					来自互联网金融、电商、医疗、O2O、在线教育、企业服务等多个领域的企业接入了微点应用小程序管理系统
				</p>
			</div>
			<div class="m-section__body">
				<p class="img">
					<img src="/public/styles/images/choice@2x.png" width="1000">
				</p>
			</div>
		</div>
	</div>
</div>
<script src="/public/styles/js/swiper.min.js"></script>
<div class="qc-footer J-qc-footer">
	<div class="qc-footer-action" id="g-footer-guide" style="">
		<div class="footer-action" data-id="g-footer-guide-1" style="">
			<div class="text">现在注册，即可免费试用所有产品</div>
			<div class="op-btns">
				<a href="/manage/passport/register.html" class="bt" hotrep="hp.footer.guide.reg">免费注册</a>
			</div>
		</div>
		<svg class="action-bg" xmlns="http://www.w3.org/2000/svg">
			<g>
				<defs>
					<linearGradient id="Gradient1">
						<stop offset="0%" stop-color="#fff" stop-opacity="0"></stop>
						<stop offset="99%" stop-color="#fff" stop-opacity="0.2"></stop>
						<stop offset="99.9%" stop-color="#fff" stop-opacity="0.8"></stop>
						<stop offset="100%" stop-color="#fff" stop-opacity="0.8"></stop>
					</linearGradient>
				</defs>
				<!--第1行三种线条 start-->
				<rect x="0" y="30" width="200" height="2" stroke="white" stroke-width="0" fill="url(#Gradient1)">
					<animate attributeName="x" from="0" by="100%" begin="0" dur="15s" repeatCount="indefinite"></animate>
				</rect>
				<!--第1行三种线条 end-->

				<!--第2行三种线条 start-->
				<rect x="0%" y="60" width="300" height="2" stroke="white" stroke-width="0" fill="url(#Gradient1)">
					<animate attributeName="x" from="0" by="100%" begin="25s" dur="15s" repeatCount="indefinite"></animate>
				</rect>
				<!--第2行三种线条 end-->

				<!--第3行三种线条 start-->
				<rect x="0" y="100" width="100" height="2" stroke="white" stroke-width="0" fill="url(#Gradient1)">
					<animate attributeName="x" from="0" by="100%" begin="5s" dur="15s" repeatCount="indefinite"></animate>
				</rect>

			</g>
		</svg>
	</div>
</div>
<script>
	$(function() {

		$('a[href*=#],area[href*=#]').click(function() {
			if(location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
				var $target = $(this.hash);
				$target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
				if($target.length) {
					var targetOffset = $target.offset().top;
					$('html,body').animate({
						scrollTop: targetOffset
					}, 1000);
					activenav("#" + this.hash.slice(1));
					return false;
				}
			}
		});
		activenav();

		function activenav(thisId) {
			var thisId = thisId ? thisId : window.location.hash;
			$('.m-topNav li').removeClass('z-sel');
			if(!thisId || thisId == '#floor0') {
				$('.m-topNav li:first').addClass('z-sel');
			}
			if(thisId == '#floor1') {
				$('.m-topNav li:nth-child(2)').addClass('z-sel');
			}
			if(thisId == '#floor2') {
				$('.m-topNav li:nth-child(3)').addClass('z-sel');
			}
		}
		$(window).scroll(function() {
			var top = $(document).scrollTop(); //定义变量，获取滚动条的高度
			var items = $("body").find(".wxappMod"); //定义变量，查找.item
			var curId = ""; //定义变量，当前所在的楼层item #id
			items.each(function() {
				curId = $(this).attr("id"); //定义变量，获取当前类
				var itemsTop = $(this).offset().top; //定义变量，获取当前类的top偏移量
				if(top > itemsTop - 50 && curId) {
					curId = "#" + curId;
					activenav(curId);
				} else {
					return false;
				}
			});
		});
	});
</script>
<!--[if lt IE 9]>
<![endif]-->

<script>
	/* 首屏轮播图 */
	(function() {
		var mySwiper = new Swiper('.m-swiper-main', {
			initialSlide: 0,
			resizeReInit: true, //window如果resize则重新初始化
			updateOnImagesReady: true,
			preventLinks: true,
			preventLinksPropagation: true,
			pagination: '.pagination',
			paginationClickable: true,
			mousewheelControl: false,
			DOMAnimation: true,
			autoplay: 2000,
			speed: 550,
			loop: true
		});
		// 控制是否自动播放
		$(".j-swiper").hover(function() {
			mySwiper.stopAutoplay();
		}, function() {
			mySwiper.startAutoplay();
		});
	})();
	// 防止加载闪动
	$(".g-firstSlide .j-swiper").css("visibility", "visible").animate({
		opacity: 1
	}, 500);
</script>
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