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


$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$userArr = $com->getUserDet($openid, $do);

if($userArr[0]['jointag']==2)
    echo "<script>window.location.href='members_dp.php';</script>";
elseif($userArr[0]['jointag']==0)
	echo "<script>window.location.href='../category/fenlei.php';</script>";
$sql="select sum(if(isnull(open_id),1,0)) count from sk_message a left join sk_message_read b on a.id=b.mid where rec='$openid' or recpid=(select if(pid!='',pid,0) from sk_member where open_id='$openid')";
$pcount=$do->getone($sql);

$pop = $userArr[0]["shouyi"];
if($pop == "") $pop = "0.0";
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
	<title>会员中心</title>
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
var cartnum = "<?php echo $cartnum ?>";
$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
});

function soucang(){
	window.open("./paihang.php");
}
function faqitixian(){
	window.open("./yongjin.php");
}
function wodeyouhui(){
	window.open("./mengyou.php");
}

function guanyuwomen(){
	window.open("./xiaoxi.php");
}
function dianpudingdan(){
	window.open("../order/myorder.php?dianpu_huiyuan=1&type=0");
}
</script>
<style>
	html,body{background:#edecec;}
.wod_dingdan{ width:100%;height:35px;display:none;}
.wod_dingdan li{ width:25%;height:35px;text-align:center;line-height:35px;float:left;}
.wod_youhui{ width:100%;height:35px;display:none;}
.wod_youhui li{ width:50%;height:35px;text-indent:15px;line-height:35px;float:left; font-size:12px; color:#555;}
.wod_dingdan a:link{ color:#555; font-size:12px;}
.wod_dingdan a:hover{ color:#555; font-size:12px;}
.wod_dingdan a:visited{ color:#555; font-size:12px;}


.fanhuisouye{ font-size:14px; height:30px; line-height:30px; text-indent:10px; border:1px solid #fff;}
.fanhuisouye a:link{ color:#666;}
.fanhuisouye a:hover{ color:#666;}
.fanhuisouye a:visited{ color:#666;}
	.tubiao{ display:inline-block; width:1.2em;margin:0 0.5em;}
	.tubiao img{float:left; width:25px; display:inline-block;}

.hy_waikuang{ position:relative;width:100%;height:2.8em;line-height:2.8em;background:#fff;font-size:1.8em;border-bottom:1px solid #E5E5E5;}

.souytx{ font-size:14px; float:right; padding-right:8px; color:#888;}


	.jiru a:link{ color:#fe7182; font-size:12px;}
	.jiru a:hover{ color:#fe7182; font-size:12px;}
	.jiru a:visited{ color:#fe7182; font-size:12px;}

.toppu{padding:2em 0;background:#fff;}
.top_img{ float:left;width:8em;height:8em;margin:0 2em 0 2.5em;border:1px solid #f6b616;padding:0.2em;position:relative;}	
.top_img .vtu{position:absolute;width:35%;bottom:0;right:0;}	
.top_name{float:left;}	
.top_name .name{  font-size:1.5em;line-height:1.6em;}	
.top_name .jianjie{  font-size:1.5em;line-height:1.2em;color:#888;width:8em;height:2.2em;overflow:hidden;}	
.top_name .huiyuan{ font-size:1.5em; line-height:2em;}	
.top_erweima{float:right;width:3em;margin:1.5em 2.5em 0 0;}
.hyerweima{ position:fixed;top:0;left:0;right:0;bottom:0;display:none;}
.hyerweima img{ display:block;width:50%;margin:30% auto;}
.hyerweima_yin{background:#000;
filter:alpha(opacity:50);
opacity:0.5;
-moz-opacity:0.5;}
</style>
</head>
<body style="margin:0px;padding:0px;">
<?php if($userArr[0]['jointag']==1){?>
<div class="toppu">
    <div class="top_img"><img width="100%" height="100%" src="<?php echo $_SESSION["headimgurl"];?>" style=" max-width:100%;"/><img class="vtu" src="../../img/hyvip.png" style=" max-width:100%;"/></div>
    <div class="top_name">
        <div class="name"><?php echo $nickname ?></div>
        <div class="jianjie">洋仆淘,享购世界</div>
        <div class="huiyuan">洋仆淘会员</div>
    </div>
    <div class="top_erweima">
        <img class="vtu" id="vtu" src="../../img/erweima.png" style=" max-width:100%;"/>
        <div>二维码</div>
    </div>
    <div style="clear:both;float:none;height:0;"></div>
</div>
<div class="hy_waikuang" style="margin-top:0.5em;">
	<img class="tubiao" src="<?php echo $cxtPath ?>/wei/img/hy_shouy.png" ></img><span class="hy_nameq">我的洋仆淘收益：<?php echo $pop ?></span>
</div>
<div onclick="faqitixian()" class="hy_waikuang">
	<img class="tubiao" src="<?php echo $cxtPath ?>/wei/img/hy_shouy.png" ></img><span class="hy_nameq">发起提现
</div>
<div onclick="soucang()"  class="hy_waikuang" style="margin-top:0.5em;">
	<img class="tubiao" src="<?php echo $cxtPath ?>/wei/img/hy_paih.png" ></img><span class="hy_nameq">洋仆淘排行榜</span>
</div>
<div onclick="wodeyouhui()"  class="hy_waikuang">
	<img class="tubiao" src="<?php echo $cxtPath ?>/wei/img/hy_mengy.png" ></img><span class="hy_nameq">我的盟友</span>
</div>
<!-- <div onclick="wodemingpian()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_mingp.png" ></img></span><span class="hy_nameq">我的名片</span>
</div> -->
<div onclick="guanyuwomen()"  class="hy_waikuang" style="margin-top:0.5em;">
	<img class="tubiao" src="<?php echo $cxtPath ?>/wei/img/hy_xiaox.png" ></img><span class="hy_nameq">消息中心<?php if($pcount['count']>0){echo "（".$pcount['count']."）";}?></span>
</div>
<div class="hyerweima hyerweima_yin" id="hyerweima">
</div>
<div class="hyerweima">
    <img src="<?php echo $cxtPath ?>/<?php echo $userArr[0]["hyerweima"];?>" style=" max-width:100%;"/>
</div>
<script>
var erweima="<?php echo $userArr[0]["hyerweima"];?>";
$(function(){
	$('#vtu').click(function(){
	    if(erweima==""){
	        $.ajax({
		        type:"POST",
		        url:"members_erweima.php",
		        data:{},
		        success:function(data){
		        	erweima = data;
		        	$('#hyerweima img').attr('src',"<?php echo $cxtPath ?>/"+data);
			    }
		    })
		}
    	$('.hyerweima').show();
	})
	$('.hyerweima').click(function(){
	    $('.hyerweima').hide();
	});
})
if($(document).width()>800) {
	$(".hyerweima").find("img").css("margin","10% auto").css("width","30%");
}
</script>
<div style="clear:both;height:90px;"></div>
<?php }else{?>
<div style="background:#fff; height:100%; width:100%;left:0; top:0;">
	<div style="width:60%;margin:0 auto; padding-top:40px;"><img src="<?php echo $cxtPath ?>/wei/img/baibai.jpg" style="width:100%;margin:0 auto;"></img></div>
	<div style="font-size:14px; line-height:35px; color:#888; text-align:center;background:#fff;">您还不是我们的会员噢</div>
	<div class="jiru" style="font-size:14px; line-height:35px; color:#888; text-align:center;background:#fff;">快来<a href="../category/fenlei.php">加入我们</a>吧</div>
	<div style="clear:both;height:90px;background:#fff;"></div>
</div>
<?php }?>
<?php include '../gony/nav.php';?>
<?php include '../gony/guanzhu.php';?>
</body>
</html>