<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$order_id = intval($_POST["order_id"]);

$sql = "select o.order_sn,o.buyer_name,o.status,o.order_time,o.payment_code,o.invoice_no,s.address,s.phone_tel,o.jifendh from sk_orders o 
		left join sk_order_extm s on s.order_id = o.order_id where o.order_id='$order_id'";
$resultArr = $do->selectsql($sql);
$json = "";
if(count($resultArr)>0){
	$json = "{";
	for($i=0;$i<count($resultArr);$i++){
		$json .= "\"order_sn\":\"".$resultArr[0]["order_sn"]."\",";
		$json .= "\"buyer_name\":\"".$resultArr[0]["buyer_name"]."\",";
		$json .= "\"address\":\"".trim((string)$resultArr[0]["address"])." \","; //送货地址 在录入地址时有猫腻
		$json .= "\"status\":\"".$resultArr[0]["status"]."\","; 
		$json .= "\"add_time\":\"".$resultArr[0]["order_time"]."\",";
		$json .= "\"payment_code\":\"".$resultArr[0]["payment_code"]."\",";
		$json .= "\"jifendh\":\"".$resultArr[0]["jifendh"]."\",";
		$json .= "\"invoice_no\":\"".$resultArr[0]["invoice_no"]."\",";
		$json .= "\"phone_tel\":\"".$resultArr[0]["phone_tel"]."\",good:[";
		$sql = "select s.goods_id,s.goods_name,s.goods_images,s.price,s.quantity,k.goodtype,s.goods_expired,k.status from sk_order_goods s join sk_goods k on k.id=s.goods_id where order_id='$order_id'";
		$goodsArr = $do->selectsql($sql);
		for($j=0;$j<count($goodsArr);$j++){
			$json .= "{\"goods_id\":\"".$goodsArr[$j]["goods_id"]."\",";
			$json .= "\"goods_images\":\"".$goodsArr[$j]["goods_images"]."\",";
			$json .= "\"goodtype\":\"".$goodsArr[$j]["goodtype"]."\",";
			$json .= "\"goods_name\":\"".$goodsArr[$j]["goods_name"]."\",";
			$json .= "\"price\":\"".$goodsArr[$j]["price"]."\",";
			
			if($goodsArr[$j]["status"]==1){
			    if($goodsArr[$j]['goods_expired']==1){//活动商品，活动是否已结束
			        $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < now() and hd_jieshu > now() and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 1 and canyuid=(select cate_id from sk_goods where id=".$goodsArr[$j]["goods_id"]."))";
			        $LArr = $do->selectsql($sql);
			        $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < now() and hd_jieshu > now() and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 2 and canyuid=(select mp_id from sk_goods where id=".$goodsArr[$j]["goods_id"]."))";
			        $MArr = $do->selectsql($sql);
			        $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < now() and hd_jieshu > now() and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 3 and canyuid=(select gx_id from sk_goods where id=".$goodsArr[$j]["goods_id"]."))";
			        $GArr = $do->selectsql($sql);
			    
			        if($LArr[0]['cou'] > 0||$MArr[0]['cou'] > 0||$GArr[0]['cou'] > 0){//商品还在活动中
			            $json .= '"status":"1",';
			        }else
			            $json .= '"status":"0",';
			    }elseif($goodsArr[$j]['goods_expired']==2){
			        $sql="select count(*) count from sk_gs where gid='".$goodsArr[$j]["goods_id"]."' and start_time<".time()." and end_time>".time()." and status=1";
			        $gs=$do->getone($sql);
			        if($gs['count'] > 0)
			            $json .= '"status":"1",';
			        else
			            $json .= '"status":"0",';
			    }
			}else
			    $json .= "\"status\":\"".$goodsArr[$j]["status"]."\",";
			
			$json .= "\"quantity\":\"".$goodsArr[$j]["quantity"]."\"},";
		}
		$json = substr($json, 0, strlen($json)-1) . "]}";
	}
}
//echo (string)$resultArr[0]["address"]."9";
echo $json;
?>