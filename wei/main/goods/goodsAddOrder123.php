<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$goodsid = intval($_POST["goodsid"]);//ID
$cartnum = $_SESSION["cartnum"];
$guige = isset($_POST['guige'])?addslashes($_POST["guige"]):'';//规格
$sul = isset($_POST['sul'])?intval($_POST["sul"]):1;//数量
//$color = isset($_POST['color'])?addslashes($_POST["color"]):'';//颜色
$tax_rate = addslashes($_POST["tax_rate"]);//税收
//echo $tax_rate ;exit;
$jifendh=0;
$goods_expired=0;

$ssql = "select dhjifen,id,good_name,img,guige,fp_price,goodtype,pcode,price,huodongjia,kucun from sk_goods where id=".$goodsid;
$goodsArr = $do->selectsql($ssql);
if($sul>$goodsArr[0]['kucun']){
	echo "{\"success\":\"2\"}";
	exit;
}

/*//是否店铺会员
$dp_user = $do->selectsql("select jointag from sk_member where open_id ='$openid'");
$jointag = $dp_user[0]['jointag'];
//获取店铺会员售价比例
if ($jointag == '2') {
	$dp_price = $do->selectsql("select dp_price from sk_dp_price where id = 1");
    $dp_price = $dp_price[0]['dp_price'];
//店铺会员价格为：商品价格-利润分出*店铺会员售价比例
    $fp_price = $goodsArr[0]["fp_price"]*$dp_price;
    $goodsArr[0]["price"]-=$fp_price;
}*/
if($goodsArr[0]['goodtype']==0){
	if($_POST['ms']==1){
		$sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 1 and canyuid=(select cate_id from sk_goods where id='$goodsid'))";
		$LArr = $do->selectsql($sql);
		$sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 2 and canyuid=(select mp_id from sk_goods where id='$goodsid'))";
		$MArr = $do->selectsql($sql);
		$sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 3 and canyuid=(select gx_id from sk_goods where id='$goodsid'))";
		$GArr = $do->selectsql($sql);
		if($LArr[0]['cou'] > 0||$MArr[0]['cou'] > 0||$GArr[0]['cou'] > 0){
			$goodsArr[0]['price']=$goodsArr[0]['huodongjia'];
			$goods_expired=1;
		}else{
			echo "{\"success\":\"3\"}";
			exit;
		}
	}
	$goodsArr[0]['guige']=$guige;
}elseif($goodsArr[0]['goodtype']==3){
	$ssql = "select jifen from sk_member where open_id='$openid'";
	$openArr = $do->selectsql($ssql);
	
	if($openArr[0]['jifen']>=$goodsArr[0]['dhjifen']){
		$goodsArr[0]['price']=$goodsArr[0]['dhjifen'];
		$jifendh=1;
		$goods_expired=4;
	}else{
		echo "{\"success\":\"4\"}";
		exit;
	}
}else{
	echo "{\"success\":\"0\"}";
	exit;
}
$curtime = date('YmdHis',time()).mt_rand(1000,9999);

$do->dealsql("insert into sk_orders (order_sn,buyer_id,status,order_time,add_time,ipdz,erm,jifendh,goods_amount,order_amount) values ('$curtime','$openid','0','".date('Y-m-d H:i:s',time())."',".time().",'".getip()."',0,$jifendh,".$goodsArr[0]['price']*$sul.",".$goodsArr[0]['price']*$sul.")");//插入订单表
$order_id=$do->getone("select order_id from sk_orders where order_sn='$curtime' and buyer_id='$openid' and status=0");
$order_id=$order_id['order_id'];
$sql = "insert into sk_order_goods (order_id,goods_id,goods_name,specification,price,tax_rate,quantity,goods_images,goods_code,goods_expired) values ('$order_id','".$goodsArr[0]["id"]."','".addslashes($goodsArr[0]['good_name'])."','".$goodsArr[0]['guige']."','".$goodsArr[0]['price']."','$tax_rate','$sul','".$goodsArr[0]['img']."','".$goodsArr[0]['pcode']."',$goods_expired)";//插入订单商品表
$do->dealsql($sql);
$sql = "update sk_goods set kucun = kucun - $sul where id='".$goodsArr[0]["id"]."'";//商品库存减去
$do->dealsql($sql);
echo "{\"success\":\"1\",\"orderid\":\"$order_id\"}";
//----------------------获取本机IP
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
?>