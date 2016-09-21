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
$zj= false;
if($_SESSION["openid"]==$_SESSION["mpid"]||$_SESSION["mpid"]=="")
	$zj= false;
else
	$zj= true;
	
if($_SESSION["mpid"]){
	$mpid = $_SESSION["mpid"];
	$openid = $mpid;
}

$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$userArr = $com->getUserDet($openid, $do);
include 'mp_erweim.php';

$sql = "select wei_nickname,open_id,headimgurl,mingpian_xuanyan,hyerweima from sk_member where open_id='$openid'";
$openid = $_SESSION["openid"];
$_SESSION["mpid"]=$_SESSION["openid"];
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
	<title>我的名片</title>
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
	        title: '洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台',
	        desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
	        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|mingpian&connect_redirect=1#wechat_redirect',
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
.mingpbj{ width:100%;position:fixed; top:0; left:0; right:0; z-index:-1;}
.mingpbj img{ width:100%;}
.touxiang{ width:80px; height:80px; background:#fff; margin:30px auto 0 auto; z-index:5;border-radius:40px;-moz-border-radius:40px;-webkit-border-radius:40px;-o-border-radius:40px;-ms-border-radius:40px;}
.touxiang img{ width:76px; margin:2px;border-radius:38px;-moz-border-radius:38px;-webkit-border-radius:38px;-o-border-radius:38px;-ms-border-radius:38px; z-index:5;}
.name_tex{ text-align:center; font-size:18px;line-height:30px; color:#fff; z-index:5;}
.name_tex span{ display:inline-block; width:10px;}
.name_xuany{ width:100%;margin:10px auto;}
.name_xuany img{ width:100%;}
.mp_jiantu { width:30px; margin:10px auto;}
.mp_jiantu img{ width:30px; margin:auto;}
.mperweima{ width:100px; margin:10px auto 20px auto;}
.mperweima img{ width:100px; border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;-o-border-radius:4px;-ms-border-radius:4px;}
.jinrushangcheng{ width:120px; height:35px; line-height:35px; font-size:18px; background:#666; text-align:center; margin:10px auto; color:#fff; border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;-o-border-radius:4px;-ms-border-radius:4px;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#EBEBEB">
<div class="mingpbj"><img src="<?php echo $cxtPath; ?>/wei/img/mingpian.png" /></div>
<div class="touxiang"><img src="<?php echo $myOrderArr[0]['headimgurl']; ?>" /></div>
<div class="name_tex" style="margin-top:10px;">我是<?php echo $myOrderArr[0]['wei_nickname']?><span></span>我为正品代言</div>
<div class="name_tex">这里有我想要的全球免税优质商品</div>
<div class="name_xuany"><img src="<?php echo $cxtPath; ?>/wei/img/mp_hbj.png" /></div>
<!-- <div class="mp_jiantu"><img src="<?php echo $cxtPath; ?>/wei/img/mp_jiantu.png" /></div> -->
<div class="mperweima"><img src="<?php echo $cxtPath."/".$myOrderArr[0]['hyerweima']; ?>" /></div>
<div class="name_xuany" style="margin-bottom:50px;"><img src="<?php echo $cxtPath; ?>/wei/img/mp_lbj.png" /></div>
<?php if($zj){?>
<a href="../../index.php"><div class="jinrushangcheng">进入商城</div></a>
<?php }?>
</body>
</html>