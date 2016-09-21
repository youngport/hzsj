<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();

$productName = addslashes($_POST["productName"]);

$sql = "select id,good_name,img,price from sk_goods where good_name like '%$productName%'";
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "id,good_name,img,price");
echo $json;
?>