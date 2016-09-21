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

$sql="select sum(if(isnull(open_id),1,0)) count from sk_message a left join sk_message_read b on a.id=b.mid where rec='$openid' or recpid=(select if(pid!='',pid,0) from sk_member where open_id='$openid')";
$pcount=$do->getone($sql);

$sql = "select jointag,shouyi from sk_member where open_id='$openid'";
$jointagArr = $do->selectsql($sql);

$pop = $jointagArr[0]["shouyi"];
/*if($popArr[0]['jointag']==0){//----------------------------活动流程------ 
	header("Location:".$cxtPath."/wei/main/liucheng.php");
}*/

$selyear = date('Y',time())."-".date('m',time());
/* $sql = "select count(id) as con,sum(pop) as pop from wei_pop where openid='$openid'";
$popArr = $do->selectsql($sql);
$pop = $popArr[0]["pop"]; */
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

<?php if($jointagArr[0]['jointag']==2){?>
function forwordMyPop(){
	window.open("./dianp.php");
}
<?php }?>
function soucang(){
	window.open("./paihang.php");
}
function wodeyouhui(){
	window.open("./mengyou.php");
}
/* function wodemingpian(){
	window.open("../user/mingpian.php");
} */

function xitongshezhi(){
	window.open("./jibenxinxi.php");
}
function guanyuwomen(){
	window.open("./xiaoxi.php");
}
function dianpudingdan(){
	window.open("../order/myorder.php?dianpu_huiyuan=1&type=0");
}
/*function ceshimuban(){
	window.open("../../css/jifen.html");
}*/
</script>
<style>
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
	.tubiao{ display:inline-block; float:left; margin:12px 0 0 8px;}
	.tubiao img{float:left; width:25px; display:inline-block;}

.hy_waikuang{ position:relative;width:100%;height:3.2em;line-height:3.2em;background:white;font-size:1.8em;border-bottom:1px solid #E5E5E5;margin-bottom:0.2em;}
.hy_waikuang .hy_nameq{margin-left:1em;float:left;}
.souytx{ font-size:14px; float:right; padding-right:8px; color:#888;}


	.jiru a:link{ color:#fe7182; font-size:12px;}
	.jiru a:hover{ color:#fe7182; font-size:12px;}
	.jiru a:visited{ color:#fe7182; font-size:12px;}
	html{background:#fff;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#EBEBEB">
<?php if($jointagArr[0]['jointag']==1||$jointagArr[0]['jointag']==2){?>
<div style="position:relative;width:100%;height:18em;overflow:hidden;">
	<img src="../../images/userbg.png" style="position:absolute;width:100%;">
	<div style="position:absolute; top:30px; left:0;right:0;z-index:100;">
		<div style="overflow:hidden;width:70px;height:70px;color:white;font-size:1.5em; margin:0 auto; border-radius:35px;-moz-border-radius:35px;-webkit-border-radius:35px;-o-border-radius:35px;-ms-border-radius:35px;">
			<img src="<?php echo $_SESSION["headimgurl"];?>" style=" max-width:100%;"/>
		</div>
		<div style="margin-top:15px;width:100%;z-index:100;color:white;font-size:1.5em;text-align:center;">
			<p style="margin-top:10px;margin-bottom:0px"><?php echo $nickname ?></p>
		</div>
	</div>
</div>
<div class="hy_waikuang" style="margin-top:0.5em;">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_shouy.png" ></img></span><span class="hy_nameq">我的和众收益：<?php echo $pop ?></span><a href="./yongjin.php"><span class="souytx">提现</span></a>
</div>
<?php if($jointagArr[0]['jointag']==2){?>
<div onclick="dianpudingdan()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_diangw.png" ></img></span><span class="hy_nameq">线下店铺订单</span>
</div>
<div onclick="forwordMyPop()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_diangw.png" ></img></span><span class="hy_nameq">线下店铺定位</span>
</div>
<?php }?>
<div onclick="soucang()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_paih.png" ></img></span><span class="hy_nameq">和众排行榜</span>
</div>
<div onclick="wodeyouhui()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_mengy.png" ></img></span><span class="hy_nameq">我的盟友</span>
</div>
<!-- <div onclick="wodemingpian()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_mingp.png" ></img></span><span class="hy_nameq">我的名片</span>
</div> -->
<div onclick="guanyuwomen()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_xiaox.png" ></img></span><span class="hy_nameq">消息中心<?php if($pcount['count']>0){echo "（".$pcount['count']."）";}?></span>
</div>
<div onclick="xitongshezhi()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_xinx.png" ></img></span><span class="hy_nameq">会员基本信息</span>
</div>
<!--<div onclick="ceshimuban()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/hy_xinx.png" ></img></span><span class="hy_nameq">测试模板</span>
</div>
--><!-- <div onclick="yijianfankui()" style="position:relative;width:100%;height:3em;line-height:3em;background:white;font-size:1.5em;border-bottom:1px solid #E5E5E5">
	<span style="margin-left:0.3em;float:left;">普通会员</span><span style="margin-left:1em;float:left;">店铺商家</span>
</div> -->
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