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
$subscribe = $_SESSION["subscribe"];
$order_id = intval($_GET["order_id"]);//订单id

$out_trade_no = substr($openid, 0, 5).time();

$do = new condbwei();
$com = new commonFun();


$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl =  'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);


$sql = "select jiezhangren,erm,status,jifendh,order_amount from sk_orders where order_id='$order_id'";
$resultArr = $do->selectsql($sql);

$dianpushenhe = true;
if($resultArr[0]['erm'] == 1){
    $dianpushenhe = false;
    $sql = "select jiamengshenhe from sk_dpgou where orderid='$order_id'";
    $spArr = $do->selectsql($sql);
    if($spArr[0]['jiamengshenhe'] == 1)
        $dianpushenhe = true;
}

$jifen = false;//用来标注是否是积分订单 false不是 true是
if($resultArr[0]['jifendh']==1)//是否积分兑换商品 1是
    $jifen = true;
$bbqa = false;//用来标注该订单是否是自己付的款 false是   true不是
if($resultArr[0]['jiezhangren'] != 0)//不是自己付款的显示上传付款单-----0是自己付款 1推广付款 2是店家付款
	$bbqa = true;
$fx="select o.status,d.goodtype,o.fb_lfxiang,o.fb_plun,(select zhuangtai from sk_kuaidi where name = o.shipping_name and danhao = o.invoice_no) zhuangtai from sk_orders o join sk_order_goods g on o.order_id=g.order_id join sk_goods d on g.goods_id=d.id where o.order_id='$order_id'";
$fxArr = $do->selectsql($fx);
/* $fenx = false;//用来判断是否要显示乐分享按键 false不显示  true显示
if(count($fxArr)==1&&$fxArr[0]['status']==4&&($fxArr[0]['goodtype']==0||$fxArr[0]['goodtype']==3)&&$fxArr[0]['fb_lfxiang']==0)
	$fenx = true; */
$pltrue= false;//用来判断是否显示评论按钮 false不显示 true显示
if($fxArr[0]['status']==4&&$fxArr[0]['fb_plun']==0)
	$pltrue= true;
