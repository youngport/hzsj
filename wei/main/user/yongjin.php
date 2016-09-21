<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$cartnum = 0;
if(isset($_SESSION["cartnum"])){
	$cartnum = $_SESSION["cartnum"];
}
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];

$do = new condbwei();
$com = new commonFun();
$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

$sql = "select sum(p1.pop) as pop,p2.popdui,p3.poping,p4.popwait from wei_pop p1,
		(select sum(pop) as popdui from wei_pop where openid='$openid' and duitag='1') p2,
		(select sum(pop) as poping from wei_pop where openid='$openid' and duitag='2') p3,
		(select sum(pop) as popwait from wei_pop where openid='$openid' and duitag='3') p4 
		where p1.openid='$openid'";
$popArr = $do->selectsql($sql);
$pop = $popArr[0]["pop"];
if($pop == "") $pop = "0.0";
$popdui = $popArr[0]["popdui"];
if($popdui == "") $popdui = "0.0";
$poping = $popArr[0]["poping"];
if($poping == "") $poping = "0.0";
$popwait = $popArr[0]["popwait"];
if($popwait == "") $popwait = "0.0";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>佣金</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/area.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
<style>
.bottom{  
	position:fixed;
	height:4.5em;
	right:0px;
	_position:absolute;
	/*_right:17px;*/
	bottom:0px;
	background:white;
	color:#fff;
	border-top:1px solid #fff;
	display:block;
	width:100%;
	padding-top:0.7em;
}
.appdiv{
	width:100%;height:100%;background:red;z-index:1000;
}
</style>
<script type="text/javascript">
wx.config({
    appId: '<?php echo $shareCheckArr["appid"];?>',
    timestamp: '<?php echo $shareCheckArr["timestamp"];?>',
    nonceStr: '<?php echo $shareCheckArr["noncestr"];?>',
    signature: '<?php echo $shareCheckArr["signature"];?>',
    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
});
wx.ready(function(){
	var shareData = {
        title: '洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台',
        desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
		imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png',
        success: function (res) {
            msgSuccess("分享成功");
        },
        cancel: function (res) {
        }
    };
	wx.onMenuShareTimeline(shareData);
	wx.onMenuShareAppMessage(shareData);
	wx.onMenuShareQQ(shareData);
	wx.onMenuShareWeibo(shareData);
	wx.hideMenuItems({
		menuList: [
			'menuItem:readMode',
			'menuItem:openWithSafari',
			'menuItem:openWithQQBrowser',
			'menuItem:copyUrl'
		]
	});
});
$(window).bind('resize load', function(){
	$("body").css("zoom", $(window).width() / 480);
	$("body").css("display" , "block");
});
var cartnum = "<?php echo $cartnum ?>";
$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
});
function duihuan(){
	window.open("dui.php");
}
function jilu(){
	window.open("duiorder.php");
}
</script>
</head>
<body style="background:#EBEBEB;">
<div class="header_03" style="background:white">
	<div class="back">
		<a href="../../index.php" class="arrow"></a>
	</div>
	<div class="tit">佣金</div>
	<div class="nav">
		<ul>
			<li class="cart"><a href="../cart/cart.php">购物车</a><span style="display:none" id="ShoppingCartNum"></span></li>
		</ul>
	</div>
</div>
<div style="height:1em;width:100%;"></div>
<div style="margin-top:0.5em;margin-left:auto;margin-right:auto;width:100%;height:auto;background:white;padding:10px;font-size:1.8em;">
	总佣金：<font id="pop"><?php echo $pop ?></font><br/>
	(已兑换佣金：<font id="popdui" style="color:green;"><?php echo $popdui ?></font>，兑换中佣金：<font id="poping" style="color:red;"><?php echo $poping ?></font>，待确认佣金：<font id="popwait" style="color:red;"><?php echo $popwait ?></font>)<br/>
	说明：兑换规则以商家通知为准，请在个人中心填写正确的支付宝或银行账号，以便商家操作。	
</div>
<div class="bottom">  
	<span onclick="duihuan()" style="font-size:1.5em;background:red;padding:0.5em;float:left;margin-left:1em;border-radius:15px;">如何兑换</span>
	<span onclick="jilu()" style="font-size:1.5em;background:#07B12C;padding:0.5em;float:right;margin-right:1em;border-radius:15px;">兑换记录</span>
</div>  
<?php
if($subscribe == "0"){
?>
<div style="position:fixed;_position:absolute;top:0px;height:50px;width:100%;background:red;z-index:100;background-color:#000000; background-color:rgba(0,0,0,0.5);">
	<img style="width:50px;height:50px;" src="<?php echo $_SESSION["tuiheadimgurl"]; ?>" />
	<span style="font-size:14px;color:white;"><?php echo $_SESSION["tuinickname"]; ?>推荐</span>
	<img onclick="closeGuan(this)" style="margin-top:10px;width:30px;height:30px;float:right;" src="<?php echo $cxtPath ?>/wei/images/multiply.png" />
	<a href="http://mp.weixin.qq.com/s?__biz=MzA4OTcyOTM5NQ==&mid=209415610&idx=1&sn=e073ffaa48bacda621dadecf9966123f#rd"><div style="margin-top:10px;margin-right:10px;width:90px;height:30px;float:right;background:#02b526;border-radius:5px;text-align:center;line-height:30px;font-size:14px;"><font style="color:white;">关注我们</font></div></a>
</div>
<?php
}
?>
<?php include '../gony/returntop.php';?>
</body>
</html>