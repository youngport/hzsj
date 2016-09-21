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

function quotes($content){
    if (is_array($content))	{
        foreach ($content as $key=>$value){
            $content[$key] = addslashes($value);
        }
    }else{
        $content=addslashes($content);
    }
    return htmlspecialchars($content);
}

$order_id = intval($_POST['order_id']);
$xingm = quotes($_POST['kuaidmc']);
$lianx = quotes($_POST['kuaiddh']);


$sql = "update sk_thhuo set kuaidiname='$xingm',kuaididan='$lianx' where ordersid='$order_id'";
$do->selectsql($sql);
$sql = "update sk_orders set status=8 where order_id='$order_id'";
$do->selectsql($sql);
echo 1;

