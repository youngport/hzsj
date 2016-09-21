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

$sql = "select jointag from sk_member where openid='$openid'"; 
$popArr = $do->selectsql($sql);
/*
if($popArr[0]['jointag']==0){//----------------------------活动流程------
	header("Location:".$cxtPath."/wei/main/liucheng.php");
}*/

$selyear = date('Y',time())."-".date('m',time());
$sql = "select count(id) as con,sum(pop) as pop from wei_pop where openid='$openid'";
$popArr = $do->selectsql($sql);
$pop = $popArr[0]["pop"];
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
	<title>我的盟友</title>
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

.mengyou_ul li{ padding:10px 0 0 10px;}
.mengyou_ul .tuoxiang{width:80px; height:80px; float:left;}
.mengyou_ul .tuoxiang img{max-width:80px;}
.mengyou_ul .name {height:80px; float:left; font-size:16px; line-height:25px; margin-left:10px; width:60%; overflow: hidden;}
.my_fenqu{position:fixed;height:40px;text-align:center;font-size:16px;color:#665;left:0;right:0;bottom:50px;width:100%;}
.my_fenqu td{width:50%;}
#my_ul li{ padding:10px;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#fff">
<div class="bigbox clearfix">

<div class="touhead clearfix" style="background:url(<?php echo $cxtPath ?>/wei/css/img/tipy.jpg) top center no-repeat;background-size:100%;width:100%;margin:0 auto;padding-top:20px;padding-bottom:18px;">
<div class="tip clearfix" style="padding:0;border:1px solid #fff;"><img src="<?php echo $_SESSION["headimgurl"]; ?>" style="width:100%;"/></div>
<h5><?php echo $nickname ?></h5>
</div>
<ul class="clearfix mengyous" id="my_ul">

</ul>

</div>
<div id="jiazai" style="color:#a3a4a4;font-size:0.9em;text-align:center;margin:0.5em 0;">加载中...</div>
<div style="clear:both;height:90px;"></div>
<table class="my_fenqu">
    <tr align="center"><td id="huiymy">会员盟友</td><td id="putmy">普通盟友</td></tr>
</table>
<script>
var data_sum = 0;
var data_s = 1;
function my_get(){
	$('#jiazai').html('加载中...');
	$.ajax({
		type: "POST",
		url: "mengyou_get.php",
		data: {s:data_s,data_sum:data_sum},
		success: function(data){
			$('#jiazai').html('暂无盟友');
			data = eval("("+data+")");
			if(data.length > 10){
			    //显示加载更多
				$('#jiazai').show();
				$('#jiazai').html('加载更多');
			}else{
				//隐藏加载更多
				$('#jiazai').hide();
			}
			if(data.length>0){
				for(var mi=0;mi<10;mi++){
					myhtml = "";
					myhtml += "<li><img src=\""+data[mi].headimgurl+"\">";
					if(data[mi].wei_nickname=="")
						myhtml += "<div class=\"left\"><h3>匿名盟友<font></font></h3>";
					else
						myhtml += "<div class=\"left\"><h3>"+data[mi].wei_nickname+"<font></font></h3>";
					if(data[mi].pop == null ||data[mi].pop == "")
						myhtml += "<h4>Ta的收益：<font>￥0.00</font></h4>";
					else
						myhtml += "<h4>Ta的收益：<font>￥"+data[mi].pop+"</font></h4>";
					if(data[mi].mypop == null ||data[mi].mypop == "")
						myhtml += "<h4>我从Ta获取的收益<font class=\"fontg\">￥0.00</font></h4>";
					else
						myhtml += "<h4>我从Ta获取的收益<font class=\"fontg\">￥"+data[mi].mypop+"</font></h4>";
					myhtml += "</div><a href=\"./shouyiq.php?shuzhi="+data[mi].mypop+"&shouyi="+data[mi].open_id+"\">详情</a></li>"
					$("#my_ul").append(myhtml);
				}
			}
		}
	})
}
$('#jiazai').click(function(){
	data_sum += 10;
	my_get();
})
$('#huiymy').click(function(){
	$(this).css({background:'#fff'});
	$('#putmy').css({background:'#f4f4f4'});
	$('#jiazai').show();
	$("#my_ul").empty();
	data_sum = 0;
	data_s = 1;
	my_get();
})
$('#putmy').click(function(){
	$(this).css({background:'#fff'});
	$('#huiymy').css({background:'#f4f4f4'});
	$('#jiazai').show();
	$("#my_ul").empty();
	data_sum = 0;
	data_s = 0;
	my_get();
})
my_get();
$('#putmy').css({background:'#f4f4f4'});
$('#huiymy').css({background:'#fff'});
</script>
<div style="height:50px;"></div>
<?php include '../gony/nav.php';?>
<?php include '../gony/guanzhu.php';?>
<?php include './main/gony/returntop.php';?>
</body>
</html>