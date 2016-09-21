<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$openid=$_SESSION['openid'];
$user=$do->getone("select jointag from sk_member where open_id='$openid'");
if($user['jointag']!=2){
	echo "<script>alert('店铺会员专享!');history.back(-1);</script>";
	exit;
}
$id=intval($_POST['id']);
$orderid=intval($_POST['orderid']);
$order=$do->getone("select o.order_amount,(SELECT count(*) FROM sk_order_goods og inner join sk_goods g on og.goods_id=g.id where og.order_id=o.order_id and is_coupon=0) is_coupon from sk_orders o where o.order_id=$orderid and o.order_amount<=500 and o.buyer_id='$openid' and o.status=0");
$status=1;
if(!empty($order)){
	if($order['is_coupon']==0) {
		if ($id > 0) {
			$time = time();
			$coupon = $do->getone("select count(id) count from sk_mcoupon where id=$id and open_id='$openid' and status=1 and start_time<=$time and end_time>=$time and money>0");
			if ($coupon['count'] == 0) {
				$status = 2;
			}
		}
		if ($status == 1) {
			$do->dealsql("update sk_orders set mcouponid=$id,couponid=0 where order_id=$orderid");
		}
	}else{
		$status=3;
	}
}else{
	$status=0;
}
echo $status;