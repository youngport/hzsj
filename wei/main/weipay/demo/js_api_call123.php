<?php
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
include '../../../base/condbwei.php';
session_start();
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
include_once("../WxPayPubHelper/WxPayPubHelper.php");

//使用jsapi接口
$jsApi = new JsApi_pub();
$do=new condbwei();
//=========步骤1：网页授权获取用户openid============
//通过code获得openid
/*if (!isset($_GET['code']))
{
	//触发微信返回code码
	$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
	Header("Location: $url");
}else
{
	//获取code码，以获取openid
	$code = $_GET['code'];
	$jsApi->setCode($code);
	$openid = $jsApi->getOpenId();
}*/
$open_id = $_SESSION['openid'];
//	$totprice = $_GET["totprice"];
$orderid = intval($_GET['order']);
$is_shouyi = intval($_GET['is_sy']);

$jointag=$do->getone("select jointag from sk_member where open_id='$open_id'");
$shouyi=$do->getone("select is_shouyi from sk_orders where order_id=$orderid");
$sql="select sum(b.price*b.quantity) totalPrice,sum(c.fp_price) fp_price,sum(if(c.goodtype=1 or c.goodtype=2,1,0)) goodtype,sum(if(c.cate_id=88,1,0)) naifen,sum(quantity) count,a.order_sn,sum(if(c.is_coupon=0,1,0)) is_coupon,couponid,mcouponid from sk_orders a left join sk_order_goods b on a.order_id=b.order_id left join sk_goods c on b.goods_id=c.id where buyer_id='$open_id' and a.order_id=$orderid and a.status=0";
$order=$do->getone($sql);
 //print_r($order);exit;
//是否店铺会员

if ($jointag['jointag'] == 2) {
	$dp_price = $do->selectsql("select dp_price from sk_dp_price where id = 1");
    $dp_price = $dp_price[0]['dp_price'];
    $sql="select c.fp_price ,b.quantity from sk_orders a left join sk_order_goods b on a.order_id=b.order_id left join sk_goods c on b.goods_id=c.id where buyer_id='$open_id' and a.order_id=$orderid and a.status=0";
    $fp =$do->selectsql($sql);
//如果是多商品下单，需遍历不同商品利润分配
    foreach ($fp as $key => $value) {
//店铺会员价格为：商品价格-利润分出*比例*数量
    $b['count']+= $fp[$key]['fp_price']*$dp_price*$fp[$key]['quantity'];  
}
//合计
$fp_price = array_sum($b);  
    $order['totalPrice']-=$fp_price;
 }

 //税收
$arr_rate  = $do->selectsql("select tax_rate,quantity from sk_order_goods WHERE order_id = '$orderid'");
//如果是多商品下单，需遍历不同商品的税收

foreach ($arr_rate as $key => $value) {
	//税收*数量
    $a['count']+= $arr_rate[$key]['quantity']*$arr_rate[$key]['tax_rate'];  
}
$tax_rate = array_sum($a);
$order['totalPrice']+=$tax_rate;


if($order['count']<1){
	echo "{'success':'0'}";
	exit;
}
$mc=true;
if($order['is_coupon']==0){
	$time = time();
	if($order['couponid']>0) {
		$couponid = $do->getone("select xz,js from sk_coupon where id=" . $order['couponid'] . " and rec='$open_id' and status in(1,3) and start_time<=$time and end_time>=$time");
		if (!empty($couponid)) {
			if ($order['totalPrice'] >= $couponid['xz'] && $couponid['xz'] >= $couponid['js']) {
				$order['totalPrice'] -= $couponid['js'];
			}
		}
	}elseif($jointag['jointag']==2&&$order['mcouponid']>0){
		$mcouponid = $do->getone("select money from sk_mcoupon where id=" . $order['mcouponid'] . " and open_id='$open_id' and status=1 and start_time<=$time and end_time>=$time");
		if($mcouponid['money']>0) {
			$order['totalPrice'] -= $mcouponid['money'];
			if ($order['totalPrice'] <= 0)
				$order['totalPrice'] = 0.1;
			$mc = false;
		}
	}
}
//echo $order['totalPrice'];exit;
//是否收益抵扣
if($is_shouyi== 1){
	 $do->dealsql("update sk_orders set is_shouyi =1 where order_id=".$orderid);
if($mc&&!($order['totalPrice']>=299||$order['count']>1||$order['goodtype']>0||$order['naifen']>0)){
	    //这里订单价还没判断是否包邮，如果不包邮，收益前价格+10入库
		$sql = "update sk_orders set syq_price =".($order['totalPrice']+10)." where order_id='$orderid'";
		$do->dealsql($sql); 
}else{
	     //如果包邮则不加10
	     //回调时，个人收益减去点击收益抵扣前的订单价格。
		 $sql = "update sk_orders set syq_price =".$order['totalPrice']." where order_id='$orderid'";
		 $do->dealsql($sql); 
}
	$s_price = $do->getone("select shouyi from sk_member where open_id='$open_id'");
	if ($s_price['shouyi'] > 0 ) {	
	          $js=$order['totalPrice'];	
		$order['totalPrice'] -= $s_price['shouyi'];
		if ($order['totalPrice'] <= 0) {
				$order['totalPrice'] = 0.1;
			}else{
				$js=$js-$order['totalPrice'];
		}			
	} 
}else{
	$do->dealsql("update sk_orders set is_shouyi =0 where order_id=".$orderid);
}

