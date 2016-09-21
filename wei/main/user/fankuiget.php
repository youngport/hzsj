<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
$openid = $_SESSION["openid"];
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
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
if($_SESSION['VCODE']==$_POST['yzm']){
	if($_POST['text_f']){
			$text_f = quotes($_POST["text_f"]);
			$leix = quotes($_POST["lei"]);
			$lxfs = quotes($_POST["lxfs"]);
			$text_f = mysql_escape_string($text_f);
			$leix = mysql_escape_string($leix);
			$lxfs = mysql_escape_string($lxfs);
			if($text_f!=""){
				$where_ext = "insert into sk_fankui ( openid, text, date,lei,lianx) values ( '".$_SESSION["openid"]."', '".$text_f."', ".time().",'$leix','$lxfs')";
				$resultArr = $do->selectsql($where_ext);
				echo 1;
			}
			else
				echo 0;
	}else
		echo 0;
}else
	echo 5;
?>
