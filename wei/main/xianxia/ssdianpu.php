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
$cs = "";
$mc = "";
if($_POST['cs'])
	$cs = quotes($_POST['cs']);
if($_POST['mc'])
	$mc = quotes($_POST['mc']);

$sql = "select er.id,er.dianpname,er.xxdizhi,me.headimgurl from sk_erweima er join sk_member me on me.open_id=er.openid where shenhe=1 and xxdizhi like '%".$cs."%' and dianpname like '%".$mc."%' and dianp_img <> ''";
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "id,dianpname,xxdizhi,headimgurl");
echo $json;