if ($is_shouyi== 1 && $order['totalPrice'] ==0.1) {
	$order['totalPrice']+=0;
}elseif($mc&&!($order['totalPrice']>=299||$order['count']>1||$order['goodtype']>0||$order['naifen']>0)){
	$order['totalPrice']+=10;
}

             //结算后，才减库存
            $sql = "select k.goods_id,k.quantity,g.kucun from sk_orders o left join sk_order_goods k on k.order_id=o.order_id left join sk_goods g on g.id=k.goods_id where o.order_sn='".$order["order_sn"]."'";
			//echo $sql;exit;
			$kuarr = $do->selectsql($sql);
			//print_r($kuarr);exit;
			foreach ($kuarr as $key => $value) {
				$kc[$key]['kucun'] =  $kuarr[$key]['kucun'] - $kuarr[$key]['quantity'];
				
				$sql = "update sk_goods set kucun =".$kc[$key]['kucun']." where id =".$kuarr[$key]['goods_id'];
				
				$do->dealsql($sql);
				
			}
$totprice=$order['totalPrice'];
//echo $totprice;exit;
$access_token = $_SESSION["access_token"];
//=========步骤2：使用统一支付接口，获取prepay_id============
//使用统一支付接口
$unifiedOrder = new UnifiedOrder_pub();

//设置统一支付接口参数
//设置必填参数
//appid已填,商户无需重复填写
//mch_id已填,商户无需重复填写
//noncestr已填,商户无需重复填写
//spbill_create_ip已填,商户无需重复填写
//sign已填,商户无需重复填写
$unifiedOrder->setParameter("openid","$open_id");//商品描述
$unifiedOrder->setParameter("body","购买商品");//商品描述
//自定义订单号，此处仅作举例
$timeStamp = time();
$out_trade_no = $order["order_sn"];
$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号
//$totprice = 0.1;
$totprice = floatval($totprice) * 100;
$unifiedOrder->setParameter("total_fee",$totprice);//总金额
$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址
$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
//非必填参数，商户可根据实际情况选填
//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
//$unifiedOrder->setParameter("device_info","XXXX");//设备号
//$unifiedOrder->setParameter("attach", substr($access_token,127));//附加数据
//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
//$unifiedOrder->setParameter("openid","XXXX");//用户标识
//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

$prepay_id = $unifiedOrder->getPrepayId();
//=========步骤3：使用jsapi调起支付============
$jsApi->setPrepayId($prepay_id);

$jsApiParameters = $jsApi->getParameters();
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>微信安全支付</title>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript">
		//调用微信JS api 支付
		function jsApiCall(){
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					//alert(res.err_msg);
					if(res.err_msg == "get_brand_wcpay_request:ok"){
						if("<?php if($order['goodtype']>0){echo 1;$_SESSION['confirm']=1;}else{ echo 0;}?>">0)
							location.href='../../members/confirm.php';
						/*else
							window.location.replace("../../order/shenfenz.php?order=<?php echo $orderid ?>");*/
					}else{
						window.location.replace("../../order/myorder.php?type=0");
					}
				}
			);
		}
		function callpay(){
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
		callpay();
	</script>
</head>
</html>