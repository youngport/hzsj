<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$goodsid = intval($_POST["id"]);
function getIPaddress()
{
	$IPaddress='';
	if (isset($_SERVER)){
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
			$IPaddress = $_SERVER["HTTP_CLIENT_IP"];
		} else {
			$IPaddress = $_SERVER["REMOTE_ADDR"];
		}
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR")){
			$IPaddress = getenv("HTTP_X_FORWARDED_FOR");
		} else if (getenv("HTTP_CLIENT_IP")) {
			$IPaddress = getenv("HTTP_CLIENT_IP");
		} else {
			$IPaddress = getenv("REMOTE_ADDR");
		}
	}
	return $IPaddress;
}
//--------------------获取地址
function getip(){
$ip=file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.getIPaddress());
$ip=json_decode($ip,true);
return $ip['data']['region'].$ip['data']['city'];
}

$sql = "select kucun from sk_goods where id=$goodsid ";
$kucun = $do->getone($sql);
//print_r($kucun );exit;
/*if ($kucun['kucun']<0) {
	$status['status']=4;
}*/
$sql="select jointag from sk_member where open_id='$openid'";
$jointag=$do->getone($sql);
if($jointag['jointag']!=0 ){
	$status['status']=2;
}elseif($kucun['kucun']<=0){
	$status['status']=4;
}
else {
	$time = time();
	$goodsArr = $do->getone("select g.id,g.good_name,g.guige,g.img,g.pcode,ug.price from sk_user_goods ug inner join sk_goods g on g.id=ug.gid where ug.gid=$goodsid and ug.status=1 and ug.start_time<=$time and ug.end_time>=$time");
	$curtime = date('YmdHis', time()).mt_rand(1000.9999);
	$status = array('status' => 1);
	if (!empty($goodsArr)) {
		$sql = "insert into sk_orders (order_sn,buyer_id,status,order_time,add_time,ipdz,erm,jifendh,is_member,goods_amount,order_amount) values ('$curtime','$openid','0','" . date('Y-m-d H:i:s', time()) . "'," . time() . ",'" . getip() . "',0,0,1," . $goodsArr['price'] . "," . ($goodsArr['price'] + 20) . ")";
		if ($do->dealsql($sql)) {
			$order_id=$do->getone("select order_id from sk_orders where order_sn='$curtime' and status=0");
			$order_id=$order_id['order_id'];
			$status['order_id']=$order_id;
			$sql = "insert into sk_order_goods (order_id,goods_id,goods_name,specification,price,quantity,goods_images,goods_code) values ('$order_id','" . $goodsArr["id"] . "','" . addslashes($goodsArr['good_name']) . "','" . $goodsArr['guige'] . "','" . $goodsArr['price'] . "','1','" . $goodsArr['img'] . "','" . $goodsArr['pcode'] . "')";//插入订单商品表
			$do->dealsql($sql);
			$sql = "update sk_goods set kucun = kucun - 1 where id='" . $goodsArr["id"] . "'";//商品库存减去
			$do->dealsql($sql);
		} else {
			$status['status'] = 0;
		}
	} else {
		$status['status'] = 0;
	}
}
echo $status['status'];exit;
echo json_encode($status);
?>