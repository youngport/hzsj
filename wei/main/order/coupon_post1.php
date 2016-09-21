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
$id=intval($_POST['id']);
$orderid=intval($_POST['orderid']);
$order=$do->getone("select o.order_amount,(SELECT count(*) FROM sk_order_goods og inner join sk_goods g on og.goods_id=g.id where og.order_id=o.order_id and is_coupon=0) is_coupon from sk_orders o where o.order_id=$orderid and o.buyer_id='$openid' and o.status=0");

$gslist=$do->selectsql("select g.id,g.cate_id  from sk_order_goods og inner join sk_goods g on g.id=og.goods_id where order_id=$orderid");
foreach($gslist as $v){
	$cates=$v['cate_id'].',';
	$goods=$v['id'].",";
}
$cates=trim($cates,",");
$goods=trim($goods,",");
$status=1;
if(!empty($order)){
	if($order['is_coupon']==0) {
		if ($id > 0) {
			$time = time();
			$coupon = $do->getone("select count(id) count from sk_coupon where id=$id and rec='$openid' and status in (1,3) and (type=0 or (type=1 and tid in ($cates)) or (type=2 and tid in ($goods))) and start_time<=$time and end_time>=$time and xz<=" . $order['order_amount']);
			if ($coupon['count'] == 0) {
				$status = 2;
			}
		}
		if ($status == 1) {
			$do->dealsql("update sk_orders set couponid=$id,mcouponid=0,discount=0 where order_id=$orderid");
		}
	}else{
		$status=3;
	}
}else{
	$status=0;
}
echo $status;