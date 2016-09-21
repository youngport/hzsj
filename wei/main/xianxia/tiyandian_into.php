<?php
header("Content-type: text/html; charset=utf-8"); 

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


$cxtPath = "http://".$_SERVER['HTTP_HOST'];
$do = new condbwei();
$com = new commonFun();

session_start();
$openid = $_SESSION["openid"];
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
if($_POST['dianpid']){
	$dianpid = intval($_POST['dianpid']);
	$neirong = "";
	$neirong = quotes($_POST['neirong']);
	if($neirong!=""){
		$sql = "insert into sk_dppinglun(dianpid,neirong,openid,shijian,zan,jiancha) values(".$dianpid.",'$neirong','$openid',".time().",0,0)";
		$do->selectsql($sql);
		echo 0;
	}else
		echo 1;
}
