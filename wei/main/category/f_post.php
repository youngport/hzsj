<?php

include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$cartnum = $_SESSION["cartnum"];
$categoryid = intval($_GET["categoryid"]);
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];
$dianji = $_GET["ddjm"];

$tiaoid="";//跳转页面所带参数
$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl = 'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

if (isset($_POST['id'])) {
	$id = addslashes($_POST['id']);
	$sql = "select id,name from sk_items_cate where pid=".$id;
	$result = $do -> selectsql($sql);
	foreach ($result as $key => $value) {
		$sql_ = "select good_name,img,id,cate_id,canyuhd from sk_goods where cate_id=".$value['id']." limit 0,6";
		$result[$key]['val'] = $do -> selectsql($sql_);
	}
}
echo json_encode($result);

?>