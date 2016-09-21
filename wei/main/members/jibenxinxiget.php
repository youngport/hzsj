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

$mingpian_xuanyan = "";
$email = "";
$im_qq = "";
$phone_tel = "";
$phone_mob = "";
$im_weixin = "";
//dianhua,email,im_qq,phone_tel,phone_mob,im_weixin
if($_POST['xuanyan']){
	$mingpian_xuanyan = quotes($_POST['xuanyan']);
}

if($_POST['email']){
	$email = quotes($_POST['email']);
}
if($_POST['im_qq']){
	$im_qq = quotes($_POST['im_qq']);
}
if($_POST['phone_tel']){
	$phone_tel = quotes($_POST['phone_tel']);
}
if($_POST['phone_mob']){
	$phone_mob = quotes($_POST['phone_mob']);
}
if($_POST['im_weixin']){
	$im_weixin = quotes($_POST['im_weixin']);
}
$sql = "update sk_member set email='$email', im_qq='$im_qq', phone_tel='$phone_tel', phone_mob='$phone_mob', im_weixin='$im_weixin',mingpian_xuanyan='$mingpian_xuanyan' where open_id='$openid'";
$do->dealsql($sql);
 echo 1;
