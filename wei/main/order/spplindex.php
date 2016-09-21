<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
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

$do = new condbwei();
$com = new commonFun();
session_start();
if($_POST["fenxiang"]){
	$order_id = intval($_POST["fenxiang"]);
	$sql = "select goods_images,goods_id,goods_name from sk_order_goods where order_id='$order_id'";
	$resultArr = $do->selectsql($sql);
	$json = $com->createBaseJson($resultArr, "goods_images,goods_id,goods_name");
	echo $json;
}
?>