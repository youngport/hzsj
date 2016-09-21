<?php 
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

include '../../base/condbwei.php';
include '../../base/commonFun.php';
$do = new condbwei();
$com = new commonFun();
session_start();
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}
$openid = $_SESSION["openid"];

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
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<title>积分规则</title>
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
        		imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/44160FA970203A1A401E727DC20660301.jpg',
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
<body style="margin:0px;padding:0px;">
<img style="width:100%;" src="<?php echo $cxtPath ?>/wei/img/jifenguize.jpg" />
<div></div>
</body>
</html>