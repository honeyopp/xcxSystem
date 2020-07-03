<script language="JavaScript"> 
document.oncontextmenu=new Function("event.returnValue=false;"); //禁止右键功能,单击右键将无任何反应 
</script>
<title>微信扫码支付</title>
<style>
    body{ background:#Fff;}
    .wx_img{ margin-top:30px;}
    .wx_img img{ border:1px solid #ddd;}
</style>   
<link rel="stylesheet" href="/public/home/css/promo.css?v=201708230932"/>
<style>
    .promoImg{height:125px;}
</style>
<script src="js/jquery.js" type="text/javascript"></script>
</head>
<body>
   <div class="hezuoCon hezuoConImgs" style="width:100%;margin:0;padding:0;display:none">
                      <div class="hezuoArticleTitle" style="margin:0;padding:0;"><span class="hezuoArticleName" style="font-size:30px;line-height:30px;font-weight:bold;">限时充值大优惠</span></div>
                      <div style="margin-left:4px;line-height:1.5;font-size:15px;">活动时间：2017年12月19日至2018年1月3日<br/>充值赠送比例：100元至499元 1:1 / 500元至999元 1:1.6 / 1000元以上 1:2</div>
                      <div class="promoImgs" style="margin:0;padding:0;">
                        <a target="_blank" id="pay100" class="promoImgAlink" href="javascript:;"><div class="promoImg"><img style="height: 100px;" src="/public/home/images/promo/1.png"></div></a>
                        <a target="_blank" id="pay500"  class="promoImgAlink" href="javascript:;"><div class="promoImg"><img style="height: 100px;"  src="/public/home/images/promo/2.png"></div></a>
                        <a target="_blank" id="pay1000" class="promoImgAlink" href="javascript:;"><div class="promoImg"><img style="height: 100px;"  src="/public/home/images/promo/3.png"></div></a>
                      </div>
  </div>
    <div align="left" >
        <form action="index.php" method="post">
			<input type="hidden" value="<?=$_GET['memberid'];?>" id="member_id" name="member_id" class="form-control"/>
			<span class="hezuoArticleName" style="font-size:30px;line-height:30px;font-weight:bold;"></span>
            <span style="font-weight:bold;" >充值金额: <input id="pirce" type="text" name="money" style="height:26px;vertical-align:middle;border: 1px solid #63a62f;" value="100" onkeyup="this.value=this.value.replace(/\D|^0/g,'')" onafterpaste="this.value=this.value.replace(/\D|^0/g,'')"/></span>
            <input type="submit" value="立刻充值" class="button"/>
         </form>
    </div>
    <style>
        .button{
			vertical-align:middle;
            background: #7fbf4d;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #7fbf4d), color-stop(100%, #63a62f));
            background-image: -webkit-linear-gradient(top, #7fbf4d, #63a62f);
            background-image: -moz-linear-gradient(top, #7fbf4d, #63a62f);
            background-image: -ms-linear-gradient(top, #7fbf4d, #63a62f);
            background-image: -o-linear-gradient(top, #7fbf4d, #63a62f);
            background-image: linear-gradient(top, #7fbf4d, #63a62f);
            border: 1px solid #63a62f;
            border-bottom: 1px solid #5b992b;
            border-radius: 3px;
            -webkit-box-shadow: inset 0 1px 0 0 #96ca6d;
            box-shadow: inset 0 1px 0 0 #96ca6d;
            color: #fff;
            font: bold 11px/1 "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;
            padding: 7px 0 8px 0;
            text-align: center;
            text-shadow: 0 -1px 0 #4c9021;
            width: 80px;
        }
    </style>
<script>




  $("#pay100").click(function(){
      $("#pirce").val("100");
  })
  $("#pay500").click(function(){
      $("#pirce").val("500");
  })
  $("#pay1000").click(function(){
      $("#pirce").val("1000");
  })

</script>
</body>
</html>
