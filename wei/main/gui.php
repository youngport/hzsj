<?php
include '../base/condbwei.php';
include '../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

$do = new condbwei();
$com = new commonFun();

$openid = $_SESSION["openid"];
$cartnum = $_SESSION["cartnum"];
$subscribe = $_SESSION["subscribe"];

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"]);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>活动规则</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/shopping-cart.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
<script type="text/javascript">
wx.config({
    appId: '<?php echo $shareCheckArr["appid"];?>',
    timestamp: '<?php echo $shareCheckArr["timestamp"];?>',
    nonceStr: '<?php echo $shareCheckArr["noncestr"];?>',
    signature: '<?php echo $shareCheckArr["signature"];?>',
    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
});
wx.ready(function(){
	wx.onMenuShareTimeline({
        title: '洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台',
        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fhzsj.11zone.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
        imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png',
        success: function (res) {
            msgSuccess("分享成功");
        },
        cancel: function (res) {
        }
    });
	wx.onMenuShareAppMessage({
	    title: '洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台', // 分享标题
	    desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
	    link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fhzsj.11zone.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
        imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png',
        type: '', // 分享类型,music、video或link，不填默认为link
	    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	    success: function () { 
	    	msgSuccess("成功发送给好友");
	    },
	    cancel: function () { 
	    }
	});
	wx.onMenuShareQQ({
	    title: '洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台', // 分享标题
	    desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
	    link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fhzsj.11zone.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
        imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png',
        type: '', // 分享类型,music、video或link，不填默认为link
	    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	    success: function () { 
	    	msgSuccess("成功发送给好友");
	    },
	    cancel: function () { 
	    }
	});
	wx.onMenuShareWeibo({
	    title: '洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台', // 分享标题
	    desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
	    link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fhzsj.11zone.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
        imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png',
        type: '', // 分享类型,music、video或link，不填默认为link
	    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	    success: function () { 
	    	msgSuccess("成功发送给好友");
	    },
	    cancel: function () { 
	    }
	});
	wx.hideMenuItems({
		menuList: [
			'menuItem:readMode',
			'menuItem:openWithSafari',
			'menuItem:openWithQQBrowser',
			'menuItem:copyUrl'
		]
	});
});
</script>
</head>
<body style="background:#EBEBEB">
<div class="header_03">
	<div class="back"><a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fhzsj.11zone.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=&connect_redirect=1#wechat_redirect" class="arrow">首页</a></div>
	<div style="" class="tit">
		<h3>活动规则</h3>
	</div>
</div>
<div style="font-size:2em;padding:10px;">
<p>举例说明：一件商品100块钱的利润，分成60元公司成本盈利和对外分配的40元，同时又拿40的20% 8块给星级股东分配，40的80%给推荐人9层分配。</p>
<p>40*0.8=32元分成100%给商城股东分配9层:</p>
<p>第一层拿 32*50%</p>
<p>第二层拿 32*20%</p>
<p>第三层拿 32*30%</p>
<p></p>
<p>星级股东，把星级股东红利部分8元分配如下:</p>
<p>一星级股东，消费990元拿8*10％拿0.4元</p>
<p>二星级股东，累计消费6990元，直推20个股东，你拿8*15％</p>
<p>三星级股东，累计消费7990元，直推50个股东，你拿8*20％</p>
<p>四星级股东，累计消费8990元，直推80个股东，你拿8*25％</p>
<p>五星级股东，累计消费9990元，直推100个股东，你拿8*30％</p>
</div>
<div class="dingbu" id="dingbu">Top</div>
<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/backtop.css" />
<script type="text/javascript">
$(function(){
	$('#dingbu').click(function(){
		$("html, body").animate({
			scrollTop: 0
		}, 120);
	});
	$(window).bind("scroll",function() {
        var d = $(document).scrollTop(),
        e = $(window).height();
        0 < d ? $('#dingbu').css("bottom", "50px") : $('#dingbu').css("bottom", "-190px");
	});
});
</script>
</body>
</html>