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

/* $sql = "select sum(p1.pop) as pop,p2.popdui,p3.poping,p4.popwait,p5.popwdh from wei_pop p1,
		(select sum(pop) as popdui from wei_pop where openid='$openid' and duitag='1') p2,
		(select sum(pop) as poping from wei_pop where openid='$openid' and duitag='2') p3,
		(select sum(pop) as popwait from wei_pop where openid='$openid' and duitag='3') p4 ,
		(select sum(pop) as popwdh from wei_pop where openid='$openid' and duitag='0') p5
		where p1.openid='$openid'"; */
$sql = "select shouyi as pop,p2.popdui,p3.poping from sk_member p1,
		(select sum(totpop) as popdui from wei_exchange where openid='$openid' and audit_tag='1') p2,
		(select sum(totpop) as poping from wei_exchange where openid='$openid' and audit_tag='0') p3
		
		where p1.open_id='$openid'";
$popArr = $do->selectsql($sql);
$pop = $popArr[0]["pop"];
if($pop == "") $pop = "0.0";
$popdui = $popArr[0]["popdui"];
if($popdui == "") $popdui = "0.0";
$poping = $popArr[0]["poping"];
if($poping == "") $poping = "0.0";
//$popwait = $popArr[0]["popwait"];
if($popwait == "") $popwait = "0.0";


$y=date("Y",time());
$m=date("m",time());
//$start_time = mktime(0, 0, 0, $m, 1 ,$y);
$start_time = $y.'-'.$m.'-1 00:00:00';
if($m+1>12)
    $start_time2 = ($y+1).'-1-1 00:00:00';
else
    $start_time2 = $y.'-'.($m+1).'-1 00:00:00';
$sql =  "select sum(pop) dypop from wei_pop where openid='$openid' and poptime > '$start_time' and poptime < '$start_time2'";
$dypop = $do->selectsql($sql);
$dypop_z = $dypop[0]['dypop'];
if($dypop_z==""||$dypop_z==null){
    $dypop_z = "0.00";
    $pop_zph = "- -";
}else{

    $sql =  "select p.openid,sum(p.pop) from wei_pop p join sk_member m on m.open_id=p.openid where m.pid='$openid' and p.poptime > '$start_time' and p.poptime < '$start_time2' group by p.openid having sum(p.pop)>".$dypop_z;
    //$sql =  "select openid,sum(pop) from wei_pop where poptime > '$start_time' and poptime < '$start_time2' group by openid having sum(pop)>".$dypop_z;
    $poppm = $do->selectsql($sql);
    $pop_zph = (count($poppm)+1);
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
	<title>会员中心</title>
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
var cartnum = "<?php echo $cartnum ?>";
$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
});