$sql = "SELECT sk_order_goods.rec_id FROM sk_order_goods join sk_goods on sk_order_goods.goods_id=sk_goods.id WHERE sk_order_goods.order_id = '$order_id' and sk_goods.cate_id = 88";
$naifen_youfei = $do->selectsql($sql);
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
	<title>支付单详情</title>
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
<script type="text/javascript">
wx.config({
    appId: '<?php echo $shareCheckArr["appid"];?>',
    timestamp: '<?php echo $shareCheckArr["timestamp"];?>',
    nonceStr: '<?php echo $shareCheckArr["noncestr"];?>',
    signature: '<?php echo $shareCheckArr["signature"];?>',
    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage'] // 功能列表，我们要使用JS-SDK的什么功能
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
var bian_sn="";
var order_sn = "";
$(function(){
	$.ajax({
		type: "POST",
		url: "orderdetSearch.php",
		data: "order_id="+<?php echo $order_id ?>,
		success: function(data){
			//$("#buyer_name").text(data);
			//a= "{\"address\":\"广东省深圳市宝安区那里608\"}";
			//data = eval("("+a+")")
			//alert(data);return;
			data = eval("("+data+")");
			var payment_code = data.payment_code;
			if(payment_code.length>0){
				$("#payment_code").text(payment_code);
				$("#paytype").text("微信支付");
			}
			$("#buyer_name").text(data.buyer_name);
			$("#phone_tel").text(data.phone_tel);
			$("#address").text(data.address);
			$("#order_sn").text(data.order_sn);
			bian_sn = data.order_sn;
			order_sn = data.order_sn;
			var status = data.status;
			if(status == "0"){
				$("#status").text("未支付");
			}else if(status == "1" || status == "2" || status == "3"){
				$("#status").text("已支付");
				$("#paybut").remove();
				$("#invoicediv").css("display", "block");
				$("#invoice_no").text(data.invoice_no);
				if('<?php echo $fxArr[0]['zhuangtai'];?>'=='3')
				    $("#confirm").css("display", "block");
			}
			$("#add_time").text(data.add_time);
			$("#add_time").text(data.add_time);
			var good = data.good;
			var totprice = 0;
			var zint = 0//总共有多少件商品
			var hyjia = false;//有没有会员加盟的商品

			var goods_expired = false;
			for(var i=0;i<good.length;i++){
				var price = good[i].price;
				var quantity = good[i].quantity;
				var appstr = "<div onclick=\"goodsdet('"+good[i].goods_id+"')\" style='width:100%;height:110px;padding-top:10px;border-bottom:1px solid #DEDEDE;'>";
				appstr += "<img src='<?php echo $imgurl ?>"+good[i].goods_images+"' style='margin-left:10px;float:left;width:100px;height:100px;'></img>";
				appstr += "<div style='margin-left:10px;width:auto;float:left;font-size:16px;'>";
				appstr += "<p style='font-weight:bold;font-size:18px;width:12em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'>"+good[i].goods_name+"</p>";
				appstr += "<p style='color:#FB0000'><?php if($jifen){ echo '积分：';}else{echo '￥';}?>"+price+"</p>";
				appstr += "<p>×"+quantity+"</p>";
				appstr += "</div></div>";

				totprice += parseFloat(price)*parseInt(quantity);
				$("#goodsdiv").append(appstr);
				zint += quantity;
				if(good[i].goodtype == 1|| good[i].goodtype== 2)
					hyjia = true;
				if(good[i].status==1)
					goods_expired = true;
			}
			/* if(!goods_expired)
				$('#paybut').hide(); */
			$("#totpricetit").text(toDecimal2(totprice));
			<?php if($jifen){?>
			    $("#totprice").text(toDecimal2(totprice));
			<?php }else{?>
			    var naifen_youfei=<?php echo count($naifen_youfei);?>;
    			if(zint >= 2 || totprice >= 299 || hyjia == true ||naifen_youfei>0){
    			    $("#totprice").text(toDecimal2(totprice));
    			}else{
    			    $("#totprice").text(toDecimal2(totprice+10));
    			    $("#yunfei").text("运费：10.00");
    			}
			<?php }?>
		}
	});
})
function goodsdet(goods_id){
	location.replace("<?php echo $cxtPath ?>/wei/main/goods/goods.php?id="+goods_id);	
}
function pay(){
	var totprice = $("#totprice1").text();
	$.ajax({
		type: "POST",
		url: "orderdetPay.php",
		data: "order_id=<?php echo $order_id ?>&out_trade_no=<?php echo $out_trade_no ?>",
		success: function(data){
			data = eval("("+data+")");
			if(data.success == "1"){
				location.replace("../weipay/demo/js_api_call.php?open_id=<?php echo $openid ?>&out_trade_no=<?php echo $out_trade_no ?>&totprice="+totprice);
			}else{
				msgError("购买失败，请稍后重试");
			}
		}
	});
}
function confirm(){
	$.ajax({
		type: "POST",
		url: "orderConfirm.php",
		data: "order_id=<?php echo $order_id ?>&order_sn="+order_sn,
		success: function(data){
			data = eval("("+data+")");
			msgSuccessUrl("确认收货成功", "<?php echo $cxtPath ?>/wei/main/order/myorder.php?type=0");
		}
	});
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
</script>
</head>
<body style="background:#EBEBEB;">
<div class="header_03" style="background:white">
	<div class="back">
		<a href="../../index.php" class="arrow"></a>
	</div>
	<div class="tit">支付单详情</div>
	<div class="nav">
		<ul>
			<li class="cart"><a href="../cart/cart.php">购物车</a><span style="display:none" id="ShoppingCartNum"></span></li>
		</ul>
	</div>
</div>
<div style="position:absolute;top:6em;width:96%;height:60em;background:white;margin-left:2%;border:1px solid #DDDDDD">
	<div style="width:100%;height:auto;background:white;">
		<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
			<font style="font-size:16px;color:#535758;"><strong>支付单信息</strong></font>
		</div>
		<span class="userinfsp">支付单号：<font id="payment_code"></font></span><br/>
		<span class="userinfsp">支付类型：<font id="paytype"></font></span>
	</div>
	<div style="margin-top:2%;width:100%;height:auto;background:white;">
		<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
			<font style="font-size:16px;color:#535758;"><strong>收货信息</strong></font>
		</div>
		<span class="userinfsp">收货人：<font id="buyer_name"></font></span><br/>
		<span class="userinfsp">手机：<font id="phone_tel"></font></span><br/>
		<span class="userinfsp">收货地址：<font id="address"></font></span>
	</div>
	<div id="invoicediv" style="margin-top:2%;width:100%;height:auto;background:white;display:none;">
		<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
			<font style="font-size:16px;color:#535758;"><strong>物流信息</strong></font>
		</div>
		<span class="userinfsp">发货单号：<font id="invoice_no"></font></span><br/>
	</div>
	<div style="margin-top:2%;width:100%;height:auto;background:white;">
		<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
			<font style="font-size:16px;color:#535758;"><strong>订单信息</strong></font>
		</div>
		<span class="userinfsp">订单编号：<font id="order_sn"></font></span><br/>
		<span class="userinfsp">订单状态：<font id="status"></font></span><br/>
		<span class="userinfsp">下单时间：<font id="add_time"></font></span><br/>
		<span class="userinfsp">商品总<?php if($jifen){echo '积分';}else{echo '金额';}?>：<font id="totpricetit"></font></span><br/>
		<span class="userinfsp" id="yunfei">运费：包邮</span>
	</div>
	<div id="goodsdiv" style="margin-top:2%;width:100%;height:auto;background:white;">
		<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
			<font style="font-size:16px;color:#535758;"><strong>商品信息</strong></font>
		</div>
		<!-- <div style="width:100%;height:110px;padding-top:10px;border-bottom:1px solid #DEDEDE;">
			<img src="../../images/150_P_1416178879815.jpg" style="margin-left:10px;float:left;width:100px;height:100px;"></img>
			<div style="margin-left:10px;width:auto;float:left;font-size:16px;">
				<p style="font-weight:bold;font-size:18px;">商品名称商品名称商品名称</p>
				<p style="color:#FB0000">￥23.43</p>
				<p>×2</p>
			</div>
		</div> -->
	</div>
	<?php if($resultArr[0]['erm'] == 1 && $resultArr[0]['status'] == 4){
		$ersql = "select bianhao from sk_erweima where dingdan='$order_id'";
		$erArr = $do->selectsql($ersql);
		$ermtext = "<div style=\"margin-top:2%;width:100%;height:auto;background:white;\"><div class=\"userxiao\" style=\"height:30px;line-height:30px;background:#F3F3F3\"><font style=\"font-size:16px;color:#535758;\"><strong>二维码编号</strong></font></div>";
		for($i=0;$i<count($erArr);$i++){
			$ermtext .="<span class=\"userinfsp\">".$erArr[$i]['bianhao']."</span><br/>";
		}
		echo $ermtext."</div>";
	}
	?>
	<?php if($bbqa){?>
    <form action="inget.php?id=<?php echo $order_id ?>" method="post" enctype="multipart/form-data" >
	<div style="margin-top:2%;width:100%;height:auto;background:white; margin:10px 0;">
		<span class="userinfsp">上传付款账单：<font id="shangchuan"><input type="file" name="file" id="file" /><input type="submit" name="submit" value="确认上传" /></font></span><br/>
	</div>
	</form>
    <?php }?>
	<div style="width:100%;padding-top:10px;font-size:18px;"><strong><?php if($jifen){echo '实付积分';}else{echo '实付金额';}?>：</strong><font  id="totprice1" style="color:#FB0000;font-weight:bold;"><?php echo $resultArr[0]['order_amount'] ?></font></div>
	<?php //if($fenx){?>
		<!-- <a href="../lefenxiang/lefenxiang_bj.php?fenxiang=<?php echo $order_id ?>"><div id="paybut" onclick="lefenxiang()" class="userinfsub">乐分享</div></a> -->
	<?php //}?>
	<?php if($pltrue){?>
		<a href="sppl.php?sppl=<?php echo $order_id ?>"><div id="paybut" class="userinfsub">评论</div></a>
	<?php }elseif($fxArr[0]['status']==0 && $dianpushenhe){?>
	<div id="paybut"  onclick="window.open('../order/order.php?orderid=<?php echo $order_id;?>');" class="userinfsub">微信支付</div>
	<?php }elseif($fxArr[0]['status']==1||$fxArr[0]['status']==2||$fxArr[0]['status']==3){?>
	<div onclick="window.open('../order/thhuo.php?orderid=<?php echo $order_id;?>&bian='+bian_sn);" class="userinfsub" style="display:block;">申请退换货</div>
	<?php }; ?>
	<div id="confirm" onclick="confirm()" class="userinfsub" style="display:none;">确认收货</div>
	<br/>
</div>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>