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
$xingm = quotes($_POST['xingm']);
$lianx = empty($_POST['lianx'])?'':quotes($_POST['lianx']);
$souji = quotes($_POST['souji']);
$thuan = intval($_POST['thuan']);
$cname = quotes($_POST['cname']);
$credit = quotes($_POST['credit']);

$sql = "select * from sk_thhuo where ordersid='$order_id'";
$kf = $do->selectsql($sql);
if(count($kf)==0){
    $sql = "insert into sk_thhuo(name,lianxi,liyou,tuihuan,ordersid,cname,credit) values('$xingm','$souji','$lianx',$thuan,$order_id,'$cname','$credit')";
    $do->selectsql($sql);
    $sql = "update sk_orders set status=6 where order_id='$order_id'";
    $do->selectsql($sql);
    echo 1;
}else 
    echo 2;

