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

$userArr = $com->getUserDet($openid, $do);
$joinTime = $userArr[0]["login_time"];
$year = substr($joinTime, 0, 4);
$month = substr($joinTime, 5, 2);
$curyear = date('Y',time());
$curmonth = date('m',time());
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
	<title>每月订单</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/area.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
<style>
.sel_wrap{width:100%;margin-left:auto;margin-right:auto;height:2.4em;background:#fff url(../../images/icons.png) no-repeat right -24px;
color: #a1a1a1; font-size: 16px;
border:1px solid #E4E4E4;cursor:pointer;position:relative;_filter:alpha(opacity=0);

}
.sel_wrap .select{width:100%; height:40px; line-height:40px; z-index:4;position:absolute;top:0;left:0;margin:0;padding:0;opacity:0; *margin-top:12px; filter:alpha(opacity=0);cursor:pointer; font-size: 16px;}
.bottom{  
	position:fixed;
	height:5em;
	right:0px;
	_position:absolute;
	/*_right:17px;*/
	bottom:0px;
	background:white;
	color:#fff;
	border-top:1px solid #fff;
	display:block;
	width:100%;
	padding-top:0.5em;
}
.appdiv{
	width:100%;height:100%;background:red;z-index:1000;
}
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
$(window).bind('resize load', function(){
	$("body").css("zoom", $(window).width() / 480);
	$("body").css("display" , "block");
});
var cartnum = "<?php echo $cartnum ?>";
$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
	$(".sel_wrap").on("change", function() {
		var o;
		var opt = $(this).find('option');
		opt.each(function(i) {
			if (opt[i].selected == true) {
				o = opt[i].innerHTML;
			}
		});
		$(this).find('label').html(o);
	}).trigger('change');
	loadData("<?php echo $curyear ?>"+"-"+"<?php echo $curmonth ?>");
});
function loadData(selyear){
	$.ajax({
		type: "POST",
		url: "integralSearch.php",
		data: "selyear="+selyear,
		success: function(data){
			data = eval("("+data+")");
			var pop = data.pop;
			var popdui = data.popdui;
			var poping = data.poping;
			var con = data.con;
			if(pop == "") pop = "0.0";
			if(popdui == "") popdui = "0.0";
			if(poping == "") poping = "0.0";
			$("#pop").text(pop);
			$("#popdui").text(popdui);
			$("#poping").text(poping);
			$("#botpop").text(pop);
			$("#ordercon").text(con);
		}
	});
}
function duihuan(){
	window.open("dui.php");
}
</script>
</head>
<body style="background:#EBEBEB;">
<div class="header_03" style="background:white">
	<div class="back">
		<a href="../../index.php" class="arrow"></a>
	</div>
	<div class="tit">每月订单</div>
	<div class="nav">
		<ul>
			<li class="cart"><a href="../cart/cart.php">购物车</a><span style="display:none" id="ShoppingCartNum"></span></li>
		</ul>
	</div>
</div>
<div style="height:1.5em;width:100%;"></div>
<div class="sel_wrap">
	<label style="text-align:center;color:black;font-size:1.5em;z-index:2;line-height: 1.8em; height:2.4em; display: block;">请选择您所在的城市</label>
	<select class="select" name="" id="c_city">
		<?php
		$year = intval($year);
		$month = intval($month);
		$curyear = intval($curyear);
		$curmonth = intval($curmonth);
		for($y=$curyear;$y>=$year;$y--){
			$strmonth = 12;
			if($curyear == $year){
				$strmonth = $curmonth;
			}else{
				$month = "1";
			}
			for($u=$strmonth;$u>=$month;$u--){
				echo "<option value='".$y."-".$u."'>".$y."年".$u."月</option>";		
			}
		}
		?>
	</select>
</div>
<div style="margin-top:0.5em;margin-left:auto;margin-right:auto;width:100%;height:auto;background:white;padding:10px;font-size:1.8em;">
本月总佣金：<font id="pop">0.0</font>分，总订单数：<font id="ordercon">0</font><br/>
(已兑换佣金：<font id="popdui" style="color:green;">0.0</font>，兑换中佣金：<font id="poping" style="color:red;">0.0</font>)<br/>
说明：兑换规则以商家通知为准，请在个人中心填写正确的支付宝或银行账号，以便商家操作。	
</div>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>