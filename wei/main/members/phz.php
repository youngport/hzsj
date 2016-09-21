<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$do = new condbwei();
$openid = $_SESSION["openid"];
$i=addslashes($_POST['openid']);
$cou = $do->selectsql("select count(*) cou from sk_panhangzan where openid='$openid' and bopenid='$i'");
if($cou[0]['cou']==0){
    $do->selectsql("insert into sk_panhangzan (openid,bopenid)values('$openid','$i')");
    echo 1;
}else{
    echo 0;
}
/* $i=$_POST['openid'];
$sql = "update sk_member set phzan = phzan + 1 where open_id='$i'";
$do->selectsql($sql);
echo 1; */

