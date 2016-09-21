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
        title: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.',
        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
        imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png',
        success: function (res) {
            msgSuccess("分享成功");
        },
        cancel: function (res) {
        }
    });
	wx.onMenuShareAppMessage({
	    title: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享标题
	    desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
	    link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
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
	    title: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享标题
	    desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
	    link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
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
	    title: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享标题
	    desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
	    link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
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
function forwordUserInf(){
	location.replace("userinf.php");
}
</script>
</head>
<body style="background:#EBEBEB">
<div class="header_03" style="background:white;">
	<div class="back">
		<a href="../../index.php" class="arrow"></a>
	</div>
	<div class="tit">个人中心</div>
	<div class="nav">
		<ul>
			<li class="cart"><a href="../cart/cart.php">购物车</a><span style="display:none" id="ShoppingCartNum"></span></li>
		</ul>
	</div>
</div>
<div style="position:absolute;top:5em;width:100%;height:auto;">
	<div onclick="forwordUserInf()" style="width:96%;height:8em;background:white;margin-top:2%;margin-left:2%;padding-top:10px;">
		<img src="<?php echo $headimgurl ?>" style="width:6em;height:6em;margin-left:10px;float:left;"></img>
		<div style="float:left;margin-left:10px;">
			<div style="font-size:1.5em;">您好，<?php echo $nickname ?></div>
			<div style="margin-top:5px;font-size:1.5em;color:#1895E9;">更新用户信息</div>
		</div>
		<img src="<?php echo $cxtPath ?>/wei/images/rightjian.png" style="margin-top:3em;margin-right:10px;float:right;"></img>
	</div>
	<div style="width:96%;height:70px;background:white;margin-top:2%;margin-left:2%;padding-top:10px;">
		<div class="usermid">
			<a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=0"><img style="width:40px;height:40px;" src="../../images/thumbs-up.png"><br/><font style='color:black;'>待支付</font></a>
		</div>
		<div class="usermid">
			<a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=1"><img style="width:40px;height:40px;" src="../../images/track.png"><br/><font style='color:black;'>待收货</font></a>
		</div>
		<div class="usermid">
			<a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type="><img style="width:40px;height:40px;" src="../../images/notebook.png"><br/><font style='color:black;'>全部订单</font></a>
		</div>
	</div>
	<div style="width:96%;height:80px;background:white;margin-top:2%;margin-left:2%;border:1px solid #DDDDDD">
		<div class="userxiao">
			<font style="font-size:1.5em;line-height:40px;color:#535758;">开启消息推送</font>
		</div>
		<div class="userxiao" style="border-top:1px solid #DDDDDD">
			<font style="font-size:1.5em;line-height:40px;color:#535758;">我的推送海报</font>
			<img src="../images/rightjian.png" style="margin-right:10px;float:right;margin-top:10px;"></img>
		</div>
	</div>
	<div style="width:96%;height:10em;background:white;margin-top:2%;margin-left:2%;border:1px solid #DDDDDD">
		<div class="userxiao" style="height:3em;background:#F3F3F3">
			<font style="font-size:16px;line-height:30px;color:#535758;"><strong>商城股东</strong></font>
		</div>
		<div class="usermid" style="margin-top:8px;height:auto;">
			<a href="mypop.php"><font style="font-size:30px;"><?php echo $userArr[0]["pop"] ?></font><br/>我的人气</a>
		</div>
		<div class="usermid" style="margin-top:8px;height:auto;">
			<a href="integral.php"><font style="font-size:30px;"><?php echo $myOrderArr[0]["con"]; ?></font><br/>本月订单</a>
		</div>
		<div class="usermid" style="margin-top:8px;height:auto;">
			<a href="yongjin.php"><font style="font-size:30px;"><?php echo $pop ?></font><br/>佣金</a>
		</div>
	</div>
	<div style="width:96%;height:10em;background:white;margin-top:2%;margin-left:2%;border:1px solid #DDDDDD">
		<div class="userxiao" style="height:3em;background:#F3F3F3">
			<font style="font-size:16px;line-height:30px;color:#535758;"><strong>优惠券</strong></font>
		</div>
		<div class="usermid" style="margin-top:8px;">
			<font style="font-size:30px;">0</font><br/>未使用
		</div>
		<div class="usermid" style="margin-top:8px;">
			<font style="font-size:30px;">0</font><br/>已使用
		</div>
	</div>
</div>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>