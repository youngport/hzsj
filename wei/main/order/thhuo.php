<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
header("content-type:text/html;charset=utf8;");
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
$type = intval($_GET["type"]);
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];

$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl =  'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

$orderid = intval($_GET['orderid']);
$bian =addslashes($_GET['bian']);

$sql="select is_member from sk_orders where order_id=$orderid and status!=0";
$ug=$do->getone($sql);
if($ug['is_member']==1){
	echo "<script>alert('会员特惠商品不能退货');history.back(-1);</script>";
	exit;
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
	<title>我的订单</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v2.0" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
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

</script>
</head>
<style>
.tim{font-size:14px;color:#555;line-height:25px;margin-top:5px;}
.shuru{width:100%; height:30px;border:1px solid #f4f4f4;color:#555;}
.shuru font{padding-right:1em;}
.tijiao{background:#fe7182;width:120px;font-size:14px;height:30px;line-height:30px;text-align:center;color:#fff;margin:20px auto;}
</style>
<body style="background:#fff;padding:0;margin:0;">
<div style="width:96%;margin:15px auto;">
    <div class="tim">订单编号：<?php echo $bian; ?></div>
    <div class="tim">姓名<input name="th_xingming" class="shuru" type="text" id="th_xingming" placeholder="请输入姓名" ></div>
    <div class="tim">手机号码<input name="th_shouji" class="shuru" type="text" id="th_shouji" placeholder="请输入手机号码" ></div>
    <div class="tim">退换货来由<div class="shuru" style="height:auto;"><p><font><input type='radio' name='lianx' value='商品漏发/错发'/>商品漏发/错发</font><font><input type='radio' name='lianx' value='收到商品破损'/>收到商品破损</font><font><input type='radio' name='lianx' value='商品与描述不符'/>商品与描述不符</font><font><input type='radio' name='lianx' value='质量问题'/>质量问题</font><font><input type='radio' name='lianx' value='未按约定时间发货'/>未按约定时间发货</font><font><input type='radio' name='lianx' value='' class='tx'/>其他</font></p><textarea id="th_liyou" class="shuru" style="height:130px;width:94%;margin:10px 3%;border:none;display:none;" placeholder="请输入退换货来由" ></textarea></div></div>
	<div class="tim">银行卡姓名<input name="th_cname" class="shuru" type="text" id="th_cname" placeholder="请输入真实的银行卡姓名" ></div>
	<div class="tim">银行卡卡号<input name="th_credit" class="shuru" type="text" id="th_credit" placeholder="请输入银行卡卡号,请务必确认无误!" ></div>
    <div class="tim" id ="thuan" style="padding-left:10px;">
        <input type="radio" name="thuan" id="thuan1" class="radio_style" value="0" checked="checked"> &nbsp;退货&nbsp;&nbsp;&nbsp;
        <input type="radio" name="thuan" id="thuan2" class="radio_style" value="1"> &nbsp;换货
	</div>
	<div id="tijiao" class="tijiao">提交申请</div>
</div>
<script>
$('#tijiao').click(function(){
	var reg=/^[0-9a-zA-Z]{11,30}/;
	if($('#th_xingming').val()=="")
		alert('姓名不能为空');
	else if($('#th_shouji').val()=="")
		alert('手机不能为空');
	else if($('#th_cname').val()=="")
		alert('银行卡姓名不能为空');
	else if($('#th_credit').val()==""||!reg.test($('#th_credit').val()))
		alert('银行卡卡号格式错误');
	else {
    	$.ajax({
			//+"&lianxs="+$('#th_liyou').val()
    		type: "POST",
    		url: "thhuoget.php",
    		data: "order_id=<?php echo $orderid ?>&xingm="+$('#th_xingming').val()+"&souji="+$('#th_shouji').val()+"&thuan="+$('#thuan input[name="thuan"]:checked').val()+"&lianx="+$("input[name='lianx']:checked").val()+"&cname="+$('#th_cname').val()+"&credit="+$('#th_credit').val(),
    		success: function(data){
        		if(data==1){
            		alert('退换货申请中，请耐心等候..');
            		window.location.href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=3"; 
        			//window.open("<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=3");
            	}else if(data==2){
            		alert('该订单已经在退换货申请中，请耐心等候..');
            		window.location.href="<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=3"; 
        			//window.open("<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=3");
            	}else
                	alert('申请失败！请稍后重新申请');
    		}
    	});
	}
})
$('.tx').click(function(){
	$('#th_liyou').show();
});
$("input[type='radio']:not('.tx')").click(function(){
	$('#th_liyou').hide();
});
$('#th_liyou').blur(function(){
	$('.tx').val($(this).val());
});
</script>
</body>
</html>