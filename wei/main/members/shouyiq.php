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
$openid = $_SESSION["openid"];
$taopenid = addslashes($_GET['shouyi']);

$shuzhi = $_GET['shuzhi']+0;
if($shuzhi=="")
    $shuzhi = '0.00'
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
	<title>收益详情</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.6" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
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
var js_limit_int = 0;


function cax(jsint){
	$.ajax({
		type: "POST",
		url: "shouyiq_get.php",
		data: {shouyi_id:'<?php echo $taopenid;?>',limit_int:jsint},
		success: function(data){
			var data = eval("("+data+")");
			var appStr = "";
			if(data.length>0){
				var ding = data.length;//循环多少次
				if(data.length>20){
					var ding = 20;
					js_limit_int += 20;
					$('#jiazai').css({display:"block"});
					$('#zanwugengduo').css({display:"none"});
				    $('#jiazaizhong').css({display:"none"});
				}else{
					$('#zanwugengduo').css({display:"block"});
				    $('#jiazai').css({display:"none"});
				    $('#jiazaizhong').css({display:"none"});
				}
				appStr = "";
				for(var i=0;i < ding;i++){
					appStr += "<li><div class=\"shouyi_xq\">";
					appStr += "<span style=\"font-size:1.5em;line-height:1.8em;height:1.8em;display:block;\">"+data[i].wei_nickname+" 购买了商品</span>";
					appStr += "<span style=\"font-size:1em;display:block;color:#9c9c9c;\">"+data[i].poptime+"</span>";
					appStr += "</div>";
					appStr += "<div class=\"shouyi_yuan\">"+data[i].pop+"</div>";
					appStr += "<div class=\"clear\"></div>";
					appStr += "</li>";
				}
				$("#ul_xq").append(appStr);
			}else{
				$('#zanwusouyi').css({display:"block"});
			}
		}
	});
}
cax(js_limit_int);
$(function(){
	$('#jiazai').click(function(){
		cax(js_limit_int);
	    $('#jiazaizhong').css({display:"block"});
		$('#zanwugengduo').css({display:"none"});
	    $('#jiazai').css({display:"none"});
	})
})
</script>
<style>
html{background:#fff;}
body{ margin:0;padding:0;background:#fff;}
.top_daqu{width:100%;background:#fe7182;text-align:center;color:#fff; margin:0;}
.top_daqu .top_congta{ font-size:1.4em;padding-top:2em;}
.top_daqu .top_shouyi{ font-size:2.2em;}
.top_daqu .top_shuju{ font-size:6em;padding:1em 0;}
.top_daqu .top_shuju span{font-size:0.3em;}
.shouyi{ width:94%; font-size:1.5em;margin:0.5em auto;line-height:2em;height:2em;}
.ul_xq{ width:94%; margin:0 auto;padding:0;}
.ul_xq li{ border-bottom:1px solid #ebebeb;list-style:none;margin:0 auto;padding:0.7em 0;}
.ul_xq li .shouyi_xq{float:left;margin-left:0.3em;}
.ul_xq li .shouyi_yuan{font-size:3em;float:right;line-height:1.5em;color:#fe7182;margin-right:0.3em;}
.ul_xq li .clear{clear:both;height:0;float:none;}
.ul_xq li .zanwu{ font-size:2em;text-align:center;padding:0.5em 0;}
.ul_xq li .zanwuimg{ width:50%;display:block;margin:0 auto;}
.zanwugengduo{ font-size:1.5em;text-align:center;line-height:2.2em;color:#7a7a7a;display:none;margin-bottom:2em;}
</style>
</head>
<body>
<div class="top_daqu">
    <div class="top_congta">您从Ta那获取</div>
    <div class="top_shouyi">收益</div>
    <div class="top_shuju"><?php echo $shuzhi;?><span>（元）</span></div>
</div>
<div class="shouyi">
    <span style="float:left;margin-left:0.3em;">收益明细</span>
    <span style="float:right;margin-right:0.3em;">（元）</span>
</div>
<ul class="ul_xq" id="ul_xq">
    <!-- <li>
        <div class="shouyi_xq">
            <span style="font-size:1.5em;line-height:1.8em;height:1.8em;display:block;">收益明细</span>
            <span style="font-size:1em;display:block;color:#9c9c9c;">2015-10-22 09:10:03</span>
        </div>
        <div class="shouyi_yuan">58.00</div>
        <div class="clear"></div>
    </li> -->
    <li id="zanwusouyi" style="display:none;">
        <div class="zanwu">暂未获取收益</div>
        <img class="zanwuimg" src="../../img/zanwushouyi.jpg" >
    </li>
</ul>
<div class="zanwugengduo" id="zanwugengduo">暂无更多收益，继续努力吧</div>
<div class="zanwugengduo" id="jiazai">加载更多记录</div>
<div class="zanwugengduo" id="jiazaizhong">努力加载中...</div>
</body>
</html>