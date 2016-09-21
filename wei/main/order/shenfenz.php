<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
//----判断是否购买会员或
if($_SESSION["tiao_zhongxin"]==1){//跳转到个人中心
    $_SESSION["tiao_zhongxin"]=0;
    echo '<script>window.location.replace("../../lefenxiang/lefenxiang.php")</script>';
}

$do = new condbwei();
$com = new commonFun();
$openid = $_SESSION["openid"];

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

$sql = "select m.wei_shousfz,o.buyer_name from sk_member m join sk_orders o on o.buyer_id = m.open_id where m.open_id = '$openid' and o.order_id='".intval($_GET['order'])."'";
$sfzArr = $do->selectsql($sql);
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
	<title>结算</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
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
<style>
html{ background:#f0efef;}
.top_tishi{ width:80%;margin:1em auto;line-height:1.3em;color:#7b7b7b;font-size:1em;}
.zhengjian{ padding:1em 0;}
.zhengjian span{ font-size:1.2em;display:inline-block;width:35%;text-indent:1.2em;}
.zhengjian input{ font-size:1em;width:55%;border:none;}
.wancheng{ width:80%; margin:1em auto; font-size:1.2em; color:#fff; background:#ec6e7e; text-align:center; padding:0.5em 0;
border-radius:4px;
-moz-border-radius:4px;
-webkit-border-radius:4px;
-o-border-radius:4px;
-ms-border-radius:4px;}
</style>
</head>
<body>
<div class="top_tishi">由于国家政策要求，购买海外商品必须提供身份证信息请填写本人（收件人）身份证号码</div>
<div style="background:#fff;">
    <div class="zhengjian"><span>收件人</span><input type="search" id="name" placeholder="请填写姓名" value="<?php echo $sfzArr[0]['buyer_name'];?>" /></div>
    <div style="width:90%;border-top:1px solid #f0efef;margin:0 auto;"></div>
    <div class="zhengjian"><span>身份证号</span><input type="search" id="shenfenz" placeholder="请填写身份证号码" value="<?php echo $sfzArr[0]['wei_shousfz'];?>" /></div>
</div>
<div class="wancheng" id="wan">完成</div>
<script>
$("#shenfenz").focus();
$('#wan').click(function(){
	if($('#shenfenz').val()==""||$('#name').val()=="")
		alert('身份证或名字不得为空');
	else{
	    $.ajax({
	    	type: "POST",
	    	url: "shenfenzget.php",
	    	data: "shenfenz="+$('#shenfenz').val()+"&name="+$('#name').val()+"&orderid=<?php echo $_GET['order'];?>",
	    	success: function(data){
	    		data = eval("("+data+")");
	    		if(data.success == "1"){
	    			alert("身份证填写成功");
	    			location.replace("./myorder.php?type=0");
	    		}else{
	    			alert("身份证填写失败，请稍后重试");
	    		}
	    	}
	    });
	}
})

</script>
</body>
</html>