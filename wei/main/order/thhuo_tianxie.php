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
$type = intval($_GET["type"]);
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];

$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl =  'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

$orderid = intval($_GET['orderid']);
$bian =  addslashes($_GET['bianhao']);
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
	<title>我的订单</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v2.0" />
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

</script>
</head>
<style>
.tim{font-size:14px;color:#555;line-height:25px;margin-top:5px;}
.shuru{width:100%; height:30px;border:1px solid #f4f4f4;color:#555;}
.tijiao{background:#fe7182;width:120px;font-size:14px;height:30px;line-height:30px;text-align:center;color:#fff;margin:20px auto;}
</style>
<body style="background:#fff;padding:0;margin:0;">
<div style="width:96%;margin:15px auto;">
    <div class="tim">订单编号：<?php echo $bian; ?></div>
    <div class="tim">快递名称：<input name="th_xingming" class="shuru" type="text" id="th_xingming" placeholder="请输入快递名称" ></div>
    <div class="tim">快递单号：<input name="th_shouji" class="shuru" type="text" id="th_shouji" placeholder="请输入快递单号" ></div>
	<div id="tijiao" class="tijiao">提交（已发货）</div>
</div>
<script>
$('#tijiao').click(function(){
	if($('#th_xingming').val()=="")
		alert('快递名称不能为空');
	else if($('#th_shouji').val()=="")
		alert('快递单号不能为空');
	else {
    	$.ajax({
    		type: "POST",
    		url: "thhuo_tianxieget.php",
    		data: "order_id=<?php echo $orderid ?>&kuaidmc="+$('#th_xingming').val()+"&kuaiddh="+$('#th_shouji').val(),
    		success: function(data){
        		if(data==1){
        			window.open("<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=3");
            	}
    		}
    	});
	}
})
</script>
</body>
</html>