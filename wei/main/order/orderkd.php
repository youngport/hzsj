<?php
include '../../base/condbwei.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}
$do = new condbwei();
$openid=$_SESSION['openid'];
$order_id=intval($_POST['order_id']);
$sql="select count(*) count from sk_orders where order_id=$order_id and buyer_id='$openid' and status in (2,3)";
$count=$do->getone($sql);
if($count['count']>0) {
    $sql="select count(*) count from sk_order_goods a left join sk_kuaidi b on a.invoice_no=b.danhao where order_id=$order_id and zhuangtai!=3";
    $count=$do->getone($sql);
    if($count['count']>0)
        echo 2;
    else
        echo 1;
}else
    echo 3;