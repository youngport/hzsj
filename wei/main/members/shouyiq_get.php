<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$cartnum = 0;
if(isset($_SESSION["cartnum"])){
	$cartnum = $_SESSION["cartnum"];
}
$do = new condbwei();
$com = new commonFun();

$openid = $_SESSION["openid"];
$taopenid = addslashes($_POST['shouyi_id']);
$limit_int = intval($_POST['limit_int']);

$sql = "select pop,poptime,(select wei_nickname from sk_member where open_id = (select buyer_id from sk_orders where order_sn = wei_pop.order_sn)) wei_nickname from wei_pop where openid = '$openid' and (order_sn in(select order_sn from wei_pop where openid ='$taopenid') or order_sn in (select order_sn from sk_orders where buyer_id = '$taopenid')) order by poptime desc limit ".$limit_int.",21";

$json = "[";
$shouyiArr = $do -> selectsql($sql);
if(count($shouyiArr)>0){
    for($i = 0; $i < count($shouyiArr); $i++){
        $json .= '{"pop":"'.$shouyiArr[$i]['pop'].'",'; 
        $json .= '"poptime":"'.$shouyiArr[$i]['poptime'].'",'; 
        $json .= '"wei_nickname":"'.$do->gohtml($shouyiArr[$i]['wei_nickname']).'"},'; 
    }
    $json = substr($json, 0, strlen($json)-1)."]";
}else 
    $json .= "]";
echo $json;
?>