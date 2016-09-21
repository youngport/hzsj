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
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<!-- <meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> -->
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>我的人气</title>
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
	$("body").css("zoom", $(window).width() / 500);
	$("body").css("display" , "block");
});
var cartnum = "<?php echo $cartnum ?>";
$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
	$("#type1").append("<div id='titleact' style='background:#FFC43A;width:100%;height:3px;margin-top:3px;'></div>");
	loadData("1");//已关注
	
	$("#type1").click(function(){
		loadData("1");
		removeActive("1");
	});
	$("#type2").click(function(){
		loadData("2");
		removeActive("2");
	});
});
function loadData(type){
	$.ajax({
		type: "POST",
		url: "mypopSearch.php",
		data: "type="+type,
		success: function(data){
			$(".popdiv").each(function(){
				$(this).remove();
			});
			data = eval("("+data+")");
			var total = data.length;
			if(type=="1"){
				$("#popyi").text(total);
			}else{
				$("#popwei").text(total);
			}
			for(var i=0;i<data.length;i++){
				var open_id = data[i].open_id;
				var wei_nickname = data[i].wei_nickname;
				var headimgurl = data[i].headimgurl;
				if(type == "2"){
					wei_nickname = "未知";
					headimgurl = "../../images/vcard.png";
				}
				var appStr = "<div class='popdiv' style='margin-top:2%;width:100%;height:7em;background:white;'>";
				appStr += "<img style='width:50px;height:50px;margin-top:10px;margin-left:10px;float:left;' src='"+headimgurl+"' />";
				appStr += "<div style='width:auto;height:7em;margin-top:10px;margin-left:10px;float:left;'>";
				appStr += "<p style='font-size:14px;'>用户（昵称）："+wei_nickname+"</p>";
				appStr += "<p style='font-size:14px;'>加入时间："+data[i].join_time+"</p>";
				appStr += "<p style='font-size:14px;'>人气指数："+data[i].pop+"</p>";
				appStr += "</div></div>";
				$("body").append(appStr);
			}
		}
	});
}
function removeActive(tagid){
	$("#titleact").remove();
	$("#type"+tagid).append("<div id='titleact' style='background:#FFC43A;width:100%;height:3px;margin-top:3px;'></div>");
}
</script>
</head>
<body style="background:#EBEBEB;">
<div class="header_03" style="background:white">
	<div class="back">
		<a href="../../index.php" class="arrow"></a>
	</div>
	<div class="tit">我的人气</div>
	<div class="nav">
		<ul>
			<li class="cart"><a href="../cart/cart.php">购物车</a><span style="display:none" id="ShoppingCartNum"></span></li>
		</ul>
	</div>
</div>
<div style="width:100%;height:40px;background:#F7762E;">
	<div style="margin-left:auto;margin-right:auto;width:220px;height:100%;">
		<div style="float:left;" id="type1">
			<div class="ordertit" style="width:100px;">已关注(<font id="popyi">0</font>)</div>
		</div>
		<div style="float:left;" id="type2">
			<div class="ordertit" style="width:100px;border:0px">未关注(<font id="popwei">0</font>)</div>
		</div>
	</div>
</div>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>