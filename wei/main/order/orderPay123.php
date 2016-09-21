<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$totalPrice =$_POST["totalPrice"]+0;
$orderid = intval($_POST["orderid"]);
$shouname = addslashes($_POST["shouname"]);
$phone = addslashes($_POST["phone"]);
$address = addslashes($_POST["address"]);
$post_code = addslashes($_POST["post_code"]);
$postscript = addslashes($_POST["postscript"]);
$out_trade_no = substr($openid, 0, 5).time();
$shouyi = addslashes($_POST["shouyi"]);
//插入身份证和姓名
$shenfenz = addslashes($_POST["shenfenz"]);
$name = addslashes($_POST["name"]);
$sql = "update sk_orders set shenfenz = '$shenfenz', buyer_name = '$name' where order_id='$orderid'";
$do->dealsql($sql);
$sql = "update sk_member set wei_shousfz = '$shenfenz' where open_id='$openid'";
$do->dealsql($sql);

$jointag=$do->getone("select jointag from sk_member where open_id='$openid'");
//$shenfenz = $_POST["shenfenz"];
$sql="select sum(b.price*b.quantity) totalPrice,sum(if(c.goodtype=1 or c.goodtype=2,1,0)) goodtype,sum(if(c.cate_id=88,1,0)) naifen,sum(quantity) count,a.order_sn,sum(if(c.is_coupon=0,1,0)) is_coupon,couponid,mcouponid,is_member,b.goods_expired as goods_expired from sk_orders a left join sk_order_goods b on a.order_id=b.order_id left join sk_goods c on b.goods_id=c.id where buyer_id='$openid' and a.order_id=$orderid and a.status=0";
$order=$do->getone($sql);
//是否店铺会员
if ($jointag['jointag'] == 2) {
	$dp_price = $do->selectsql("select dp_price from sk_dp_price where id = 1");
    $dp_price = $dp_price[0]['dp_price'];
    $sql="select c.fp_price ,b.quantity from sk_orders a left join sk_order_goods b on a.order_id=b.order_id left join sk_goods c on b.goods_id=c.id where buyer_id='$openid' and a.order_id=$orderid and a.status=0";
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
//优惠券，现金券
$order['gtotalPrice']=$order['totalPrice'];
$mc=true;
if($order['is_coupon']==0){
	$time = time();
	if($order['couponid']>0) {
		$couponid = $do->getone("select xz,js from sk_coupon where id=" . $order['couponid'] . " and rec='$openid' and status in(1,3) and start_time<=$time and end_time>=$time");
		if (!empty($couponid)) {
			if ($order['totalPrice'] >= $couponid['xz'] && $couponid['xz'] >= $couponid['js']) {
				$order['totalPrice'] -= $couponid['js'];
			}
		}
	}elseif($jointag['jointag']==2&&$order['mcouponid']>0){
		$mcouponid = $do->getone("select money from sk_mcoupon where id=" . $order['mcouponid'] . " and open_id='$openid' and status=1 and start_time<=$time and end_time>=$time");
		if($mcouponid['money']>0) {
			$js=$order['totalPrice'];
			$order['totalPrice'] -= $mcouponid['money'];
			if ($order['totalPrice'] <= 0) {
				$order['totalPrice'] = 0.1;
			}else{
				$js=$js-$order['totalPrice'];
			}
			$do->dealsql("update sk_orders set discount=$js where order_id=".$orderid);
			$mc = false;
		}
	}
}
//收益抵扣
$sy = 0;
if($shouyi== '1'){
         $sy = 1;
	$s_price = $do->getone("select shouyi from sk_member where open_id='$openid'");
	if ($s_price['shouyi'] > 0 ) {	
	          $js=$order['totalPrice'];	
		$order['totalPrice'] -= $s_price['shouyi'];
		if ($order['totalPrice'] <= 0) {
				$order['totalPrice'] = 0.1;
			}else{
				$js=$js-$order['totalPrice'];
			}			
	}
}
//收益抵扣，如果结算只需0.1，证明连邮费也算了。
if ($shouyi== '1' && $order['totalPrice'] ==0.1 ) {
	$order['totalPrice']+=0;
}elseif($order['is_member']==1 || $order['goods_expired']==2){
	$order['totalPrice']+=0;
}elseif($mc&&!($order['totalPrice']>=299||$order['count']>1||$order['goodtype']>0||$order['naifen']>0)){
	$order['totalPrice']+=10;
}
echo $order['totalPrice'];exit;
$sql = "update sk_member set wei_shouname='$shouname' ,phone='$phone',address='$address',post_code='$post_code',leiorder=leiorder+".$order['totalPrice']." where open_id='$openid'";
$do->dealsql($sql);
$sql = "update sk_orders set buyer_name='$shouname',goods_amount=".$order['gtotalPrice'].",order_amount=".$order['totalPrice'].",postscript='$postscript',order_sn='$out_trade_no' where order_id='$orderid'";//修改 购买人，总价，买家附言，订单号
$do->dealsql($sql);
$sql = "insert into sk_order_extm (order_id,phone_tel,address,post_code) values ('$orderid','$phone','$address','$post_code')";//插入地址、联系方式
$do->dealsql($sql);
echo json_encode(array('success'=>1,'order_id'=>$orderid,'is_shouyi'=>$sy));
/*$i = 1;
$shangopenid = $_SESSION["pid"];
while($i<=9){
	$resultArr = selPat($shangopenid, $do);//获取上级的基本信息
	if(count($resultArr)==0) break;
	$shangopenid = $resultArr[0]["pid"];
	$baseprice = floatval($totalPrice) * 0.4;
	$fenprice = 0;
	if($i == 1){
		$fenprice = $baseprice * 0.8 * 0.45;
	}else if($i == 2){
		$fenprice = $baseprice * 0.8 * 0.1;
	}else if($i == 3){
		$fenprice = $baseprice * 0.8 * 0.1;
	}else if($i == 4){
		$fenprice = $baseprice * 0.8 * 0.08;
	}else if($i == 5){
		$fenprice = $baseprice * 0.8 * 0.06;
	}else if($i == 6){
		$fenprice = $baseprice * 0.8 * 0.06;
	}else if($i == 7){
		$fenprice = $baseprice * 0.8 * 0.05;
	}else if($i == 8){
		$fenprice = $baseprice * 0.8 * 0.05;
	}else if($i == 9){
		$fenprice = $baseprice * 0.8 * 0.05;
	}
	$pop = intval($resultArr[0]["pop"]);
	$leiorder = floatval($resultArr[0]["leiorder"]);
	if($leiorder>=99){
		$fenprice += $baseprice * 0.2 * 0.05;
	}else if($leiorder>=699 && $pop>=20){
		$fenprice += $baseprice * 0.2 * 0.1;
	}else if($leiorder>=799 && $pop>=50){
		$fenprice += $baseprice * 0.2 * 0.2;
	}else if($leiorder>=899 && $pop>=80){
		$fenprice += $baseprice * 0.2 * 0.3;
	}else if($leiorder>=999 && $pop>=80){
		$fenprice += $baseprice * 0.2 * 0.35;
	}
	$sql = "insert into wei_pop (openid,pop,poptime) values ('".$resultArr[0]["open_id"]."','$fenprice' ,now())";
	$do->dealsql($sql);
	$i++;
}
function selPat($openid, $do){
	$sql = "select * from sk_member where open_id='$openid'";
	$resultArr = $do->selectsql($sql);
	return $resultArr;
}*/
//echo "{'success':'1','order_id'}";
?>