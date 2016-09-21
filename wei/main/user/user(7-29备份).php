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
$nickname = $_SESSION["nickname"];
$headimgurl = $_SESSION["headimgurl"];
$openid = $_SESSION["openid"];


$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$userArr = $com->getUserDet($openid, $do);

$selyear = date('Y',time())."-".date('m',time());
$sql = "select count(id) as con,sum(pop) as pop from wei_pop where openid='$openid'";
$popArr = $do->selectsql($sql);
$pop = $popArr[0]["pop"];
if($pop == "") $pop = "0.0";
$sql = "select count(order_id) as con from sk_orders where buyer_id='$openid' and order_time>='$selyear-01' and order_time<='$selyear-31'";
$myOrderArr = $do->selectsql($sql);

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
	<title>个人中心</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.6" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
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
        title: '和众世纪跨境电商——源自于深圳自贸区的专业跨境电商平台',
        desc: '和众世纪,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
		imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/44160FA970203A1A401E727DC2066030.jpg',
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
var cartnum = "<?php echo $cartnum ?>";
$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
});
function forwordUserInf(){
	location.replace("userinf.php");
}
function forwordMyPop(){
	window.open("mypop.php");
}
function forwordIntegral(){
	window.open("integral.php");
}
function forwordYongJin(){
	window.open("yongjin.php");
}
function mingpian(){
	window.open("mingpian.php");
}
function tuiCode(){
	msgSuccess("正在生产推广二维码，稍后发送到您的账号");
	$.ajax({
		type: "POST",
		url: "qrcode.php",
		data: "openid=<?php echo $openid ?>",
		success: function(data){
		}
	});
}
</script>
</head>
<body style="margin:0px;padding:0px;background:#EBEBEB">
<div style="position:relative;width:100%;height:16em;">
	<img src="../../images/userbg.jpg" style="position:absolute;width:100%;height:100%;">
	<div style="position:absolute;top:6.7em;width:100%;height:4em;z-index:100;color:white;background:none repeat rgba(0, 0, 0, 0.3);font-size:1.5em;">
		<p style="margin-left:0.5em;margin-top:0.8em;margin-bottom:0px">尊贵的会员：<?php echo $nickname ?></p>
		<p style="margin-left:0.5em;margin-top:0em;">您拥有本店佣金：<?php echo $pop ?></p>
	</div>
</div>
<div style="position:relative;width:100%;height:5em;background:white;padding-top:10px;">
	<div class="usermid">
		<a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=0"><img style="width:2em;height:2em;" src="../../images/thumbs-up.png"><br/><font style='color:black;'>待支付</font></a>
	</div>
	<div class="usermid">
		<a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=1"><img style="width:2em;height:2em;" src="../../images/track.png"><br/><font style='color:black;'>待收货</font></a>
	</div>
	<div class="usermid">
		<a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type="><img style="width:2em;height:2em;" src="../../images/notebook.png"><br/><font style='color:black;'>全部订单</font></a>
	</div>
</div>
<div onclick="forwordUserInf()" style="position:relative;width:100%;height:3em;line-height:3em;margin-top:0.5em;background:white;font-size:1.5em;border-bottom:1px solid #E5E5E5">
	<span style="margin-left:1em;float:left;">用户信息</span>
	<img src="<?php echo $cxtPath ?>/wei/images/rightjian.png" style="margin-top:0.8em;margin-right:1em;float:right;"></img>
</div>
<div onclick="forwordMyPop()" style="position:relative;width:100%;height:3em;line-height:3em;background:white;font-size:1.5em;border-bottom:1px solid #E5E5E5">
	<span style="margin-left:1em;float:left;">我的人气</span>
	<img src="<?php echo $cxtPath ?>/wei/images/rightjian.png" style="margin-top:0.8em;margin-right:1em;float:right;"></img>
</div>
<div onclick="forwordIntegral()" style="position:relative;width:100%;height:3em;line-height:3em;background:white;font-size:1.5em;border-bottom:1px solid #E5E5E5">
	<span style="margin-left:1em;float:left;">本月订单</span>
	<img src="<?php echo $cxtPath ?>/wei/images/rightjian.png" style="margin-top:0.8em;margin-right:1em;float:right;"></img>
</div>
<div onclick="forwordYongJin()" style="position:relative;width:100%;height:3em;line-height:3em;background:white;font-size:1.5em;border-bottom:1px solid #E5E5E5">
	<span style="margin-left:1em;float:left;">佣金</span>
	<img src="<?php echo $cxtPath ?>/wei/images/rightjian.png" style="margin-top:0.8em;margin-right:1em;float:right;"></img>
</div>
<div onclick="tuiCode()" style="position:relative;width:100%;height:3em;line-height:3em;background:white;font-size:1.5em;border-bottom:1px solid #E5E5E5">
	<span style="margin-left:1em;float:left;">推广二维码</span>
	<img src="<?php echo $cxtPath ?>/wei/images/rightjian.png" style="margin-top:0.8em;margin-right:1em;float:right;"></img>
</div>
<div onclick="mingpian()" style="position:relative;width:100%;height:3em;line-height:3em;background:white;font-size:1.5em;border-bottom:1px solid #E5E5E5">
	<span style="margin-left:1em;float:left;">我的名片</span>
	<img src="<?php echo $cxtPath ?>/wei/images/rightjian.png" style="margin-top:0.8em;margin-right:1em;float:right;"></img>
</div>
<div style="height:80px;"></div>
<div class="bottom">
	<div style="width:25%;height:100%;text-align:center;float:left;">
		<a href="<?php echo $cxtPath ?>/wei/index.php" style="color:black;">
			<img src="<?php echo $cxtPath ?>/wei/images/home.png" style="width:3em;height:3em;"><br/>
			<span>首页</span>
		</a>
	</div>
	<div style="width:25%;height:100%;text-align:center;float:left;">
		<a href="<?php echo $cxtPath ?>/wei/main/category/category.php" style="color:black;">
			<img src="<?php echo $cxtPath ?>/wei/images/category.png" style="width:3em;height:3em;"><br/>
			<span>分类</span>
		</a>
	</div>
	<div style="width:25%;height:100%;text-align:center;float:left;">
		<a href="<?php echo $cxtPath ?>/wei/main/cart/cart.php" style="color:black;">
			<img src="<?php echo $cxtPath ?>/wei/images/cart.png" style="width:3em;height:3em;"><br/>
			<span>购物车</span>
		</a>
	</div>
	<div style="width:25%;height:100%;text-align:center;float:left;">
		<a href="<?php echo $cxtPath ?>/wei/main/user/user.php" style="color:black;">
			<img src="<?php echo $cxtPath ?>/wei/images/person.png" style="width:3em;height:3em;"><br/>
			<span>我</span>
		</a>
	</div>
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