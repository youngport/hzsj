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

$id = intval($_GET['id']);

$huodong = false;
$goodArr = $com->getGoodDet($id, $do);
if($goodArr[0]["canyuhd"]==1){
    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 1 and canyuid=(select cate_id from sk_goods where id='$id '))";
    $LArr = $do->selectsql($sql);
    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 2 and canyuid=(select mp_id from sk_goods where id='$id '))";
    $MArr = $do->selectsql($sql);
    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 3 and canyuid=(select gx_id from sk_goods where id='$id '))";
    $GArr = $do->selectsql($sql);
    if($LArr[0]['cou'] > 0||$MArr[0]['cou'] > 0||$GArr[0]['cou'] > 0){
          $huodong = true;
    }else{
          $huodong = false;
    	}
    }
    //根据商品分类决定税率 $tax_rate
   $cate_id = $goodArr[0]["cate_id"];
   $goods_type = $do->selectsql("select pid from sk_items_cate where id=$cate_id "); 
   if ($goods_type[0]['pid']=='100' && $goodArr[0]["fh_type"]=='保税') {
   	     $tax_rate = 0.329;//保税——美妆
   }elseif (($goods_type[0]['pid']=='65'|| $goods_type[0]['pid']=='147'||$goods_type[0]['pid']=='118')&& $goodArr[0]["fh_type"]=='保税') {
         $tax_rate = 0.119;//保税——母婴、食品、保健品
   }elseif ($goods_type[0]['pid']=='100' && $goodArr[0]["fh_type"]=='直邮' && ($goodArr[0]["gx_id"]== '26' || $goodArr[0]["gx_id"]== '27' || $goodArr[0]["gx_id"]== '34' || $goodArr[0]["gx_id"]== '35' )) {
   	     $tax_rate = 0.60;//直邮——美妆——(眼妆、面部彩妆、化妆卸妆、口红)
   }elseif (($goods_type[0]['pid']=='65'|| $goods_type[0]['pid']=='147'||$goods_type[0]['pid']=='118')&& $goodArr[0]["fh_type"]=='直邮') {
   	     $tax_rate = 0.15;//直邮——母婴、食品、保健品
   }else{
   	     $tax_rate = 0.30;//直邮——美妆——(眼妆、面部彩妆、化妆卸妆、口红)以外的
   }
//是否店铺会员
$dp_user = $do->selectsql("select jointag from sk_member where open_id ='$openid'");
$jointag = $dp_user[0]['jointag'];
//获取店铺会员售价比例
if ($jointag == '2') {
	$dp_price = $do->selectsql("select dp_price from sk_dp_price where id = 1");
    $dp_price = $dp_price[0]['dp_price'];
//店铺会员价格为：商品价格-利润分出*店铺会员售价比例
    $fp_price = round($goodArr[0]["fp_price"]*$dp_price,2);
    $goodArr[0]["price"]-=$fp_price;
    $goodArr[0]["price"]= sprintf("%.2f", $goodArr[0]["price"]);
}


//print_r($fp_price);exit;
$guige = $goodArr[0]["guige"];
//$goods_erweima = $goodArr[0]["goods_erweima"];
$cou_erweima = $do->selectsql("select * from sk_goods_erweima where goods_id='$id' and openid='$openid'");
if(count($cou_erweima)>0)
    $goods_erweima = $goodArr[0]["url"];
else 
    $goods_erweima = "";


$id_goodstype = $goodArr[0]["goodtype"];
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

