<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

$openid = $_SESSION["openid"];


$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
if($_GET['id'])
	$goodsid=intval($_GET['id']);
else
	$goodsid=intval($_GET['kd']);


//$jir = "select guige,tuangoujia,tuan_img,tuan_img_jiage,remark,good_name,tuangoujia,orgprice,tgkai_date,tgjie_date from sk_goods where id='$goodsid'";//查看商品资料
$sql="select sk_gs.id gsid,sk_goods.id,sk_goods.good_name,guige,sk_goods.desc,view,price_view,remark,orgprice,sk_gs.price,start_time,end_time,number from sk_goods left join sk_gs on sk_goods.id=sk_gs.gid where sk_gs.status=1 and sk_goods.id='$goodsid'";
$jirArr = $do->selectsql($sql);
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
	<title>团购详情</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
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
        title: koulfx,
        desc: '<?php echo $jirArr[0]["remark"]?>', // 分享描述
        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|tuangou|<?php echo $goodsid; ?>&connect_redirect=1#wechat_redirect',
		imgUrl: '',
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
.tuan_name{ font-size:16px;color:#555;line-height:20px;width:98%;margin:5px auto;}
.tuan_jj{ font-size:12px;color:#888;line-height:20px;width:98%;margin:5px auto;}
.tuandange{ border:1px solid #f4f4f4;width:98%;margin:5px auto 10px auto;}
.tuandange img{width:96%;margin:5px auto;display:block;}
.xxu li{float:left;width:50%;line-height:70px;color:#fff}
.xxu font{display:block;width:60%;margin:0px 1em;background:#fe7182;border-radius: 1em;float:right;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#fff">
<!--<div class="tuandange"><img src="<?php echo $cxtPath ?>/<?php echo $jirArr[0]["tuan_img"]?>" class="imgtt"/>
<div class="tuan_name"><?php echo $jirArr[0]["good_name"]?></div><div class="tuan_jj"><?php echo $jirArr[0]["remark"]?></div><img src="<?php echo $cxtPath ?>/<?php echo $jirArr[0]["tuan_img_jiage"]?>" class="imgtt"/></div>-->


<div class="bigbox clearfix">


<div class="tuantuan clearfix">
<div class="naiqi clearfix"><img src="<?php echo $cxtPath ?>/<?php echo $jirArr[0]["view"]?>"/>
<h2 class="hh2"><?php echo $jirArr[0]["good_name"]?></h2>
<p class="ppi"><?php echo $jirArr[0]["remark"]?></p></div>
<div class="niaiqi1 clearfix">
<h3 class="hh3">十人起团购发货</h3>
<div class="rentuan clearfix" style="padding:0;width:96%; padding-bottom:9%;">
<div class="leftbt left"><span>￥<?php echo (int)$jirArr[0]["orgprice"];?></span><img src="<?php echo $cxtPath ?>/wei/css/img/we554.jpg" class="imghf"/><span style="margin-top:7%">运费</span></div>
					<div class="rightbt right">
					<p style="font-size:22px;">￥<?php echo (int)$jirArr[0]["price"];?></p>
					<span>再省<?php echo (int)($jirArr[0]["orgprice"]-$jirArr[0]["price"]);?>元</span>
					<span>包邮</span>
					</div>
					</div><div style="clear:both;"></div>

<span class="tttime">团购时间:<?php echo date('m-d H:i:s',$jirArr[0]["start_time"])."至".date('m-d H:i:s',$jirArr[0]["end_time"])?></span>
<h3 class="hezong clearfix">洋仆淘特价：¥<?php echo (int)$jirArr[0]["orgprice"];?><a href="<?php echo $cxtPath ?>/wei/main/goods/goods.php?id=<?php echo $goodsid;?>">去特卖场</a></h3>
</div>
</div>
<div class="xx" style='width:100%;height:70px;background:black'>
<ul class='xxu'>
	<li><span style='padding-left:2em'>目前已有<?php echo $jirArr[0]['number'];?>人参团</span></li>
	<li style='text-align:center'><font>下单</font></li>
</ul>
</div>
<script>
$('.xxu font').click(function(){fukuan()});
function fukuan(){
	$.ajax({
		type: "POST",
		url: "tuang_ljgm.php",
		data: {goodsid:"<?php echo $goodsid; ?>",gsid:"<?php echo $jirArr[0]['gsid'];?>",guige:"<?php echo $jirArr[0]['guige']; ?>"},
		success: function(data){
			if(data==1)
				location.replace("../cart/cart.php");
			else if(data==2)
				alert("已经参与过该商品团购！每人只可参加一次");
			else if(data==3)
				alert("现在不是该商品的团购时间！");
			else if(data==4)
				alert("该商品还在购物车中,请及时付款！每人只可参加一次");
		}
	})
}
</script>
</body>
</html>