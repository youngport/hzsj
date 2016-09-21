<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$goodsid = intval($_POST["soucang"]);//ID
if($openid!=""&&$openid!=null){
	$sql = "select * from sk_soucang where yonghu='$openid' and chanpin='$goodsid'";
	$soucangArr = $do->selectsql($sql);
	
	if(count($soucangArr)>0){
		echo 2;
	}else{
		$time = date("y-m-d");
		$sql = "insert into sk_soucang (yonghu,chanpin,sc_time) values ('$openid','$goodsid','$time')";
		$tag = $do->dealsql($sql);
		if($tag == true){
			echo 1;
		}else{
			echo 0;
		}
	}
}


function quotes($content)
{
	if (is_array($content))
	{
		foreach ($content as $key=>$value)
		{
			$content[$key] = addslashes($value);
		}
	}
	else
	{
		$content=addslashes($content);
	}
	return htmlspecialchars($content);
}
?>