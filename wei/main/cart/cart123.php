<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

$do = new condbwei();
$com = new commonFun();

$openid = $_SESSION["openid"];
$cartnum = $_SESSION["cartnum"];
$subscribe = $_SESSION["subscribe"];

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl = 'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

$typeo = $_SESSION["typeo"];//获取是否是从店家led产品二维码进入

/* $sql = "select g.goodtype type from wei_cart w left join sk_goods g on g.id=w.goodsid 
		where w.openid='$openid'";
$typea = 0;
$typeb = 0;
$tuijianfang_typeo = 0;

$resultArr = $do->selectsql($sql);

for($i = 0;$i < count($resultArr);$i++){
	if($resultArr[$i]["type"] == 0 || $resultArr[$i]["type"] == 1){
		$typea = 1;
	}else if($resultArr[$i]["type"] == 2){//LED产品隐藏店家付款或推广付款
	$tuijianfang_typeo = 1;
		$typeb = 1;
	}
} */
$sql = "SELECT wei_cart.cart_id FROM wei_cart join sk_goods on wei_cart.goodsid=sk_goods.id where sk_goods.cate_id = 88 and wei_cart.openid='$openid'";
$naifen_youfei = $do->selectsql($sql);
$naifen_true = false;
if(count($naifen_youfei)>0){
    $naifen_true = true;
}
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
	<title>购物车</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/shopping-cart.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
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
var totalPrice = 0;
var cartnum = "<?php echo $cartnum ?>";
var shuliang = 0;

