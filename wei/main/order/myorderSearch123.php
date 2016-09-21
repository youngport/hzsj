<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$status = intval($_POST["xuanzheb"]);
$yema = intval($_POST["postyem"]);
$erma = 0;
if($_POST["dianpu_huiyuan"]==1)
    $erma = 1;


$tj = "";
if($status==0)
	$tj = "";
else if($status == 1)
	$tj = " and status=0 ";
else if($status == 2)
	$tj = " and (status=1 or status=2 or status=3) ";
else if($status == 3)
	$tj = " and status in(6,7,8,9,10) ";
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

//$sql = "select order_id,order_sn,order_time,status,jifendh,shipping_name,invoice_no from sk_orders where buyer_id='$openid' and status not in(99,98) and erm =".$erma." ".$tj." order by add_time desc limit ".$yema.",20";
//是否店铺会员
$dp_user = $do->selectsql("select jointag from sk_member where open_id ='$openid'");
$jointag = $dp_user[0]['jointag'];

$sql = "select order_id,order_sn,order_time,status,jifendh,shipping_name,invoice_no,erm,fb_plun from sk_orders where buyer_id='$openid' and status not in(99,98) and erm in(0,1) ".$tj." order by add_time desc limit ".$yema.",20";
$goodsArr = $do->selectsql($sql);

$json = "";
if(count($goodsArr)>0){
	$json = "[";
	for($i=0;$i<count($goodsArr);$i++){
		$json .= "{\"order_id\":\"".$goodsArr[$i]["order_id"]."\",";
		$json .= "\"order_sn\":\"".$goodsArr[$i]["order_sn"]."\",";
		$json .= "\"order_time\":\"".$goodsArr[$i]["order_time"]."\",";
		$json .= "\"shipping_name\":\"".$goodsArr[$i]["shipping_name"]."\",";
		$json .= "\"invoice_no\":\"".$goodsArr[$i]["invoice_no"]."\",";
		$json .= "\"status\":\"".$goodsArr[$i]["status"]."\",";
		$json .= "\"jifendh\":\"".$goodsArr[$i]["jifendh"]."\",";
		$json .= "\"fb_plun\":\"".$goodsArr[$i]["fb_plun"]."\",";
		$json .= "\"erweima\":\"";
		if($goodsArr[$i]["erm"]==1){
    		$sql = "select jiamengshenhe from sk_dpgou where orderid='".$goodsArr[$i]["order_id"]."'";
    		$spArr = $do->selectsql($sql);
    		if(count($spArr)>0){
    		    $json .= $spArr[0]['jiamengshenhe'];
    		}else{
    		    $json .= 2;
    		}
		}else{
    		    $json .= 3;
    	}
		$json .= "\",\"sangping\":";
		$sql = "select s.goods_id,s.quantity,g.fp_price,s.goods_name,s.price,s.goods_images,s.specification,s.shipping_name,s.invoice_no,g.status from sk_order_goods s join sk_goods g on g.id=s.goods_id where s.order_id='".$goodsArr[$i]["order_id"]."'";
		$spArr = $do->selectsql($sql);
		//var_dump($spArr);exit;
		if(count($spArr)>0){
			$json .= "[";
			for($j=0;$j<count($spArr);$j++){
				$kd=$spArr[$j]["shipping_name"]!=''&&$spArr[$j]["invoice_no"]!=''?1:0;
				$json .= "{\"goods_name\":\"".$do -> gohtml($spArr[$j]["good_name"])."\",";
				$json .= "\"id\":\"".$spArr[$j]["goods_id"]."\",";
				$json .= "\"quantity\":\"".$spArr[$j]["quantity"]."\",";
				if ($jointag == '2') {
				//获取店铺会员售价比例
				$dp_price = $do->selectsql("select dp_price from sk_dp_price where id = 1");
				$dp_price = $dp_price[0]['dp_price'];
				//店铺会员价格为：商品价格-利润分出*店铺会员售价比例
				$fp_price = $spArr[$j]["fp_price"]*$dp_price;
				$spArr[$j]["price"]-=$fp_price;
				$json .= "\"price\":\"".$spArr[$j]["price"]."\",";
}else			
                $json .= "\"price\":\"".$spArr[$j]["price"]."\",";
				$json .= "\"status\":\"".$spArr[$j]["status"]."\",";
				$json .= "\"specification\":\"".$spArr[$j]["specification"]."\",";
				$json .= "\"kd\":\""."$kd"."\",";
				$json .= "\"goods_images\":\"".$spArr[$j]["goods_images"]."\"},";
			}
			$json = substr($json, 0, strlen($json)-1)."]},";
		}else{
			$json .= "[]},";
		}
	}
	$json = substr($json, 0, strlen($json)-1)."]";
}
echo $json;
?>