$good_name = $do -> gohtml($goodArr[0]["good_name"]);

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
	<title><?php echo $goodArr[0]['good_name'];?></title>
	<link rel='stylesheet' type='text/css' href='<?php echo $cxtPath ?>/wei/css/product-intro.css' />
    <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/head.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/foot.css" />
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
	border:2px solid #D4D5D6;background:white;padding:0 5px;margin:-2px 10px 10px 0;display:inline-block;
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

	.lfx_pldiv{ padding:20px 0;}
	.lfx_xq_pl { background:#fff; padding:10px;}
	.lfx_xq_pl li{ border-bottom:1px solid #f4f4f4; margin-top:10px;}
	.lfx_xq_pl li .pl_ming{ width:25%; margin:10px 0 20px 0; float:left; }
	.lfx_xq_pl li .pl_ming .divimg{ width:80%; margin:0 auto 5px; }
	.lfx_xq_pl li .pl_ming .divimg img{ width:100%; }
	.lfx_xq_pl li .pl_ming div{ width:100%; font-size:12px; text-align:center; color:#888;}
	.lfx_xq_pl li .pl_neirong{ width:70%; float:left; }
	.lfx_xq_pl li .pl_neirong .shij{ font-size:10px; color:#888; height:16px; line-height:16px; border-bottom:1px solid #f4f4f4;}
	.lfx_xq_pl li .pl_neirong .shij img{ width:10px; height:10px; background:#fe7182;margin-left:5px;}
	.lfx_xq_pl li .pl_neirong .shij span{ float:right;}
	.lfx_xq_pl li .pl_neirong .neir{ font-size:12px; color:#555;line-height:23px;}

	.jiazai{ width:100%; margin:10 auto; height:35px; line-height:35px; background:#fe7182; text-align:center; color:#fff; font-size:14px;}
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
	        title: "洋仆淘跨境商城——"+"<?php echo $good_name ?>",
        desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|<?php  if($huodong){echo 'hdshangp';}else{echo 'shangp';}?>|<?php echo $id; if($huodong){echo '|ms';}?>&connect_redirect=1#wechat_redirect',
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
	//$("#ECS_GOODS_AMOUNT").text(toDecimal2(parseFloat(price)*parseInt(number)));
}
function toDecimal2(x) {  
    var f = parseFloat(x);  
    if (isNaN(f)) {  
        return false;
    }  
    var f = Math.round(x*100)/100;  
    var s = f.toString();
    var rs = s.indexOf('.');
    if (rs < 0) {
        rs = s.length;  
        s += '.';  
    }
    while (s.length <= rs + 2) {
        s += '0';  
    }
    return s;  
}  
function selGuiGe(obj){
	guige = $(obj).text();
	$(".guisp").css({ "border-color":'#c5c5c5',"color":'#888'});
	$(obj).css({ "border-color":'#ef941e',"color":'#ef941e'});
	/* $(".guisp").removeClass("xuanzhong");
	$(obj).addClass("xuanzhong"); */
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
	var tax_rate = $('#tax_rate').text();
	tax_rate=tax_rate.substring(0,tax_rate.length-1);
	//if(color == ""){
		//alert("请选择颜色");
		//return;
	//}
	$.ajax({
		type: "POST",
		url: "goodsAddCart.php",
		data: "goodsid=<?php echo $id ?>&guige="+guige+"&ms=<?php if($huodong){echo 1;}else{echo 2;}?>&color="+color+"&tax_rate="+tax_rate+"&sul="+$('#number').val(),
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
			}else if(data.success == "3"){
				msgError("该商品不在活动时间段，操作失败");
			}else if(data.success == "4"){
				msgError("该商品库存不足");
			}else{
				msgError("添加购物车失败，请稍后重试");
			}
		}
	});
}

<?php if($id_goodstype==3){?>//积分商品兑换
function duihuan(){
	$.ajax({
		type: "POST",
		url: "goodsAddOrder.php",
		data: "goodsid=<?php echo $id ?>&guige="+guige+"&color="+color+"&sul="+$('#number').val(),
		success: function(data){

		data = eval("("+data+")");
		if(data.success == "1"){
				window.open('../order/order.php?orderid='+data.orderid);
			}else if(data.success == "0"){
				msgError("您的积分不足以兑换该商品");
			}else{
				msgError("兑换失败，请稍后重试");
				}
			}
	});
}
<?php }else{?>
function buy(){
	if(guige == ""){
		alert("请选择规格");
		return;
	}
    var tax_rate = $('#tax_rate').text();
	tax_rate=tax_rate.substring(0,tax_rate.length-1);

	$.ajax({
		type: "POST",
		url: "goodsAddOrder.php",
		data: "goodsid=<?php echo $id ?>&guige="+guige+"&ms=<?php if($huodong){echo 1;}else{echo 2;}?>&color="+color+"&tax_rate="+tax_rate+"&sul="+$('#number').val(),
		success: function(data){
			data = eval("("+data+")");
			if(data.success == "1"){
				//window.open('../order/order.php?orderid='+data.orderid);
				location.href='../order/order.php?orderid='+data.orderid;
			}else if(data.success == "2"){
				msgError("该商品库存不足");
			}else if(data.success == "3") {
				msgError("该商品不在活动时间段，操作失败");
			}else if(data.success == "4"){
				msgError("您的积分不足以兑换该商品");
			}else{
				msgError("下单失败");
			}
		}
	});
}
<?php }?>
$(function(){
	if($('.guisp').length == 1){
		selGuiGe($('.guisp')[0]);
	}
	$('#xx_js').click(function(){
		$('#xuaxiangk li').css('border-color','#fff');
		$(this).css('border-color','#fe7182');
		$('#beixuan li').css('display','none');
		$('#bx_js').show();
		$('#beixuan').show();
		$('#bx_pl').hide();
	})
	$('#xx_cs').click(function(){
		$('#xuaxiangk li').css('border-color','#fff');
		$(this).css('border-color','#fe7182');
		$('#beixuan li').css('display','none');
		$('#bx_cs').show();
		$('#beixuan').show();
		$('#bx_pl').hide();
	})
	$('#xx_pl').click(function(){
		$('#xuaxiangk li').css('border-color','#fff');
		$(this).css('border-color','#fe7182');
		$('#beixuan').css('display','none');
		$('#bx_pl').show();
	})
})

function closeLayer(){
      document.getElementById("show").style.display ="none"; 
   }
   function Layer(){
   	document.getElementById("show").style.display ="block"; 
   }
</script>
<style>
.spxq_top{ color:#888;font-size:14px; width:85%; margin:5px auto 0;line-height:20px;}
.spxq_name{ color:#555;font-size:14px; width:85%; margin:5px auto 0;line-height:20px;}
.spxq_tis{color:#888;font-size:10px; width:85%; margin:5px auto 0;line-height:20px;}
.spxq_miaosu{ color:#555;font-size:10px; width:85%; margin:0 auto;line-height:18px;margin: 10px auto;}
.spxq_jiage{ width:85%; margin:10px auto;}
.spxq_jiage .danjia{ font-size:20px;color:#f15864;display:inline-block;}
.spxq_jiage .yuanjia{ font-size:14px;color:#888;text-decoration:line-through;display:inline-block;margin-left:10px;}
.rigou{ width:100%}
.rigou .houwu{ width:50%;color:#FE7182;background:#F3F3F3;padding:0.5em 0;}
.houwu img{padding-right:0.5em;width:12%;margin-top:-0.2em;}
/*.rigou .soucang{ width:20%}*/
/*.rigou .soucanga{ width:40%}*/
.soucang{position: absolute;left: 70%;top: 90%;}
.fenxiang{position: absolute;left: 85%;top: 90%;}
.rigou .lijigoumai{ width:50%; background:#fe7182; color:#fff;font-size:1.7em;padding:0.5em 0;}

.xuaxiangk{ width:90%; margin:20px auto 5px; height:30px; line-height:30px; font-size:16px;text-align:center;}
.xuaxiangk li{ border-bottom:2px solid #fff; float:left; width:33%;}
.beixuan{clear:both;}
.beixuan li{display:none; width:96%; margin:10px auto;}
.imgwid img{ width:100%;}
.jifensx{font-size:16px; color:#888;}
.tc_tis{      background: #fff;
    position: absolute;
    top: 4%;
    left: 20%;
    padding: 14px 29px;
    border: 1px solid #ccc; 
    list-style: disc;
    font-size: 1.5em;
display: none;}
.twogo{position: absolute;left:70%;right:0;top:1em;bottom:0;}

/*.wofenxiang{color:#fff;background:#fe7182;font-size:1.5em;width:1.5em;padding:0.5em 0.1em 0.5em 1.4em;line-height:1.2em; position:fixed; top:3em;left:-1em;*/
border-radius:4px;
-moz-border-radius:4px;
-webkit-border-radius:4px;
-o-border-radius:4px;
-ms-border-radius:4px;}
</style>

</head>
<body style="margin:0px;background:#fff;position:relative;" >
<div class="show" onclick="closeLayer()">
	<div class="icon">
		<a class="no-collect" href="javascript:collect(150);" id='collect'></a>
	</div>
	<div class="flexslider">
		<ul class="slides">
			<li style="position: relative"><?php if($goodArr[0]['cate_id']==88){?><img class='twogo' style='width:30%' src="<?php echo $cxtPath."/wei/img/88_2_right.png" ?>"/><?php }?><img src="<?php echo $imgurl.$goodArr[0]["img"] ?>" />
			<img src="../../img/shoucang.png" class="soucang" id="soucang" style="width:10%"/>
			<img src="../../img/fenxiang.png" class="fenxiang" id="add_fabu_good" style="width:10%" />
			</li>
		</ul>
	</div>
</div>
<?php if($goodArr[0]["fh_type"]=="直邮"){?>
<div class="spxq_top"><span><?php echo $goodArr[0]["yieldly"] ?>&nbsp&nbsp<?php echo $goodArr[0]["fh_type"] ?>发货</span></div>
<?php }else{?>
	<div class="spxq_top"><span><?php echo $goodArr[0]["yieldly"] ?>&nbsp&nbsp<?php echo $goodArr[0]["fh_type"] ?>区发货</span></div>
<?php }?>
<div class="spxq_name"><?php if($goodArr[0]['cate_id']==88)echo rtrim($goodArr[0]["good_name"],'2罐')."<span><font style='color:red;font-size:1.5em'>2</font>罐</span>"; else echo $goodArr[0]["good_name"] ?></div>

<?php if($id_goodstype==3){?>
	<div class="spxq_jiage"><span class="jifensx">兑换所需积分：</span><span class="danjia"><?php echo $goodArr[0]["dhjifen"] ?></span></div>
<?php }else if($huodong){?>
	<div class="spxq_jiage"><span style="font-size:14px;color:#888;">活动价：</span><span class="danjia">￥<?php echo $goodArr[0]["huodongjia"] ?></span><br /><span class="yuanjia" style="margin-left:0;">专柜价：￥<?php echo $goodArr[0]["orgprice"] ?></span><br /><span style="color:#888; font-size:14px;">打<?php echo $goodArr[0]['zhekou'] ?>折</span></div>
<?php }else if($jointag =="2"){?>
	 <div class="spxq_jiage"><span class="danjia">￥<?php echo $goodArr[0]["price"]?></span><span style="color:#fff;font-size: 1.3em;background: url(../../img/hd_bq.png);padding: 1px 10px;"><?php if($_GET['cate']=='88'){ echo '专享价'; }else{ echo '专享价';} ?></span><span class="yuanjia">市场价：￥<?php echo $goodArr[0]["orgprice"] ?></span></div>
<?php }else{?>
        <div class="spxq_jiage"><span class="danjia">￥<?php echo $goodArr[0]["price"] ?></span><span style="color:#fff;font-size: 1.3em;background: url(../../img/hd_bq.png);padding: 1px 10px;"><?php if($_GET['cate']=='88'){ echo '两罐价'; }else{ echo '活动价';} ?></span><span class="yuanjia">市场价：￥<?php echo $goodArr[0]["orgprice"] ?></span></div>
<?php }?>

<?php if($huodong){?>
	<div class="spxq_top"><span>跨境综合税：<font style="color:#ce8d1b" id="tax_rate"><?php echo round($tax_rate* $goodArr[0]["huodongjia"],2)?>元</font><img src="../../img/jks.png" style="    width: 15px;height: 15px;
margin-left: 5px;" onclick="Layer()"></span>
<?php }else if($goodArr[0]["price"]*$tax_rate<50  && $goodArr[0]["fh_type"] == "直邮"){?>
<div class="spxq_top"><span>行邮税：<font style="color:#ce8d1b" id="tax_rate">0.00元</font><img src="../../img/jks.png" style="    width: 15px;height: 15px;
margin-left: 5px;" onclick="Layer()"></span>
<?php }else if($goodArr[0]["huodongjia"]*$tax_rate<50  && $goodArr[0]["fh_type"] == "直邮" && $huodong){?>
<div class="spxq_top"><span>行邮税：<font style="color:#ce8d1b" id="tax_rate">0.00元</font><img src="../../img/jks.png" style="    width: 15px;height: 15px;
margin-left: 5px;" onclick="Layer()"></span>
<?php }else{?>
	<div class="spxq_top"><span>跨境综合税：<font style="color:#ce8d1b" id="tax_rate"><?php echo round($tax_rate*$goodArr[0]["price"],2)?>元</font><img src="../../img/jks.png" style="    width: 15px;height: 15px;
margin-left: 5px;" onclick="Layer()"></span>
<?php }?>

<span style="    float: right;">通用税率：<?php echo $tax_rate*100 ?>%</span></div>
<div class="spxq_miaosu"><?php echo $goodArr[0]["remark"] ?>
<!--<img src="../../img/ljgd.PNG" style=" height:18px;"/>-->
</div>
<table class="rigou">
	<tr align="center">
	<?php if($id_goodstype==3){?>
		<td class="soucanga" style="background:#c4c1c1;" id="soucang"><img src="<?php echo $cxtPath;?>/wei/img/sc.png" /></td>
		<td class="lijigoumai" onclick="duihuan()" >立即兑换</td>
	<?php }else{?>
		<td class="houwu" onclick="addToCart();"><img src="../../img/gouwuche.png"/><br>加入购物车</td>
<!--		<td class="soucang" style="background:#c4c1c1;" id="soucang"><img src="--><?php //echo $cxtPath;?><!--/wei/img/sc.png" /></td>-->
		<td class="lijigoumai" id="qugoumai" >立即购买</td>
	<?php }?>
	</tr>
</table>
<ul class="tc_tis" onclick="closeLayer()" id="show">
<?php if($goodArr[0]["fh_type"] == "直邮"){?>
<li>洋仆淘<?php echo $goodArr[0]["yieldly"] ?>直邮发货</li>
<?php }else{?>
<li>洋仆淘保税区发货</li>
<?php }?>
<li>此售价未包含跨境电商税</li>
</ul>
<ul class="spxq_tis">
<li>运费：运费10元，两件起包邮</li>
<li>退换货：不支持无理由退换货</li>
<li>税收：按照最新税改征收<?php echo $tax_rate*100 ?>%税率(直邮税收不足50元免税)</li>

</ul>

<ul class="xuaxiangk" id="xuaxiangk">
	<li id="xx_js" style="border-bottom:2px solid #fe7182;">图文详情</li>
	<li id="xx_cs" >商品参数</li>
	<li id="xx_pl" >评价管理</li>
</ul>
<ul class="beixuan" id="beixuan">
	<li id="bx_js"  class="imgwid" style="display:block;">
			<?php echo $goodArr[0]["desc"] ?>
	</li>
	<li id="bx_cs" >
		<div class="pro-info">
			<p class="pro-name">
				<strong style="color:#555;"><?php echo $goodArr[0]["good_name"] ?></strong>
			</p>
			<?php if($id_goodstype==3){?>
				<div class="price clearfix">
					<p class="jx-price bendianjia">所需积分：&nbsp;&nbsp;&nbsp;<strong id="benprice"><?php echo $goodArr[0]["dhjifen"] ?></strong></p>
				</div>
			<?php }else if($huodong){?>
			<div class="price clearfix">
				<p class="jx-price">和众价：&nbsp;&nbsp;&nbsp;<strong id="benprice"><?php echo $goodArr[0]["huodongjia"] ?></strong></p>
			</div>
			<div class="price clearfix">
				<p class="jx-price">折&nbsp;&nbsp;&nbsp;扣：&nbsp;&nbsp;&nbsp;<?php echo $goodArr[0]["zhekou"] ?></p>
			</div>
			
			<div class="price clearfix">
				<p class="jx-price bendianjia">本店价：&nbsp;&nbsp;&nbsp;<?php echo $goodArr[0]["price"] ?></p>
			</div>
			<style>
			.bendianjia{text-decoration:line-through;}
			</style>
			<?php }else{?>
			<div class="price clearfix">
				<p class="jx-price bendianjia">本店价：&nbsp;&nbsp;&nbsp;<strong id="benprice"><?php echo $goodArr[0]["price"] ?></strong></p>
			</div>
			<?php }?>
			<!-- <div class='goods_number clearfix' style="height:auto;">
				<div class="name" style="float:left;width:55px;">规&nbsp;&nbsp;&nbsp;格：</div>
				<div style="float:left;width:70%;">
				<?php
				for($i=0;$i<count($guigeArr);$i++){
					echo "<span onclick='selGuiGe(this)' class='guisp'>".$guigeArr[$i]."</span>";
				}
				?></div>
			</div> -->
			<?php
			/*if(count($colorArr)>0){
				$colorArrtxt = "<div class='goods_number clearfix'>";
				$colorArrtxt += "<p class=\"name\">颜&nbsp;&nbsp;&nbsp;色：</p>";
				for($i=0;$i<count($colorArr);$i++){
					$colorArrtxt += "<span onclick='selColor(this)' class='colorsp'>".$colorArr[$i]."</span>";
				}
				$colorArrtxt += "</div>";
				echo $colorArrtxt;
			}*/
			?>
			<!-- <div class="goods_number clearfix">
				<p class="name">数&nbsp;&nbsp;&nbsp;量：</p>
				<div class="addForm">	
					<input type="button" value="-" class="btn" onClick="chgNum(-1);changePrice();" />	
					<input type="text" id='number' name="number" onblur="changePrice()" value="1" class="text"/>	
					<input type="button" value="+" class="btn" onClick="chgNum(1);changePrice();" />
				</div>
			</div> -->
			<div class='goods_number clearfix'>
				<p class="name">商品二维码：</p><br />
				<img style="display:block;width:50%;margin:0 auto;max-width:280px" id="goods_erweima" src="<?php echo $imgurl.$goods_erweima; ?>" />
			</div>
			<div class="goods_number clearfix">
				<p class="name">生产地：</p>
					<span style="color:#666;"><?php echo $goodArr[0]["yieldly"] ?></span>
			</div>
			<div class="goods_number clearfix">
				<p class="name">保质期：</p>
				<span style="color:#666;"><?php echo $goodArr[0]["period"] ?></span>
			</div>
			<div class="goods_number clearfix">
				<p class="name">规&nbsp;&nbsp;&nbsp;格：</p>
				<span style="color:#666;"><?php echo $goodArr[0]["guige"] ?></span>
			</div>
			<div class="goods_number clearfix">
				<p class="name">库&nbsp;&nbsp;&nbsp;存：</p>
				<span style="color:#666;"><?php echo $goodArr[0]["kucun"] ?></span>
			</div>
			<div class="goods_number clearfix">
				<p class="name">适应人群：</p>
				<span style="color:#666;"><?php echo $goodArr[0]["suit"] ?></span>
			</div>
			<div class="goods_number clearfix">
				<p class="name">功&nbsp;&nbsp;&nbsp;效：</p>
					<span style="color:#666;"><?php echo $goodArr[0]["effect"] ?></span>
			</div>
			
			<!-- <?php if($id_goodstype==3){?>
			<div class='goods_number clearfix'>
				<p class="name">商品总积分：</p>
				<span class="shopcount" id="ECS_GOODS_AMOUNT"><?php echo $goodArr[0]["dhjifen"]; ?></span>
			</div>
			<?php }else{?>
			<div class='goods_number clearfix'>
				<p class="name">商品总价：</p>
				<span class="shopcount" id="ECS_GOODS_AMOUNT"><?php if($huodong){echo $goodArr[0]['huodongjia'];}else{ echo $goodArr[0]["price"];} ?></span>
			</div>
			<?php }?> -->
		</div>
	</li>
</ul>

		<div class="lfx_pldiv" id="bx_pl" style="display:none;">
			<ul class="lfx_xq_pl" id="lfx_xq_pl">
			<!--<li>
				<div class="pl_ming">
					<img src="" />
					<div>名称</div>
				</div>
				<div class="pl_neirong">
					<div class="shij">时间:2015-10-10</div>
					<div class="neir">内容难道说不粗要不行就做不成YVYECHD保护的市场部电话v</div>
				</div>
			</li>-->
			</ul>
		</div>
		<div class="jiazai" id="jiazai">加载更多</div>
<!--		<div class="wofenxiang" id="add_fabu_good">我要分享</div>-->
<!--		<div class="wofenxiang" id="add_fenxiang_good" style="top:9em">添加分享商品</div>-->
<div style="height:80px;"></div>


<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
<script type="text/javascript">
var ympost = 0;
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
	jiazai(ympost);
});
$('#add_fabu_good').click(function(){
	
			window.location.href="../lefenxiang/lfx_bianji1.php?id=<?php echo $id ?>";
	
})
$('#add_fenxiang_good').click(function(){
	$.ajax({
		type: "POST",
		url: "../lefenxiang/lfx_cookie.php",
		data: {add_canshu:'<?php echo $id ?>'},
		success: function(data){
		    alert('已添加分享商品');
		}
	})
})

function jiazai(ymmm){	
	$.ajax({
		type: "POST",
		url: "goods_pl.php",
		data: {pd:'<?php echo $id ?>',ym:ymmm},
		success: function(data){
			if(data==""){
				$('#jiazai').css('display','none');
				$("#lfx_xq_pl").append("<div style='color:#888;font-size:18px;text-align:center;line-style:50px;'>暂无评论</div>");
			}
			var data = eval("("+data+")");
			var appStr = "";
			if(data.length>0){
				for(var i=0;i<data.length;i++){
					ympost++;
					appStr += "<li><div class=\"pl_ming\"><div class=\"divimg\"><img src=\""+data[i].headimgurl +"\" /></div><div>"+data[i].wei_nickname+"</div></div>";
					appStr += "<div class=\"pl_neirong\"><div class=\"shij\">评论时间:"+data[i].shijian+"</div><div class=\"shij\">";
					for(var bi=0;bi<data[i].xingji;bi++){
						appStr += "<img />";
					}
					appStr += "</div><div class=\"neir\">"+data[i].neirong+"</div>";
					appStr += "</div><div style=\"clear:both;height:0px;\"></div></li>";
				}
				$("#lfx_xq_pl").append(appStr);
				if(data.length<20){
					$('#jiazai').css('display','none');
				}
			}else{
				$('#jiazai').css('display','none');
				$("#lfx_xq_pl").append("<div style='color:#888;font-size:18px;text-align:center;line-style:50px;'>暂无评论</div>");
			}
		}
	});
}
$('#jiazai').click(function(){
	jiazai(ympost);
});
if("<?php echo $goods_erweima;?>"==""){
	$.ajax({
		type: "POST",
		url: "goods_erweima.php",
		data: {pd:'<?php echo $id ?>'},
		success: function(data){
			$('#goods_erweima').attr("src","<?php echo $imgurl;?>"+data);
		}
	})
}
$(function(){
	$('#qugoumai').click(function(){
		$.post("goodsAddCart.php",{goodsid:<?php echo $id ?>,sul:1,ck:1},function(data){
			data = eval("("+data+")");
			if(data.success == "1") {
				$('.beijing').show();
				var scrollTop = $(document).scrollTop();//兼容性获取属性
				$('#goumaishuxing').css({"top": parseInt(($(window).height() - $('#goumaishuxing').height()) / 3 + scrollTop) + "px"});
				$('#goumaishuxing').show();
			}else{
				msgError("该商品库存不足");
			}
		});
	})
	$('.beijing').click(function(){
		$('.beijing').hide();
		$('#goumaishuxing').hide();	
	})
});
	$(function(){
		$.ajax({
			type:"POST",
			url:"../action_log.php",
			data:{action:"goods",aid:"<?php echo $id;?>"},
			success:function(data){
			}
		});
	});
</script>
<script src="http://kefu.qycn.com/vclient/state.php?webid=110105" language="javascript" type="text/javascript"></script>

<div class="beijing"></div>
<div class="goumaishuxing" id="goumaishuxing">
    <div class="gmcs_top">
        <div class="gmcs_top_img"><img src="<?php echo $imgurl.$goodArr[0]["img"] ?>" /></div>
        <div class="gmcs_top_txt">
            <div class="gmcs_biaoti"><?php echo $goodArr[0]["good_name"] ?></div>
            <div class="gmcs_jiage">
                <?php if($id_goodstype==3){ echo $goodArr[0]["dhjifen"];}else if($huodong){ echo "￥".$goodArr[0]["huodongjia"]; }else{ echo "￥".$goodArr[0]["price"];} ?>
            </div>
            <div class="gmcs_kucun">库存 <?php echo $goodArr[0]["kucun"] ?> 件</div>
        </div>
        <div style="clear:both;float:none;height:0"></div>
    </div>
    <div class="gmcs_fenlei">分类</div>
    <div class="gmcs_top">
		<?php
		for($i=0;$i<count($guigeArr);$i++){
			echo "<span onclick='selGuiGe(this)' class='guisp'>".$guigeArr[$i]."</span>";
		}?>
        <div style="clear:both;float:none;height:0"></div>
	</div>
	<div class="gmcs_shuliang">
	    <span>购买数量</span>
        <input type="button" value="" class="gmcs_jia" onClick="chgNum(1);changePrice();" />
        <input type="text" id='number' class="number" name="number" onblur="changePrice()" value="1"/>
        <input type="button" value="" class="gmcs_jian" onClick="chgNum(-1);changePrice();" />
	</div>
	<div class="quxiadan" onclick="buy()">去下单</div>
</div>
<style>
		.goumaishuxing{ width:80%;margin:2em auto;padding:1em;background:#fff;position:absolute;top:2em;left:10%;display:none;
border-radius:4px;
-moz-border-radius:4px;
-webkit-border-radius:4px;
-o-border-radius:4px;
-ms-border-radius:4px;
}
		.gmcs_top{ border-bottom:1px solid #f4f4f4;}
		.gmcs_top_img{ float:left;width:28%;}
		.gmcs_top_img img{ width:100%;}
		.gmcs_top_txt{ float:left; width:70%;margin-left:2%;}
		.gmcs_top_txt .gmcs_biaoti{ font-size:1.5em;}
		.gmcs_top_txt .gmcs_jiage{ font-size:2.5em;color:#ec6e7d;}
		.gmcs_top_txt .gmcs_kucun{ font-size:1.5em;color:#c5c5c5;}
		.gmcs_fenlei{ font-size:1.5em; line-hright:2em;text-indent: 0.5em;}
		.gmcs_top span{font-size:1.2em;padding:0.2em 0.3em;margin:0.3em 0 0.3em 0.3em;border:1px solid #c5c5c5; color:#888;
    		border-radius:4px;
            -moz-border-radius:4px;
            -webkit-border-radius:4px;
            -o-border-radius:4px;
            -ms-border-radius:4px}
		.gmcs_shuliang {padding-top:0.5em;}
		.gmcs_shuliang span{font-size:1.5em; margin:0.3em 0 0 1em;line-height:2em;}
		.gmcs_shuliang input{ float:right;}
		.gmcs_shuliang .gmcs_jia{width:3em;height:3em;border:1px solid #dddbdb;background:url("../../img/goodsjia.png") no-repeat center;margin-right:1em;
    		border-radius:0 4px 4px 0;
            -moz-border-radius:0 4px 4px 0;
            -webkit-border-radius:0 4px 4px 0;
            -o-border-radius:0 4px 4px 0;
            -ms-border-radius:0 4px 4px 0}
		.gmcs_shuliang .gmcs_jian{width:3em;height:3em;border:1px solid #dddbdb;background:url("../../img/gouwucejian.png") no-repeat center;
    		border-radius:4px 0 0 4px;
            -moz-border-radius:4px 0 0 4px;
            -webkit-border-radius:4px 0 0 4px;
            -o-border-radius:4px 0 0 4px;
            -ms-border-radius:4px 0 0 4px}
		.gmcs_shuliang .number{width:2em;height:1.5em;text-align:center;border-top:1px solid #dddbdb;border-bottom:1px solid #dddbdb;font-size:2em;background:none;
    		border-radius:0;
            -moz-border-radius:0;
            -webkit-border-radius:0;
            -o-border-radius:0;
            -ms-border-radius:0;}
		.quxiadan{ width:90%;font-size:1.5em;height:2em;line-height:2em;text-align:center;color:#fff;background:#ec6e7d;margin:1em auto;}
		.beijing{ position:fixed;top:0;left:0;right:0;bottom:0;filter:alpha(opacity:70);opacity:0.7;-moz-opacity:0.7;background:#888;display:none;}
		.beijingq{ position:fixed;top:0;left:0;right:0;bottom:0;display:none;}
</style>
</body>
</html>