function duihuan(){
	if(<?php echo $pop;?>>0)
		window.open("../user/dui.php?txd="+$('#txd input[name="fankuijifen"]:checked ').val()+"&txje="+$('#txpop').val());
	else
		alert('没有可提现的收益！');
}
function jilu(){
	window.open("../user/duiorder.php");
}
function xiugaixinxi(){
	window.open("../user/userinf.php");
}
</script>
<style>
.dibutixian{ width:100%;position:fixed;bottom:0;left:0;right:0;font-size:14px;}
.dibutixian tr{ width:100%;}
.dibutixian tr td{padding-top:15px;padding-bottom:10px;}
.dibutixian tr .tx{ width:40%;background:#fe7182;color:#fff;}
.zhong_font{ color:#fff;text-align:center;font-size:16px;padding:20px 0 0 0;}
.zongsouyi{ color:#fff;text-align:center;font-size:54px;padding:25px 0 40px 0;}
.sanx{ float:left;text-align:center;font-size:12px;width:50%; color:#fff;}
.baimo{ display:none; filter:alpha(opacity:70);opacity:0.7;-moz-opacity:0.7; background:#fff;z-index:2;position:fixed;top:0;left:0;bottom:0;right:0; }
.tixian_x{display:none;background:#fff;z-index:3;position:fixed;
    width:280px;
    border-radius:8px;
    -moz-border-radius:8px;
    -webkit-border-radius:8px;
    -o-border-radius:8px;
    -ms-border-radius:8px;
    
    box-shadow:0px 0px 15px #e8e8e8;
    -moz-box-shadow:0px 0px 15px #e8e8e8;
    -webkit-box-shadow:0px 0px 15px #e8e8e8;
    -ms-box-shadow:0px 0px 15px #e8e8e8;
    -o-box-shadow:0px 0px 15px #e8e8e8;
}
.tixian{ font-size:20px;text-align:center;height:35px;line-height:35px;color:#fe7182;border-bottom:1px solid #ebebeb;}
.ltj{ float:left;color:#363636;font-size:16px;margin-left:25px;}
.tianxie{font-size:14px;color:#fe7182;text-align:center;}
.tianxie img{width:15px;margin-top:-4px;}
.santian{ font-size:14px;color:#fe7182;margin:3px 0 20px 25px;}
.dianji{ text-align:center; font-size:14px;height:30px; color:#fff;}
.dianji span{ margin:0 5px;padding:7px 25px;
    border-radius:4px;
    -moz-border-radius:4px;
    -webkit-border-radius:4px;
    -o-border-radius:4px;
    -ms-border-radius:4px;}
.txpop{color:#818181;font-size:14px;border:none;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#fff">
<div style="background:#fef6b9;height:35px;"><img src="<?php echo $cxtPath ?>/wei/images/zuojian.png" width=20 style="float:right;margin:5px 15px 0 0;"/><span style="display:block;float:right;font-size:16px; line-height:35px; color:#fc7281;" onclick="jilu()">兑换记录</span>
</div>
<div class="yongji clearfix">
<!--<h4 style="font-size:18px">总收益(元)</h4>
<h5 style="font-size:50px;margin:0;padding:0;"><?php echo $pop ?></h5>
<div class="rty clearfix">
<span>已兑换收益<font><?php echo $popdui ?></font></span>
<span>兑换中收益<font style="color:#ff0000;"><?php echo $poping ?></font></span>
<span>待确认收益<font style="color:#fff000;"><?php echo $popwait ?></font></span>
</div>-->
<div class="zhong_font">可提现收益（元）</div>
<div class="zongsouyi"><?php echo $pop ?></div>
<div class="sanx">
	<div>已兑换收益</div>
	<div style="font-size:16px;line-height:25px;color:#3F830B;"><?php echo $popdui ?></div>
</div>
<div class="sanx">
	<div>兑换中收益</div>
	<div style="font-size:16px;line-height:25px;color:red;"><?php echo $poping ?></div>
</div>
<!-- <div class="sanx">
	<div>待确认收益</div>
	<div style="font-size:16px;line-height:25px;color:#F1DC22;"><?php echo $popwait ?></div>
</div> -->
<div style="clear:both;height:30px;"></div>
</div>

<table width="100%" style="margin:20px 0;">
	<tr align="center">
		<td width="60%" valign="top">
    		<div style="color:#fe7182;font-size:14px;">您近一个月的收益（元）</div><br height=15 />
    		<div style="color:#727272;font-size:14px;"><?php echo $dypop_z;?></div>
		</td>
		<td width="40%" valigin="top">
		    <a href="./paihang.php" >
    		    <div style="border-left:1px solid #b4b4b4;">
        		  <div style="color:#fe7182;font-size:14px;">本月收益排行</div><br height=15 />
        		  <div style="color:#727272;font-size:14px;"><?php echo $pop_zph;?></div>
        		</div>
    		</a>
		</td>
	</tr>
</table>
<div style="border-top:1px solid #b4b4b4"></div>
<div style="height:30px;background:#fe7182;color:#fff;line-height:30px;text-align:center;width:75%;margin:25px auto 0 auto;font-size:16px;" onclick="showto()">立即提现</div>
<a href="./txshuoming.html"><div style="height:30px;color:#fe7182;line-height:30px;text-align:center;width:75%;margin:0 auto;font-size:16px;">提现说明</div></a>
<div style="width: 92%;margin: 20px 4% 20px 4%;text-align:center;font-size: 16px;color: #848181;line-height: 20px;margin-bottom: 10px;">首次提现，和众收益满10元即可提现。第二次起，和众收益需满50元方可提现。</div>
<div class="baimo" id="baimo"></div>
<!--<div class="tixian_x" id="tixian_x">-->
<!--    <div class="tixian">提现确认</div>-->
<!--    <div class="tixian"><span class="ltj">金额</span><input class="txpop" type="text" name="yin_username" id="txpop" value='--><?php //echo $pop; ?><!--'></div>-->
<!--    <div class="tixian" id="txd"><span class="ltj">提现到</span><span style="color:#818181;font-size:14px;">-->
<!--        <input type="radio" name="fankuijifen" class="radio_style" value="1" checked >&nbsp;支付宝&nbsp;&nbsp;-->
<!--        <input type="radio" name="fankuijifen" class="radio_style" value="0" >&nbsp;网银</span>-->
<!--    </div>-->
<!--    <div class="santian"><span>3天后到账</span></div>-->
<!--    <div class="dianji">-->
<!--        <span style="background:#c5c5c5;" onclick="heivqu();">取消</span>-->
<!--        <span style="background:#fe7182;" onclick="duihuan()">确认</span>-->
<!--    </div>-->
<!--    <div class="tianxie"><span onclick="xiugaixinxi()">去填写银行卡支付宝信息<img src="--><?php //echo $cxtPath ?><!--/wei/images/qrsj.png" /></span></div>-->
<!--    <div style="height:20px;"></div>-->
<!--</div>-->
<!--<?php include '../gony/nav.php';?>-->
<?php include '../gony/guanzhu.php';?>
<?php include './main/gony/returntop.php';?>
<script>
$('#tixian_x').css({top:($('#baimo').height()-$('#tixian_x').height())/2+'px'});
$('#tixian_x').css({left:($('#baimo').width()-$('#tixian_x').width())/2+'px'});
function showto(){
	/* $('#tixian_x').css({'z-index':'3'});
	$('#baimo').css({'z-index':'2'}); */
	//$('#tixian_x').show();
	//$('#baimo').show();
	location.href="../user/dui.php";
}
function heivqu(){
	/* $('#tixian_x').css({'z-index':'-3'});
	$('#baimo').css({'z-index':'-2'}); */
	$('#tixian_x').hide();
	$('#baimo').hide();
}
</script>
</body>
</html>