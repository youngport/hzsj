<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
$cartnum = $_SESSION["cartnum"];
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
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
	<title>商品详情</title>
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
.soucang{ margin:0 20px 0 0;}
</style>
<script type="text/javascript"><!--
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
        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|shangp|<?php echo $id; ?>&connect_redirect=1#wechat_redirect',
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
var cartnum = "<?php echo $cartnum ?>";
var guige = "";
var color = "";
$(function() {
	$('.flexslider').flexslider({
		 animation: "slide",
		 slideDirection: "horizontal"
	 });
	$("#btn_continue").click(function(){
		$("#buy_lay").hide();
		$("#buy_lay_frm").hide();
	});
	$("#btn_check").click(function(){
		window.location='cart.php';
	});	 
	$(document).bind("click",function(){
		$("#buy_lay").hide();
		$("#buy_lay_frm").hide();
	});

	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
});
var kucun_js = <?php echo $goodArr[0]["kucun"] ?>;
function chgNum(a) {
    var number = $("#number")[0];
    var p = parseInt(number.value);
    if (a == 1) {
        if(kucun_js - 1 >= p)
        if (p < 1038) number.value = ++p;
    }
    else {
        if (p > 1) number.value = --p;
    }
}
function changePrice(){
	var number = $("#number")[0].value;
	var price = $("#benprice").text();
	$("#ECS_GOODS_AMOUNT").text(parseFloat(price)*parseInt(number));
}
function selGuiGe(obj){
	guige = $(obj).text();
	$(".guisp").removeClass("selguisp");
	$(obj).addClass("selguisp");
}
function selColor(obj){
	color = $(obj).text();
	$(".colorsp").removeClass("selcolorsp");
	$(obj).addClass("selcolorsp");
}
function addToCart(){
	if(guige == ""){
		alert("请选择规格");
		return;
	}
	//if(color == ""){
		//alert("请选择颜色");
		//return;
	//}
	$.ajax({
		type: "POST",
		url: "goodsAddCart.php",
		data: "goodsid=<?php echo $id ?>&guige="+guige+"&color="+color,
		success: function(data){
			data = eval("("+data+")");
			if(data.success == "1"){
				msgSuccess("添加购物车成功");
				$("#ShoppingCartNum").css({
					"display":"block"
				});
				if(data.type == "add"){
					$("#ShoppingCartNum").text(parseInt(cartnum)+1);
				}
			}else if(data.success == "2"){
				msgError("添加购物车失败，该商品需要直接购买");
			}else{
				msgError("添加购物车失败，请稍后重试");
			}
		}
	});
}
function buy(){
	if(guige == ""){
		alert("请选择规格");
		return;
	}
	//if(color == ""){
		//alert("请选择颜色");
		//return;
	//}
	$.ajax({
		type: "POST",
		url: "goodsAddCart.php",
		data: "goodsid=<?php echo $id ?>&guige="+guige+"&color="+color,
		success: function(data){
			data = eval("("+data+")");
			if(data.success == "1"){
				location.replace("../cart/cart.php");
			}else{
				msgError("购买失败，请稍后重试");
			}
		}
	});
}
--></script>
</head>
<body style="margin:0px;">
<div class="show">
	<div class="icon">
		<a class="no-collect" href="javascript:collect(150);" id='collect'></a>
	</div>
	<div class="flexslider">
		<ul class="slides">
			<li><img src="<?php echo $imgurl.$goodArr[0]["img"] ?>" /></li>
		</ul>
	</div>
</div>
<div class="pro-info">
	<p class="pro-name">
		<strong style="color:#555;"><?php echo $goodArr[0]["good_name"] ?></strong>
	</p>
	<div class="price clearfix">
		<p class="jx-price">本店价：&nbsp;&nbsp;&nbsp;<strong id="benprice"><?php echo $goodArr[0]["price"] ?></strong></p>
	</div>
	<div class='goods_number clearfix'>
		<p class="name">规&nbsp;&nbsp;&nbsp;格：</p>
		<?php
		for($i=0;$i<count($guigeArr);$i++){
			echo "<span onclick='selGuiGe(this)' class='guisp'>".$guigeArr[$i]."</span>";
		}
		?>
	</div>
	<div class="goods_number clearfix">
		<p class="name">数&nbsp;&nbsp;&nbsp;量：</p>
		<div class="addForm">	
			<input type="button" value="-" class="btn" onClick="chgNum(-1);changePrice();" />	
			<input type="text" id='number' name="number" onblur="changePrice()" value="1" class="text"/>	
			<input type="button" value="+" class="btn" onClick="chgNum(1);changePrice();" />
		</div>
	</div>
	<div class="goods_number clearfix">
		<p class="name">库&nbsp;&nbsp;&nbsp;存：</p>
			<span style="color:#666;"><?php echo $goodArr[0]["kucun"] ?></span>
	</div>
	<div class='goods_number clearfix'>
		<p class="name">商品总价：</p>
		<span class="shopcount" id="ECS_GOODS_AMOUNT"><?php echo $goodArr[0]["price"] ?></span>
	</div>
	<div class='goods_number clearfix'>
		<p class="name">功能：</p>
		<span class="soucang" id="soucang">收藏</span>
		<span class="soucang" id="lefenxiang">乐分享</span>
	</div>
	<div class="div_but1 clearfix" id='btn1' style="display: ;">
		<div class="buybut" >
			<a class='ljgm' name="goods_buy" href="javascript:buy()" style="background:#fe7182;">
				立即购买
			</a>
		</div>
        <?php if($id_goodstype != 2){?>
		<div class="cartbut" style="background:#fe7182;">
			<a class='add'  style="background:#fe7182;" name="goods_cart" href="javascript:addToCart()">
				加入购物车
			</a>
		</div>
        <?php }?>
	</div>
</div>
<div class="pro-detial">
	<!-- <div class="judge clearfix">
		<a href="comment.php~g_id=150.html"><span class="m-ratescore"><i style="WIDTH: 100%">100%</i></span>
		<span class="all"><strong>0</strong>人评论</span>
		<span class="nice"><strong>100%</strong>好评</span></a>
		<span class="arrow"></span>
	</div> -->
	<div class="mc radius desc">
		<?php echo $goodArr[0]["desc"] ?>
		<!-- <p><img src="../images/muguaa.jpg" width="100%" alt="" /><img src="../images/muguaa.jpg" width="100%" alt="" /></p> -->
	</div>
</div>

<div style="height:80px;"></div>
<?php include '../gony/nav.php';?>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
<script type="text/javascript">
$(function(){
	$('#soucang').click(function(){
		$.ajax({
			type: "POST",
			url: "soucang.php",
			data: {soucang:<?php echo $id ?>},
			success: function(data){
				if(data==1){
					alert('该商品收藏成功！');
				}else if(data==2){
					alert('该商品已存在收藏中！');
				}else
					alert('搜藏失败');
			}
		});
	})
	$('#lefenxiang').click(function(){
		$.ajax({
			type: "POST",
			url: "soucang.php",
			data: {soucang:<?php echo $id ?>},
			success: function(data){
				if(data==1){
					alert('该商品收藏成功！');
				}else if(data==2){
					alert('该商品已存在收藏中！');
				}else
					alert('搜藏失败');
			}
		});
	})
});
</script>
</body>
</html>