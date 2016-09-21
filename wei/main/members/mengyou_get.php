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

if(intval($_POST['s'])==1){
    $mysql = "select usrid,headimgurl,wei_nickname,open_id,(select sum(pop) as pop from wei_pop where openid=sk_member.open_id) pop,(select sum(pop) from wei_pop where openid = '$openid' and (order_sn in(select order_sn from wei_pop where openid = sk_member.open_id ) or order_sn in (select order_sn from sk_orders where buyer_id = sk_member.open_id ))) mypop from sk_member where pid='$openid' and jointag in(1,2) ORDER BY login_time DESC limit ".intval($_POST['data_sum']).",11";
}else if(intval($_POST['s'])==0)
    $mysql = "select usrid,headimgurl,wei_nickname,open_id,(select sum(pop) as pop from wei_pop where openid=sk_member.open_id) pop,(select sum(pop) from wei_pop where openid = '$openid' and (order_sn in(select order_sn from wei_pop where openid = sk_member.open_id ) or order_sn in (select order_sn from sk_orders where buyer_id = sk_member.open_id ))) mypop from sk_member where pid='$openid' and jointag = 0 ORDER BY login_time DESC limit ".intval($_POST['data_sum']).",11";
    $mypopArr = $do->selectsql($mysql);
$json = "[";
for ($i=0;$i<count($mypopArr);$i++){
    $json .= '{"usrid":"'.$mypopArr[$i]['usrid'].'",';
    $json .= '"headimgurl":"'.$mypopArr[$i]['headimgurl'].'",';
    $json .= '"wei_nickname":"'.addslashes($mypopArr[$i]['wei_nickname']).'",';
    $json .= '"open_id":"'.$mypopArr[$i]['open_id'].'",';
    $json .= '"pop":"'.$mypopArr[$i]['pop'].'",';
    $json .= '"mypop":"'.$mypopArr[$i]['mypop'].'"},';
}
$json = substr($json, 0, strlen($json)-1)."]";
//$json = $com->createBaseJson($mypopArr, "usrid,headimgurl,wei_nickname,open_id,pop,mypop");
echo $json;
?>