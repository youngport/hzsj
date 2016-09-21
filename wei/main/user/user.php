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
$sql ="select jifen from sk_member where open_id='$openid'";
$jifen = $do->selectsql($sql);

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$userArr = $com->getUserDet($openid, $do);
if($userArr[0]['jointag']==2){
    $vip_zt = '店铺会员';
}elseif($userArr[0]['jointag']==1){
    $vip_zt = '洋仆淘会员';
}else{
    $vip_zt = '普通用户';
}
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
	<title>个人中心</title>
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
function wodedingdan(){
	$('#wod_dingdan').slideToggle();
	$('#wod_youhui').slideUp()
}
function forwordMyPop(){
	window.open("../category/fenlei.php");
}
function soucang(){
	window.open("soucang.php");
}
function wodeyouhui(){
	$('#wod_youhui').slideToggle();
	$('#wod_dingdan').slideUp();
}
function yijianfankui(){
	window.open("fankui.php");
}
function guanyuwomen(){
	window.open("guanyu.php");
}
function xiaoxi(){
	window.open("../members/xiaoxi.php");
}
</script>
<style>
	.wod_dingdan{ width:100%;height:45px;display:none; margin:0 auto;}
	.wod_dingdan tr{ width:100%;height:45px;}
	.wod_dingdan tr td{ width:25%;height:45px;}
	.wod_dingdan br{height:0;}
	.wod_dingdan li{ width:24%;height:45px;text-align:center;line-height:20px;float:left; padding-top:3px;}
	.wod_youhui{ width:100%;height:35px;display:none;}
	.wod_youhui tr{ width:100%;height:35px;}
	.wod_youhui tr td{ width:50%;height:35px; font-size:12px; color:#555;padding-left:10px;}
	.wod_youhui li{ width:49%;height:35px;text-indent:15px;line-height:35px;float:left; font-size:12px; color:#555;}
	.wod_youhui a:link{ color:#555; font-size:12px;}
	.wod_youhui a:hover{ color:#555; font-size:12px;}
	.wod_youhui a:visited{ color:#555; font-size:12px;}
	.wod_dingdan a:link{ color:#555; font-size:12px;}
	.wod_dingdan a:hover{ color:#555; font-size:12px;}	
	
	
	.tubiao{ display:inline-block; float:left; margin:13px 0 0 8px;}
	.tubiao img{float:left; width:25px; display:inline-block;}

	.hy_waikuang{ position:relative;width:100%;height:3.2em;line-height:3.2em;background:white;font-size:1.8em;border-bottom:1px solid #E5E5E5;margin-bottom:0.2em;}
	.hy_waikuang .hy_nameq{margin-left:1em;float:left;}

	.wod_dingdan a:visited{ color:#555; font-size:12px;}
	.yh_tb{ width:20px; margin:0 3px 0 0;}
	.yh_tb img{width:20px;margin-top:-3px;}
	.dd_img{ width:20px;}
	
	
.toppu{padding:2em 0;background:#fff;}
.top_img{ float:left;width:8em;height:8em;margin:0 2em 0 2.5em;border:1px solid #f6b616;padding:0.2em;position:relative;}	
.top_img .vtu{position:absolute;width:35%;bottom:0;right:0;}	
.top_name{float:left;}	
.top_name .name{  font-size:1.5em;line-height:1.6em;}	
.top_name .jianjie{  font-size:1.5em;line-height:1.2em;color:#888;width:8em;overflow:hidden;}
.top_name .huiyuan{ font-size:1.5em; line-height:2em;}	
</style>
</head>
<body style="margin:0px;padding:0px;background:#EBEBEB">
<div class="toppu">
    <div class="top_img"><a href="../members/jibenxinxi.php"><img width="100%" height="100%" src="<?php echo $_SESSION["headimgurl"];?>" style=" max-width:100%;"/></a><?php if($userArr[0]['jointag']==2 || $userArr[0]['jointag']==1){echo '<img class="vtu" src="../../img/hyvip.png" style=" max-width:100%;"/>';}?></div>
    <div class="top_name">
        <div class="name"><?php echo $nickname ?></div>
        <div class="jianjie">
			<?php
			if($userArr[0]['pid']!='') {
				$pid = $do->getone("select wei_nickname from sk_member where open_id='" . $userArr[0]['pid'] . "'");
				echo "推荐人:";
				echo $pid['wei_nickname']==''?'匿名':$pid['wei_nickname'];
			}
			?>
		</div>
        <div class="huiyuan"><?php echo $vip_zt;?></div>
    </div>
    <!-- <div class="top_erweima">
        <img class="vtu" id="vtu" src="../../img/erweima.png" style=" max-width:100%;"/>
        <div>二维码</div>
    </div> -->
    <div style="clear:both;float:none;height:0;"></div>
</div>
<!-- <div style="position:relative;width:100%;height:18em;overflow:hidden;">
	<img src="../../images/userbg.png" style="position:absolute;width:100%;">
	<div style="position:absolute; top:30px; left:0;right:0;z-index:100;">
		<div style="overflow:hidden;width:70px;height:70px;color:white;font-size:1.5em; margin:0 auto; border-radius:35px;-moz-border-radius:35px;-webkit-border-radius:35px;-o-border-radius:35px;-ms-border-radius:35px;">
			<img src="<?php echo $_SESSION["headimgurl"];?>" style=" max-width:100%;"/>
		</div>
		<div style="margin-top:15px;width:100%;z-index:100;color:white;font-size:1.5em;text-align:center;">
			<p style="margin-top:10px;margin-bottom:0px"><?php echo $nickname ?></p>
		</div>
	</div>
</div> -->
<div onclick="wodedingdan()"  class="hy_waikuang" style="margin-top:0.5em;">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/dingdan.png" ></img></span><span  class="hy_nameq" style="margin-bottom:0.3em">我的订单</span>
</div>
<table class="wod_dingdan" id="wod_dingdan" >
	<tr align="center">
		<td><a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=1"><img class="dd_img" src="<?php echo $cxtPath ?>/wei/img/user_dd_dk.png" /><br />待付款</a></td>
		<td><a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=2"><img class="dd_img" src="<?php echo $cxtPath ?>/wei/img/user_dd_sh.png" /><br />待收货</a></td>
		<td><a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=3"><img class="dd_img" src="<?php echo $cxtPath ?>/wei/img/user_dd_th.png" /><br />退换货</a></td><!-- 退换货的type=2 -->
		<td><a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=0"><img class="dd_img" src="<?php echo $cxtPath ?>/wei/img/user_dd_qb.png" /><br />全部订单</a></td>
	</tr>
</table>
<!--<ul class="wod_dingdan" id="wod_dingdan">
	<li><a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=1"><img class="dd_img" src="<?php echo $cxtPath ?>/wei/img/user_dd_dk.png" /><br />待付款</a></li>
	<li><a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=2"><img class="dd_img" src="<?php echo $cxtPath ?>/wei/img/user_dd_sh.png" /><br />待收货</a></li>
	<li><a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=3"><img class="dd_img" src="<?php echo $cxtPath ?>/wei/img/user_dd_th.png" /><br />退换货</a></li> 退换货的type=2 
	<li><a href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=0"><img class="dd_img" src="<?php echo $cxtPath ?>/wei/img/user_dd_qb.png" /><br />全部订单</a></li>
</ul>-->
<div style="clear:both;height:0;"></div>
<div onclick="forwordMyPop()"  class="hy_waikuang">
	<span class="tubiao"><img style="" src="<?php echo $cxtPath ?>/wei/img/user_vip.png" ></img></span><span  class="hy_nameq">我要成为VIP</span>
</div>
<div onclick="soucang()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/user_sc.png" ></img></span><span class="hy_nameq">我的收藏</span>
</div>
<div onclick="wodeyouhui()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/user_yh.png" ></img></span><span class="hy_nameq" style="margin-bottom:0.3em">我的优惠</span>
</div>
<table class="wod_youhui" id="wod_youhui">
	<tr>
		<td><a href="<?php echo $cxtPath ?>/wei/main/jifen/jifen.php?type=0"><span class="yh_tb"><img src="<?php echo $cxtPath ?>/wei/img/user_xjz.png" /></span>现金卷:0</a></td>
		<td><a href="<?php echo $cxtPath ?>/wei/main/jifen/jifen.php?type=1"><span class="yh_tb"><img src="<?php echo $cxtPath ?>/wei/img/user_jf.png" /></span>积分:<?php echo $jifen[0]['jifen'];?></a></td>
	</tr>
</table>
<!--<ul class="wod_youhui" id="wod_youhui">
	<li><span class="yh_tb"><img src="<?php echo $cxtPath ?>/wei/img/user_xjz.png" /></span>现金卷:0</li>
	<li><span class="yh_tb"><img src="<?php echo $cxtPath ?>/wei/img/user_jf.png" /></span>积分:0</li>
</ul>
-->
<?php if($userArr[0]['jointag']==0){
	$pcount=$do->getone("select sum(if(isnull(open_id),1,0)) count from sk_message a left join sk_message_read b on a.id=b.mid where rec='$openid' or recpid=(select if(pid!='',pid,0) from sk_member where open_id='$openid')");
?>
<div style="clear:both;height:0;"></div>
<div onclick="xiaoxi()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/user_xx.png" ></img></span><span class="hy_nameq">消息中心<?php if($pcount['count']>0){echo "（".$pcount['count']."）";}?></span>
</div>
<?php }?>
<div style="clear:both;height:0;"></div>
<div onclick="guanyuwomen()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/user_gy.png" ></img></span><span class="hy_nameq">关于我们</span>
</div>
<div onclick="yijianfankui()"  class="hy_waikuang">
	<span class="tubiao"><img src="<?php echo $cxtPath ?>/wei/img/user_fk.png" ></img></span><span class="hy_nameq">意见反馈</span>
</div>
<div style="height:80px;"></div>
<?php include '../gony/nav.php';?>
<?php include '../gony/guanzhu.php';?>

</body>
</html>