var mianyou = false;
<?php if($naifen_true){?>;
	mianyou = true;
<?php }?>;
$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
	$.ajax({
		type: "POST",
		url: "cartSearch123.php",
		data: "",
		success: function(data){
			data = eval("("+data+")");
			if(parseInt(data.total)>0){
				$("#kongcart").css("display", "none");
				$(".gw_qujiesuan").css("display", "block");
				$("#totalgoods").css("display", "block");
				$("#goodslist").css("display", "block");
				$("#account").css("display", "block");
				var root = data.root;
				$("#goodsNum").text(root.length);
				appStr = "";
				for(var i=0;i<root.length;i++){
					var price = root[i].price;
					var hdasrc = "";
					if(root[i].danjia==1){
						price = root[i].huodongjia;
						hdasrc = "ms=1&";
					}
					if(root[i].danjia==2){
						if(root[i].tuangoujia>0)
							price = root[i].tuangoujia;
						else
							price = 0;
					}
					if(root[i].danjia==3)
						price = root[i].shangoujia;
					var buynum = root[i].buynum;

					
					appStr += "<tr align=\"center\">";
					appStr += "<td class=\"gw_lied\"><img class=\"xuanz\" data-x=\"1\" data-guoqi="+root[i].spzt+" data-sl="+root[i].buynum+" data-price="+price+" data-i=\""+root[i].cart_id+"\" src='<?php echo $imgurl;?>wei/img/gw_xuanz2.png' /></td>";
					appStr += "<td class=\"gw_tup\"><a href='<?php echo $cxtPath ?>/wei/main/goods/goods.php?"+hdasrc+"id="+root[i].goodsid+"'><img class='goodsimg' datagoods='"+root[i].img+"' src='<?php echo $imgurl;?>"+root[i].img+"'></a></td>";
					appStr += "<td class=\"gw_text\" align=\"left\" valign=\"top\" style=\"position:relative;\">";
					appStr += "<div class=\"gw_text_name\"><a class='good_name' href='<?php echo $cxtPath ?>/wei/main/goods/goods.php?"+hdasrc+"id="+root[i].goodsid+"'>"+root[i].good_name+"</a><div>";
					appStr += "<div class=\"gw_text_jiajian\"><span>￥"+price+"<span class=\"xiangyuba\">";
					
					appStr += "<div class=\"gw_text_jian\"><input ";
					if(root[i].spzt==1){
						appStr += "onclick=\"red_numq(this, '"+price+"',"+i+")\"";
					};
					appStr += " class='edit' type='button'></div>";
					appStr += "<div class=\"gw_text_text\"><input class='number' type='text' value='"+root[i].buynum+"' readonly=\"true\"></div>";
					appStr += "<div class=\"gw_text_jia\"><input ";
					if(root[i].spzt==1&&root[i].danjia!=2){
						appStr += "onclick=\"add_num(this, '"+price+"',"+i+")\"";
					};
					appStr += " class='edit' type='button' ></div></span><div style=\"clear:both;height:0;\"></div><div>";

					if(root[i].spzt==0)
						appStr += "<div style=\"border:1px solid #900;background:#fff;color:#900;text-align:center;width:70px;height:25px;position:absolute;top:30px;right:30px;line-height:25px;\">已过期</div>";
					if(root[i].is_kucun==0)
						appStr += "<div style=\"border:1px solid #900;background:#fff;color:#900;text-align:center;width:70px;height:25px;position:absolute;top:30px;right:30px;line-height:25px;\">库存不足</div>";
					appStr += "</td>";
					appStr += "</tr>";
					if(root[i].spzt==1){
						shuliang += root[i].buynum;
						totalPrice += parseFloat(price)*parseInt(buynum);
					};
					$("#goodsul").append(appStr);
					appStr = "";
				}

				if(shuliang>=2||totalPrice>=299||mianyou==true){
					$("#cart_amount_desc").text(toDecimal2(totalPrice));
					$('#yunfei').html(' ');
				}else{
					$("#cart_amount_desc").text(toDecimal2(totalPrice+10));
				}
				//$("#cart_amount_desc").text(toDecimal2(totalPrice));
			}else{
				$("#kongcart").css("display", "block");
				$(".gw_qujiesuan").css("display", "none");

				$.ajax({
					type: "POST",
					url: "caini_xihuan.php",
					data: {},
					success: function(data){
						var data = eval("("+data+")");
						data = data.root;
						var appStr = "";
						if(data.length>0){
							var ding = data.length;//循环多少次
							appStr = "<div class='card card-list'><div class=\"col3\">";
							for(var i=0;i < ding;i++){
								if(i%2==0)
									appStr += "<div>";
								appStr += "<div class=\"row1\"><div><a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?id="+data[i].id+"\"><span class=\"imgurl\"><img data-original=\"<?php echo $imgurl ?>"+data[i].img+"\" style=\"display: inline;\" src=\"<?php echo $imgurl ?>"+data[i].img+"\"></span><span class=\"p\"><span>"+data[i].good_name+"</span></span></a></div></div>";

								if(i == ding - 1)
									appStr += "</div><div style='clear:both;height:0px;'>";
								else if(i%2==1)
									appStr += "</div><div style='clear:both;height:0px;'>";
							}
							//appStr += "</div></div></div>";
							$("#viewport").empty();
							$("#viewport").append(appStr);
						}
					}
				});
			}
			//选中click
			$('.xuanz').click(function(){
				if($(this).attr('data-x')==1){
					$(this).attr('data-x',0);
					$(this).attr('src','<?php echo $imgurl;?>wei/img/gw_xuanz1.png');
				}else if($(this).attr('data-x')==0){
					$(this).attr('data-x',1);
					$(this).attr('src','<?php echo $imgurl;?>wei/img/gw_xuanz2.png');
				}
				xuan_zongjia();
			})
		}
	});
});
function red_numq(obj, price,paih){
	var $buyDom = $(obj).parent().next().children().eq(0);
	buynum = parseInt($buyDom.val());
	buynum = buynum - 1;
	if(buynum<=0){
		buynum = 1;
		return;
	}/*else
		shuliang--;*/
	$buyDom.val(buynum);
	$('.xuanz').eq(paih).attr('data-sl',buynum);
	xuan_zongjia()
	/* totalPrice = totalPrice - parseFloat(price);
	if(shuliang>=2||toDecimal2(totalPrice)>=299||mianyou==2){
		$("#cart_amount_desc").text(toDecimal2(totalPrice));
		$('#yunfei').html(' ');
	}else{
		$("#cart_amount_desc").text(toDecimal2(totalPrice+10));
		$('#yunfei').html('（含运费：￥10.00）');
	} */
	//$("#cart_amount_desc").text(toDecimal2(totalPrice));
}
function add_num(obj, price,paih){
	var $buyDom = $(obj).parent().prev().children().eq(0);
	buynum = parseInt($buyDom.val());
	buynum = buynum + 1;
	//shuliang++;
	$buyDom.val(buynum);
	$('.xuanz').eq(paih).attr('data-sl',buynum);
	xuan_zongjia()
	/* totalPrice = totalPrice + parseFloat(price);
	if(shuliang>=2||toDecimal2(totalPrice)>=299||mianyou==2){
		$("#cart_amount_desc").text(toDecimal2(totalPrice));
		$('#yunfei').html(' ');
	}else{
		$("#cart_amount_desc").text(toDecimal2(totalPrice+10));
		$('#yunfei').html('（含运费：￥10.00）');
	} */
	//$("#cart_amount_desc").text( toDecimal2(totalPrice));
}

