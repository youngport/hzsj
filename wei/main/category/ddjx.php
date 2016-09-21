<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
$cartnum = $_SESSION["cartnum"];
if(!isset($_SESSION["access_token"])){
	//header("Location:".$cxtPath."/wei/login.php");
	//return;
}
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];

$do = new condbwei();
$com = new commonFun();

$id = intval($_GET["id"]);
$sql = "select goodtype from sk_goods where id='$id'";
$resultArr = $do->selectsql($sql);
$id_goodstype = $resultArr[0]["goodtype"] ;

$goodArr = $com->getGoodDet($id, $do);
$guige = $goodArr[0]["guige"];
$guigeTotArr = explode(';',$guige);
$guigeArr = explode('|',$guigeTotArr[0]);
$colorArr = array();
if(count($guigeTotArr)==2){
	$colorArr = explode('|',$guigeTotArr[1]);
}
$com->getGoodAdd($id, $do,$goodArr[0]["hits"]);
$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl =  'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
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
	<title>店家加盟</title>
	<link rel='stylesheet' type='text/css' href='<?php echo $cxtPath ?>/wei/css/product-intro.css' />
    <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/head.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/foot.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/flexslider.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery.flexslider-min.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
<style>
.guisp{
	border:2px solid #D4D5D6;background:white;padding:0.5em;margin-right:1em;
}
.selguisp{
	border:2px solid #FF4400;
}
.colorsp{
	border:2px solid #D4D5D6;background:white;padding:0.5em;margin-right:1em;
}
.selcolorsp{
	border:2px solid #FF4400;
}
.jiamenghy{ width:100%; position:relative;}
.jiamenghy img{ width:100%;}
html{background:#a6daf9;}
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
        title: '洋仆淘跨境商城——<?php echo $goodArr[0]["good_name"] ?>',
        desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
		imgUrl: '<?php echo $imgurl.$goodArr[0]["img"] ?>',
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
		],success: function (res) {
			//alert('已隐藏“阅读模式”，“分享到朋友圈”，“复制链接”等按钮');
		},fail: function (res) {
			//alert(JSON.stringify(res));
		}
	});
});
$(function(){
	//$('#tui').attr("href","fenlei.php?ddjm=1");
	$('#tui').attr("href","../goods/goods_dpjm.php?id=337");
})
</script>
</head>
<body style="margin:0px;padding:0;background:#a6daf9;">
	<div class="jiamenghy">
		<?php echo $goodArr[0]["desc"] ?>
	</div>
<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>