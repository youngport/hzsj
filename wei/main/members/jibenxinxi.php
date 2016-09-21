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

$sql = "select wei_nickname,open_id,headimgurl,mingpian_xuanyan,dianhua,email,im_qq,phone_tel,phone_mob,im_weixin from sk_member where open_id='$openid'";
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
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>基本信息</title>
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
$(function(){
	$('#xiugai').click(function(){
		$.ajax({
			type: "POST",
			url: "jibenxinxiget.php",
			data: {xuanyan:$('#text_f').val(),email:$('#email').val(),im_qq:$('#im_qq').val(),phone_tel:$('#phone_tel').val(),phone_mob:$('#phone_mob').val(),im_weixin:$('#im_weixin').val()},
			success: function(data){
				if(data==1){
					alert('已修改！');
				}
				else{
					alert('修改失败！');
				}
			}
		});
	})
})
</script>
<style>
#text_f{ border:none;background:#fff;font-size:14px; line-height:20px;height:90px; width:100%;}
#shouji{ border:none;background:#fff; font-size:14px; width:200px;}
#xiugai{ font-size:14px; text-align:center; height:35px; line-height:35px; width:98%; margin:10px auto 20px; background:#fe7182;color:#fff;}
.text{ width:96%;margin:2px auto;height:25px;color:#555;border:none;borser-bottom:1px solid #888;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#fff; font-size:18px;">

<!--<div><div>头像</div><div><img width="120" src="<?php echo $myOrderArr[0]['headimgurl']; ?>" /></div></div>
	<div><div>会员名</div><div><?php echo $myOrderArr[0]['wei_nickname']; ?></div></div>
	<div><div>宣传语</div><textarea id="text_f" placeholder="请输入内容" ><?php echo $myOrderArr[0]['mingpian_xuanyan']; ?></textarea></div>
	<div><div>联系方式</div>
	<div><div>手机号码</div><input placeholder="请输入手机号或邮箱（选填）" class="text" name="shouji" id="shouji" value="<?php echo $myOrderArr[0]['dianhua']; ?>" type="search"></div>
	<div id="xiugai">修改</div>-->
	
	<div class="bigbox clearfix">
<div class="touimg clearfix">
<h4 class="h4 left" style="font-weight:400;font-size:22px;color:#333;">头像</h4>
<div class="imgpic right" style="overflow: hidden;"><img width="100%" src="<?php echo $myOrderArr[0]['headimgurl']; ?>" /></div>
</div>
<div class="touimg imghj clearfix">
<h4 class="h4 left" style="margin-top:10px;margin-left:5px;font-weight:400;font-size:22px;color:#333;">会员名</h4>
<div class="textpic right" style="font-weight:400;font-size:22px;color:#333;"><?php echo $myOrderArr[0]['wei_nickname']; ?></div>
</div>
<div class="touimg clearfix">
<h4 class="h4" style="width:100%;display:block;font-weight:400;font-size:22px;color:#333;">宣传语</h4>
<div class="textaresa"><textarea id="text_f" placeholder="请输入内容" ><?php echo $myOrderArr[0]['mingpian_xuanyan']; ?></textarea></div>
</div>
<div class="touimg  clearfix" style="border-top:solid 1px #f6f6f5;border-bottom:none;">
<h4 class="h4" style="width:100%;display:block;font-weight:400;font-size:18px;color:#333;">联系方式</h4>
<div class="textaresa" style="margin-left:25px; font-size:16px;">微信号码：<input placeholder="请输入微信号" class="text" name="shouji" id="im_weixin" value="<?php echo $myOrderArr[0]['im_weixin']; ?>" type="search"></div>
<div class="textaresa" style="margin-left:25px; font-size:16px;">联系电话：<input placeholder="请输入联系电话" class="text" name="shouji" id="phone_mob" value="<?php echo $myOrderArr[0]['phone_mob']; ?>" type="search"></div>
<div class="textaresa" style="margin-left:25px; font-size:16px;">联系手机：<input placeholder="请输入联系手机" class="text" name="shouji" id="phone_tel" value="<?php echo $myOrderArr[0]['phone_tel']; ?>" type="search"></div>
<div class="textaresa" style="margin-left:25px; font-size:16px;">联系QQ：<input placeholder="请输入联系QQ" class="text" name="shouji" id="im_qq" value="<?php echo $myOrderArr[0]['im_qq']; ?>" type="search"></div>
<div class="textaresa" style="margin-left:25px; font-size:16px;">电子邮箱：<input placeholder="请输入电子邮箱" class="text" name="shouji" id="email" value="<?php echo $myOrderArr[0]['email']; ?>" type="search"></div>
<div id="xiugai">修改</div>
</div>
</div>
</body>
</html>