function xuan_zongjia(){//选中商品总价
	var gos = $('.xuanz');
	totalPrice = 0;
	shuliang = 0;
	for(var i=0;i<gos.length;i++)
	{
		if($(gos[i]).attr('data-x')==1&&$(gos[i]).attr('data-guoqi')!=0){
			totalPrice += parseFloat($(gos[i]).attr('data-price')) * $(gos[i]).attr('data-sl');
			shuliang += $(gos[i]).attr('data-sl');
		}
	}
	if(totalPrice==0){
		$("#cart_amount_desc").text("0.00");
		$('#yunfei').html(' ');
	}else if(shuliang>=2||toDecimal2(totalPrice)>=299||mianyou==true){
		$("#cart_amount_desc").text(toDecimal2(totalPrice));
		$('#yunfei').html(' ');
	}else{
		$("#cart_amount_desc").text(toDecimal2(totalPrice+10));
		$('#yunfei').html('（含运费：￥10.00）');
	}
	//$("#cart_amount_desc").text(toDecimal2(totalPrice));
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
function deleteCart(goodsid){
	$.ajax({
		type: "POST",
		url: "cartDelete.php",
		data: "type="+goodsid,
		success: function(data){
			data = eval("("+data+")");
			if(data.success == "1"){
				location.reload();
			}
		}
	});
}
function cleanCart(){
	var gos = $('.xuanz');
	var gos_json = {};
	var json_i = 0;
	for(var i=0;i<gos.length;i++)
	{
		if($(gos[i]).attr('data-x')==1){
	    	gos_json[json_i]=$(gos[i]).attr('data-i');
	    	json_i++;
		}
	}
	$.ajax({
		type: "POST",
		url: "cartDelete.php",
		data: gos_json,
		dataType: 'json',
		success: function(data){
			if(data.success == "1"){
				location.reload();
			}
		}
	});
}
function dealOrder(){

	/*var buynum = "";
	$(".number").each(function(){
		buynum += $(this).val() + "☆";
	});
	if(buynum.length>0) buynum = buynum.substring(0, buynum.length-1);*/
	var good_name = "";
	$(".good_name").each(function(){
		good_name += $(this).text() + "☆";
	});
	if(good_name.length>0) good_name = good_name.substring(0, good_name.length-1);
	var price = "";
	$(".pricedan").each(function(){
		price += $(this).text() + "☆";
	});
	if(price.length>0) price = price.substring(0, price.length-1);
	var goodsimg = "";
	$(".goodsimg").each(function(){
		goodsimg += $(this).attr("datagoods") + "☆";
	});
	if(goodsimg.length>0) goodsimg = goodsimg.substring(0, goodsimg.length-1);

	var cart_idtext = "";
	var buynum = "";
	var gos = $('.xuanz');
	for(var i=0;i<gos.length;i++)
	{
		if($(gos[i]).attr('data-x')==1){
			cart_idtext += $(gos[i]).attr('data-i') + "☆";
			buynum += $(gos[i]).attr('data-sl') + "☆";
		}
	}
	if(buynum.length>0) buynum = buynum.substring(0, buynum.length-1);
	$.ajax({
		type: "POST",
		url: "cartOrder123.php",
		data: "buynum="+buynum+"&cart_idtext="+cart_idtext+"&good_name="+good_name+"&price="+price+"&goodsimg="+goodsimg+"&jiez="+$('#jiez input[name="field＿name"]:checked ').val(),
		success: function(data){
			data = eval("("+data+")");
			if(data.success == "1"){
				location.replace('../order/order123.php?orderid='+data.orderid);
			}else if(data.success == "2"){
				alert("商品库存不足");
				location.replace('../order/order123.php?orderid='+data.orderid);
			}else if(data.success == "3"){
				alert("请选中购物车中商品");
			}
		}
	});
}
$(function(){
	$('#gw_g_quanxuan').click(function(){
		//$('.xuanz').attr('data-x',1);
		//$('.xuanz').attr('src','<?php echo $imgurl;?>wei/img/gw_xuanz2.png');
		if($(this).attr('data-xuan')==1){
			$(this).attr('data-xuan',0);
			$(this).find('img').attr('src','<?php echo $imgurl;?>wei/img/gw_xuanz2.png');
			$('.xuanz').attr('data-x',1);
			$('.xuanz').attr('src','<?php echo $imgurl;?>wei/img/gw_xuanz2.png');
		}else{
			$(this).attr('data-xuan',1);
			$(this).find('img').attr('src','<?php echo $imgurl;?>wei/img/gw_xuanz1.png');
			$('.xuanz').attr('data-x',0);
			$('.xuanz').attr('src','<?php echo $imgurl;?>wei/img/gw_xuanz1.png');
		}
		xuan_zongjia();
	})
})
</script>
<style>
.tishi{ height:130px; line-height:210px; font-size:16px; color:#555; text-align:center;}
.quguangg{ height:35px; width:130px; margin:10px auto; line-height:35px; font-size:14px; text-align:center;border:1px solid #ccc;color:#555;}

.gw_top{ height:40px; width:98%;margin:0 auto; border-bottom:1px solid #f4f4f4;}
.gw_top .gw_fah{ height:40px; width:35px;float:left; padding:8px 0 0 8px;}
.gw_top .gw_fah img{ width:25px;}
.gw_top .gw_fah_text{float:left;font-size:16px;line-height:40px;color:#555;}
.gw_top .gw_laj{ float:right; padding: 8px 8px 0 0;}
.gw_top .gw_laj img{ width:25px;}

.gw_table{ width:98%;margin:0 auto;}
.gw_table tr{ border-bottom:1px solid #f4f4f4;}
.gw_lied{ width:10%;}
.gw_lied img{ width:25px;}
.gw_tup{ width:25%; padding:10px 0; }
.gw_tup img{ width:100%;}
.gw_text{ width:65%; padding:10px;}
.gw_text .gw_text_name{ font-size:14px; line-height:20px;}
.gw_text .gw_text_name a:link{ color:#555;}
.gw_text .gw_text_name a:hover{ color:#555;}
.gw_text .gw_text_name a:visited{ color:#555;}
.gw_text .gw_text_jiajian{ margin-top:40px;}
.gw_text .gw_text_jiajian .gw_text_jian{ float:left;}
.gw_text .gw_text_jiajian .gw_text_jian input{ float:right; width:35px;height:35px; background:#fff; border:none; color:#fe7182; font-size:16px;}
.gw_text .gw_text_jiajian .gw_text_jia{ float:left;}
.gw_text .gw_text_jiajian .gw_text_jia input{ float:right; width:35px;height:35px; background:#fff; border:none; color:#fe7182; font-size:16px;}
.gw_text .gw_text_jiajian .gw_text_text{ float:left;}
.gw_text .gw_text_jiajian .gw_text_text input{ float:right; width:35px;height:20px; background:#fff; border:none; text-align:center;}
.gw_text .gw_text_jiajian .xiangyuba{ float:right;}

.gw_tishi{ width:98%; margin:10px auto; font-size:10px;color:#888; line-height:20px;}
.gw_tishi img{ width:14px;margin-right:5px;margin-left:15px;}
.gw_qujiesuan{ position:fixed; height:45px; border-top:1px solid #ebebeb; bottom:50px;left:0;right:0; z-index:500;background:#fff; display:none;}
.gw_qujiesuan .zuo{ float:left; width:50px; font-size:12px;margin:10px 5px 0 10px;}
.gw_qujiesuan .zuo img{ width:25px;}
.gw_qujiesuan .zhong{ float:left; font-size:10px; margin-top:5px}
.gw_qujiesuan .zhong .heji{ font-size:14px;}
.gw_qujiesuan .you{ float:right; margin:4px 20px 0 0;}
.gw_qujiesuan .you img{ width:80px;}


	
.viewport{width:100%;}
.viewport .row1{ float:left; width:50%; }
.viewport .row1 div{ width:100%;border-left:1px solid #f4f4f4; }
.viewport img{ display:block; width:90%;margin:0.3em auto;}
.viewport span{ display:block; }
.viewport .p{ font-size:1.2em;line-height:1.2em; height:2.3em; width:94%;margin:0 auto; text-align:center; overflow:hidden;}
.viewport a:link{ color:#555; }
.viewport a:hover{ color:#555; }
.viewport a:visited{ color:#555; }


.gw_text .gw_text_jiajian .gw_text_jian{ width:1.9em; height:1.7em; 
border-radius:50% 2px 2px 50%;
-moz-border-radius:50% 2px 2px 50%;
-webkit-border-radius:50% 2px 2px 50%;
-o-border-radius:50% 2px 2px 50%;
-ms-border-radius:50% 2px 2px 50%;}
.gw_text .gw_text_jiajian .gw_text_jian input{ border:1px solid #b2b2b2; width:100%; height:100%; background:url("../../img/gouwucejian.png") no-repeat 60% 50%;
border-radius:50% 2px 2px 50%;
-moz-border-radius:50% 2px 2px 50%;
-webkit-border-radius:50% 2px 2px 50%;
-o-border-radius:50% 2px 2px 50%;
-ms-border-radius:50% 2px 2px 50%;}
.gw_text .gw_text_jiajian .gw_text_jia{ width:1.9em; height:1.7em; background:#f76669;
border-radius: 2px 50% 50% 2px;
-moz-border-radius:2px 50% 50% 2px;
-webkit-border-radius:2px 50% 50% 2px;
-o-border-radius:2px 50% 50% 2px;
-ms-border-radius:2px 50% 50% 2px;}
.gw_text .gw_text_jiajian .gw_text_jia input{ width:100%; height:100%; background:#f76669;background:url("../../img/gouwucejia.png") no-repeat 30% 50%;
border-radius: 2px 50% 50% 2px;
-moz-border-radius:2px 50% 50% 2px;
-webkit-border-radius:2px 50% 50% 2px;
-o-border-radius:2px 50% 50% 2px;
-ms-border-radius:2px 50% 50% 2px;}
.gw_text .gw_text_jiajian .gw_text_text input{ height:1.5em; color:#f76669;}

input[type="submit"],
input[type="reset"],
input[type="button"],
input[type="text"],
button {
    -webkit-appearance: none;
}
</style>
</head>
<body style="margin:0px;background:#EBEBEB">
<div class="shopping-cart" id="ds_gal">
	<div id="kongcart" class="tips_view mitu_01" style="display:none;">
		<div class="tishi" id="opop" data-id="dataid">购物车还是空的现在就去选购吧</div>
		<a href="../../index.php" ><div class="quguangg">去逛逛</div></a>
		<div style="border-bottom:1px solid #f4f4f4; width:100%;margin:1em 0;"></div>
		<div id="viewport" class="viewport">
		
        </div>
	</div>
	<!--顶部begin-->

		<div class="gw_top"  id="totalgoods" style="display:none;">
			<div class="gw_fah"><img src='<?php echo $imgurl;?>wei/img/gw_xuanz2.png' /></div>
			<div class="gw_fah_text">洋仆淘发货</div>
			<div class="gw_laj"><a href="javascript:cleanCart()" class="delete-all" style="font-size:14px;"><img src='<?php echo $imgurl;?>wei/img/gw_laj.png' /></a></div>
		</div>
		<div style="clear:both;height:0;"></div>
	<!--顶部end-->
	<!--商品列表begin-->
	<div id="goodslist" class="list" style="display:none;">
	<table class="gw_table"  id="goodsul"></table>
	<div class="gw_tishi">
		<div><img src='<?php echo $imgurl;?>wei/img/gw_tis.png' />正品保证，假一赔十</div>
		<div><img src='<?php echo $imgurl;?>wei/img/gw_tis.png' />洋仆淘发货商品满2件或299包邮</div>
	</div>
		<!-- <ul id="goodsul">
			<li class="clearfix first">
				<div class="container clearfix">
					<div class="show clearfix">
						<a href="goods.php?id=135"><img src="../images/thumb_url_2352058646415_sp.jpg"></a>
					</div>
					<div class="info">
						<p class="name"><a href="goods.php?id=135">【集果网】 泰国进口山竹 99元/4斤 沈阳精品水果</a></p>
						<p class="price">本店价<strong>99.00</strong></p>
						<div class="num num-edit clearfix">
							<p>数&nbsp;&nbsp;&nbsp;量</p>
							<div><input onclick="red_num(7993,135);" class="edit" type="button" value="-"></div>
							<div><input class="number" type="text" name="goods_number[7993]" id="goods_number_7993" value="1" onblur="change_price(7993,135)"></div>
							<div><input onclick="add_num(7993,135)" class="edit" type="button" value="+"></div>
						</div>
						<input type="hidden" name="rowid" value="a239d26ca5967a553b6db6489b378e17">
					</div>
				</div>
				<a href="cart.php?act=drop_goods&amp;id=7993" class="trash"></a>
			</li>
		</ul> -->
	</div>
	<!--商品列表end-->
	<!--结算begin-->
	<div id="account" class="account" style="display:none;">
		<!-- <div class="delete clearfix"><a href="javascript:cleanCart()" class="delete-all" style="font-size:14px;"><i></i>清空购物车</a></div>
			<div class="total">
				<div class="final">
					<p>实付金额：<strong id="cart_amount_desc">0.00</strong></p>
				</div>
			</div> -->
            <?php if($typeo == 2 && $_SESSION["state"] != $openid && $tuijianfang_typeo == 0){?>
			<div id="jiez" class='price' style='font-size:12px;padding-bottom:10px;'>付款人：<input type="radio" name="field＿name" checked value="2" >店家&nbsp;&nbsp;<input type="radio" name="field＿name"  value="1" >推广</div>
            <?php }?>
			<!-- <div class="buy">
				<input type="button" value="立即下单" style="border:0px;" onclick="dealOrder()">
			</div> -->
		</div>
	</div>
<div style="height:80px;"></div>
	<!--结算end-->
	<div class="gw_qujiesuan">
		<div class="zuo" id="gw_g_quanxuan" data-xuan = 1><img src='<?php echo $imgurl;?>wei/img/gw_xuanz2.png' />全选</div>
		<div class="zhong">
			<div class="heji">合计：<strong id="cart_amount_desc">0.00</strong></div>
			<div id="yunfei">（含运费：￥10.00）</div>
		</div>
		<div class="you" onclick="dealOrder()"><img src='<?php echo $imgurl;?>wei/img/gw_jies.png' /></div>
	</div>
<?php include '../gony/nav.php';?>
<?php include '../gony/guanzhu.php';?>
<?php include './main/gony/returntop.php';?>
</body>
</html>