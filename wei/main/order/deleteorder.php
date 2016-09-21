<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
if($_POST['dia']){
	$order_id = intval($_POST['dia']);
	$sql = "select * from sk_orders where order_id='$order_id' and buyer_id='$openid'";
	$countArr = $do->selectsql($sql);
	if(count($countArr)>0){
		$sql = "select quantity,goods_id from sk_order_goods where order_id='$order_id'";
		$gsArr = $do->selectsql($sql);
		if(count($gsArr)>0){
			for($i=0;$i<count($gsArr);$i++){
				$sql = "update sk_goods set kucun = kucun + ".$gsArr[$i]["quantity"]." where id='".$gsArr[$i]["goods_id"]."'";
				$do->selectsql($sql);
			}
		}
		//$sql = "delete from sk_order_goods where order_id='$order_id'";
		//$do->selectsql($sql);
		$sql = "update sk_orders set status = 98 where order_id = '$order_id' and status=0";
		$do->selectsql($sql);
		echo 1;
	}
}


/*$condition = "where o.buyer_id='$openid' ";
if($type!=""){
	if($type == "1"){
		$condition .= "and (o.status='1' or o.status='2' or o.status='3')";
	}else{
		$condition .= "and o.status='$type'";
	}
}
$json = "";
$sql = "select o.order_id,o.order_sn,o.payment_code,o.status,o.order_time,sum(w.quantity*g.price) as price,sum(w.quantity) as quantity,g.img from sk_orders o  
		left join sk_order_goods w on o.order_id=w.order_id  
		left join sk_goods g on g.id=w.goods_id $condition group by o.order_id order by o.add_time desc";
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "order_id,order_sn,payment_code,status,order_time,price,quantity,img");
echo $json;*/

/*$sql = "select order_id,order_sn,order_time,status from sk_orders where buyer_id='$openid' and status not in(-99,-98) ".$tj." order by add_time desc limit ".$yema.",20";
$goodsArr = $do->selectsql($sql);
$json = "";
if(count($goodsArr)>0){
	$json = "[";
	for($i=0;$i<count($goodsArr);$i++){
		$json .= "{\"order_id\":\"".$goodsArr[$i]["order_id"]."\",";
		$json .= "\"order_sn\":\"".$goodsArr[$i]["order_sn"]."\",";
		$json .= "\"order_time\":\"".$goodsArr[$i]["order_time"]."\",";
		$json .= "\"status\":\"".$goodsArr[$i]["status"]."\",";
		$json .= "\"sangping\":";
		$sql = "select quantity,goods_name,price,goods_images,specification from sk_order_goods where order_id='".$goodsArr[$i]["order_id"]."'";
		$spArr = $do->selectsql($sql);
		if(count($spArr)>0){
			$json .= "[";
			for($j=0;$j<count($spArr);$j++){
				$json .= "{\"goods_name\":\"".$spArr[$j]["goods_name"]."\",";
				$json .= "\"quantity\":\"".$spArr[$j]["quantity"]."\",";
				$json .= "\"price\":\"".$spArr[$j]["price"]."\",";
				$json .= "\"specification\":\"".$spArr[$j]["specification"]."\",";
				$json .= "\"goods_images\":\"".$spArr[$j]["goods_images"]."\"},";
			}
			$json = substr($json, 0, strlen($json)-1)."]},";
		}else{
			$json .= "[]},";
		}
	}
	$json = substr($json, 0, strlen($json)-1)."]";
}
echo $json;*/
?>