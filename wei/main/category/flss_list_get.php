<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

$do = new condbwei();
$com = new commonFun();
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$_SESSION["caini_xihuan"] = intval($_POST['quid']);
$productName = addslashes($_POST["productName"]);
$sqllimit = intval($_POST["sqllimit"]);
//print_r($_POST);exit;
$where_ext = "";
/*if($_POST['quid']) {
	$quid=$_POST['quid']+0;
	$where_ext .= "id in(select id from sk_goods where cate_id in(select id from sk_items_cate where pid = '" . $quid . "') or cate_id='" . $quid . "')";
}elseif($_POST['hots']){
	$where_ext.="is_hots=1";
}*/
if($_POST['cate_id'])
	$where_ext .= "cate_id=".intval($_POST['cate_id']);
if($_POST['mp_id'])
	$where_ext .= " and mp_id=".intval($_POST['mp_id']);
if($_POST['mp_cate_id'])
	$where_ext .= " and mp_cate=".intval($_POST['mp_cate_id']);
if($_POST['nation_id'])
	$where_ext .= " and nation_id=".intval($_POST['nation_id']);
if($where_ext != "")
	$where_ext .= " and ";
if($productName=="")
   $canyuhuodong = " and canyuhd=0 ";
$canyuhuodong='';
//echo $where_ext;exit;
/*
if($_POST["leix"])
	$sql = "select id,cate_id,good_name,img,price,huodongjia,canyuhd from sk_goods where ".$where_ext." good_name like '%$productName%' and goodtype=0 and status=1 ".$canyuhuodong." and sort_order not in(1,2,3,4,5) order by ".addslashes($_POST["leix"])." ".addslashes($_POST["paix"])." limit ".$sqllimit.",21";
else*/
	$sql = "select id,cate_id,good_name,img,price,huodongjia,canyuhd from sk_goods where ".$where_ext." good_name like '%$productName%' and goodtype=0 and status=1 ".$canyuhuodong." and sort_order not in(1,2,3,4,5) order by `sk_goods`.`xiaoliang` desc limit ".$sqllimit.",21";
//echo $sql;exit;
$resultArr = $do->selectsql($sql);
//print_r($resultArr);exit;
$json = "[";
for($i=0;$i < count($resultArr); $i++){
    $json .= '{"id":"'.$resultArr[$i]['id'].'",';
	$json .= '"cate_id":"'.$resultArr[$i]['cate_id'].'",';
    $json .= '"good_name":"'.$do -> gohtml($resultArr[$i]['good_name']).'",';
    $json .= '"img":"'.$resultArr[$i]['img'].'",';
    $json .= '"canyuhd":"'.$resultArr[$i]['canyuhd'].'",';
    $json .= '"huodongjia":"'.$resultArr[$i]['huodongjia'].'",';
    $json .= '"price":"'.$resultArr[$i]['price'].'"},';
}
$json = substr($json, 0, strlen($json)-1)."]";
//$json = $com->createBaseJson($resultArr, "id,good_name,img,price");
echo $json;
?>