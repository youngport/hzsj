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
$dianpid = intval($_POST['dianpid']);
$sql = "select pl.id,me.headimgurl,me.wei_nickname,from_unixtime(pl.shijian) shijian,zan,neirong from sk_dppinglun pl join sk_member me on pl.openid=me.open_id where pl.dianpid='$dianpid' and pl.jiancha=1 ORDER BY shijian DESC";
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "id,headimgurl,wei_nickname,shijian,zan,neirong");
echo $json;
