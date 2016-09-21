<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$orderid = intval($_POST["orderid"]);

//是否店铺会员
$dp_user = $do->selectsql("select jointag from sk_member where open_id ='$openid'");
$jointag = $dp_user[0]['jointag'];
//获取店铺会员售价比例

$dianpushenhe = true;
$sql = "select erm from sk_orders where order_id='$orderid' and buyer_id='$openid'";
$resultArr = $do->selectsql($sql);
if($resultArr[0]['erm'] == 1){
    $dianpushenhe = false;
    $sql = "select jiamengshenhe from sk_dpgou where orderid='$orderid'";
    $spArr = $do->selectsql($sql);
    if($spArr[0]['jiamengshenhe'] == 1)
        $dianpushenhe = true;
}
if($dianpushenhe){
    $json = "";
    $sql = "select erm,(select count(*) from sk_dpgou where sk_dpgou.orderid = sk_orders.order_id) cou from sk_orders where order_id='$orderid' and buyer_id='$openid'";
    $resultArr = $do->selectsql($sql);
    if($resultArr[0]['cou']>0 || $resultArr[0]['erm']==0 || $resultArr[0]['erm']==2){
        $sql = "select w.goods_id,w.tax_rate,w.quantity,g.img,g.fp_price,g.good_name,w.price,orq.jifendh,g.goodtype,g.status,orq.add_time,orq.sg_xiadan,w.goods_expired,orq.is_member from sk_order_goods w left join sk_goods g on g.id=w.goods_id join sk_orders orq on orq.order_id=w.order_id
        where w.order_id='$orderid' and orq.buyer_id='$openid' and orq.status=0";
        $resultArr = $do->selectsql($sql);
        //var_dump($resultArr);exit;
        
        
        if( (time() - $resultArr[0]['add_time']) > 30 && $resultArr[0]['sg_xiadan'] == 1){
            $sql = "update sk_orders set status=98 where order_id='$orderid'";
            $do->selectsql($sql);
            $json = "{\"success\":\"0\"}";
        }else{
            $json = "[";
            for($i=0;$i<count($resultArr);$i++){
                $json .= '{"goods_id":"'.$resultArr[$i]['goods_id'].'",';
                $json .= '"quantity":"'.$resultArr[$i]['quantity'].'",';
                $json .= '"img":"'.$resultArr[$i]['img'].'",';
                $json .= '"is_member":"'.$resultArr[$i]['is_member'].'",';
                $json .= '"good_name":"'.$do -> gohtml($resultArr[$i]['good_name']).'",';
                if ($jointag == '2') {
        //获取店铺会员售价比例
        $dp_price = $do->selectsql("select dp_price from sk_dp_price where id = 1");
        //echo $dp_price;
        $dp_price = $dp_price[0]['dp_price'];
        //店铺会员价格为：商品价格-利润分出*店铺会员售价比例
        $fp_price = $resultArr[$i]["fp_price"]*$dp_price;
        $resultArr[$i]["price"]-=$fp_price;
        $json .= '"price":"'.$resultArr[$i]['price'].'",';
}else{
         $json .= '"price":"'.$resultArr[$i]['price'].'",';
       }        
                $json .= '"jifendh":"'.$resultArr[$i]['jifendh'].'",';
                $json .= '"goodtype":"'.$resultArr[$i]['goodtype'].'",';
                 $json .= '"tax_rate":"'.$resultArr[$i]['tax_rate'].'",';
                
                if($resultArr[$i]['goods_expired']==1 && $resultArr[$i]['status'] == 1){//活动商品，活动是否已结束
                    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < now() and hd_jieshu > now() and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 1 and canyuid=(select cate_id from sk_goods where id=".$resultArr[$i]['goods_id']."))";
                    $LArr = $do->selectsql($sql);
                    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < now() and hd_jieshu > now() and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 2 and canyuid=(select mp_id from sk_goods where id=".$resultArr[$i]['goods_id']."))";
                    $MArr = $do->selectsql($sql);
                    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < now() and hd_jieshu > now() and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 3 and canyuid=(select gx_id from sk_goods where id=".$resultArr[$i]['goods_id']."))";
                    $GArr = $do->selectsql($sql);
                    
                    if($LArr[0]['cou'] > 0||$MArr[0]['cou'] > 0||$GArr[0]['cou'] > 0){//商品还在活动中
				        $json .= '"status":"1"},';
                    }else 
				        $json .= '"status":"0"},';
                }elseif($resultArr[$i]["goods_expired"]==2 && $resultArr[$i]['status'] == 1){
    				$sql="select count(*) count from sk_gs where gid='".$resultArr[$i]['goods_id']."' and start_time<".time()." and end_time>".time()." and status=1";
    				$gs=$do->getone($sql);
    				if($gs['count'] > 0)
    				    $json .= '"status":"1"},';
    				else 
    				    $json .= '"status":"0"},';
    			}else
                    $json .= '"status":"'.$resultArr[$i]['status'].'"},';
            }
            $json = substr($json, 0, strlen($json)-1)."]";
            //$json = $com->createBaseJson($resultArr, "goods_id,quantity,img,good_name,price,jifendh,goodtype,status");
        }
    }else{
        $json = "{\"success\":\"1\"}";
    }
    echo $json;
}
?>