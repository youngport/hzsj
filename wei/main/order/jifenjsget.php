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
$openid = $_SESSION["openid"];
//$totalPrice = $_POST["totalPrice"];
$orderid = intval($_POST["orderid"]);
$shouname = addslashes($_POST["shouname"]);
$phone = addslashes($_POST["phone"]);
$address = addslashes($_POST["address"]);
$post_code = addslashes($_POST["post_code"]);
$postscript = addslashes($_POST["postscript"]);
$shenfenz = addslashes($_POST["shenfenz"]);
$out_trade_no = substr($openid, 0, 5).time();
$sql="select sum(b.price*b.quantity) totalPrice from sk_orders a left join sk_order_goods b on a.order_id=b.order_id where buyer_id='$openid' and a.order_id=$orderid and a.status=0";
$order=$do->getone($sql);
if(count($order)<1){
	echo 0;
	exit;
}
$sql = "update sk_member set wei_shouname='$shouname',phone='$phone',address='$address',leiorder=leiorder+".$order['totalPrice']." where open_id='$openid'";
$do->dealsql($sql);
$sql = "update sk_orders set buyer_name='$shouname',shenfenz='$shenfenz',order_amount=".$order['totalPrice'].",postscript='$postscript',order_sn='$out_trade_no' where order_id='$orderid'";//修改 购买人，总价，买家附言，订单号
$do->dealsql($sql);
$sql = "insert into sk_order_extm (order_id,phone_tel,address,post_code) values ('$orderid','$phone','$address','$post_code')";//插入地址、联系方式
if($do->dealsql($sql)){//插入订单表


if($orderid){
	$ssql = "select jifendh from sk_orders where order_id='$orderid' and buyer_id='$openid'";
	$goodsArr = $do->selectsql($ssql);
	if($goodsArr[0]['jifendh']==1){
		$ssql = "select g.price from sk_orders o join sk_order_goods g on g.order_id=o.order_id where buyer_id='$openid' and o.order_id=".$orderid;
		$goodsArr = $do->selectsql($ssql);
		$ssql = "select jifen from sk_member where open_id='$openid'";
		$openArr = $do->selectsql($ssql);
		if($openArr[0]['jifen']>=$goodsArr[0]['price']){
			$sql = "update sk_member set jifen=jifen-".$goodsArr[0]['price']." where open_id='$openid'";
			$zongjifen += $goodsArr[0]['price'];
			$do->selectsql($sql);
			$jfsql = "insert into sk_jifen (jifen,openid,laiyuan,shijian)values(".$goodsArr[0]['price'].",'$openid',2,".time().")";
			$do->selectsql($jfsql);
			$sql = "update sk_orders set status=1 where order_id=".$orderid;
			$do->selectsql($sql);
			echo json_encode(array('success'=>1,'order_id'=>$orderid));
		}else{
			echo 0;
		}
	}
}
$name = $do -> selectsql("select wei_nickname from sk_member where open_id = '$openid'");
$weixintxt="亲爱的 ".$name[0]['wei_nickname']."，恭喜您于 ".date('Y-m-d H:i:s',time())." 在洋仆淘成功兑换礼品，剩余积分 ".$zongjifen."，千里之行，始于足下，骚年，继续努力吧！";
$do -> weixintishi($openid,$weixintxt);
$do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$openid."',2)");
//$do -> weixintishi($openid,"恭喜 ".date('Y-m-d H:i:s',time())." 在和众世纪下兑换商品成功，总积分 ".$zongjifen."");
}
?>