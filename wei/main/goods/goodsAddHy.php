<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$goodsid = intval($_POST["goodsid"]);//ID
//$guige = $_POST["guige"];//规格
//$sul = $_POST["sul"];//数量
//$color = $_POST["color"];//颜色
$cartnum = $_SESSION["cartnum"];


$ssql = "select jointag from sk_member where open_id='$openid'";
$HuiyuanArr = $do->selectsql($ssql);
if($HuiyuanArr[0]['jointag']==0){
	$ssql = "select price,id,good_name,img,guige,goodtype,kucun,pcode from sk_goods where id=".$goodsid;
	$goodsArr = $do->selectsql($ssql);
	if($goodsArr[0]['goodtype']==1){
	    if($goodsArr[0]['kucun'] > 0){		
			$curtime = date('YmdHis',time()).mt_rand(1000,9999);
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

			$sql="insert into sk_orders (order_sn,buyer_id,status,order_time,add_time,ipdz,erm,goods_amount,order_amount) values ('$curtime','$openid','0','".date('Y-m-d H:i:s',time())."',".time().",'".getip()."',2,".$goodsArr[0]['price'].",".$goodsArr[0]['price'].")";
			$do->dealsql($sql);//插入订单表
			$order_id=$do->getone("select order_id from sk_orders where order_sn='$curtime' and buyer_id='$openid' and status=0");
			$order_id=$order_id['order_id'];
			$sql = "insert into sk_order_goods (order_id,goods_id,goods_name,specification,price,quantity,goods_images,goods_code) values ('$order_id','".$goodsArr[0]["id"]."','".$goodsArr[0]['good_name']."','".$goodsArr[0]['guige']."','".$goodsArr[0]['price']."','1','".$goodsArr[0]['img']."','".$goodsArr[0]['pcode']."')";//插入订单商品表
			$do->dealsql($sql);
			
			/*$sql = "update sk_goods set kucun = kucun - 1 where id='".$goodsArr[0]["id"]."'";//商品库存减去
			$do->dealsql($sql);*/
			echo "{\"success\":\"1\",\"orderid\":\"$order_id\"}";
	    }else 
	       echo "{\"success\":\"2\"}";
	        
	}
}else
	echo "{\"success\":\"0\"}";
?>