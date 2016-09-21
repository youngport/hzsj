<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();

$searchtext = addslashes($_POST["searchtext"]);

$sql = "select id,good_name,img,price from sk_goods where good_name like '%$searchtext%'";
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "id,good_name,img,price");
echo $json;
?>