<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$openid = $_SESSION["openid"];
$y=date("Y",time());
$m=date("m",time());
//$start_time = mktime(0, 0, 0, $m, 1 ,$y);
$start_time = $y.'-'.$m.'-1 00:00:00';
if($m+1>12)
    $start_time2 = ($y+1).'-1-1 00:00:00';
else
    $start_time2 = $y.'-'.($m+1).'-1 00:00:00';

$do = new condbwei();
$json = "";
$sql = "select s.wei_nickname,w.openid,sum(w.pop) pop,s.phzan,s.headimgurl,(select count(*) from sk_panhangzan where bopenid=s.open_id) phzan,(select count(*) from sk_panhangzan where openid='$openid') sfyz from sk_member s join wei_pop w on w.openid=s.open_id where s.pid='$openid' or s.open_id='$openid' and w.poptime > '$start_time' and w.poptime < '$start_time2' group by w.openid order by pop desc limit 0,10";
//$sql = "select s.wei_nickname,w.openid,sum(w.pop) pop,s.phzan,s.headimgurl,(select count(*) from sk_paihangzan where bopenid=s.open_id) phzan from sk_member s join wei_pop w on w.openid=s.open_id where s.pid='$openid' or s.open_id='$openid' and w.poptime > '$start_time' and w.poptime < '$start_time2' group by w.openid order by pop desc limit 0,10";
//$sql = "select s.wei_nickname,w.openid,sum(w.pop) pop,s.phzan,s.headimgurl from sk_member s join wei_pop w on w.openid=s.open_id group by w.openid order by pop desc limit 0,10";
$goodsArr = $do->selectsql($sql);
if(count($goodsArr)>0){
	$json = "[";
	for($j=0;$j<count($goodsArr);$j++){
		$json .= "{\"openid\":\"".$goodsArr[$j]["openid"]."\",";
		$json .= "\"pop\":\"".$goodsArr[$j]["pop"]."\",";
		$json .= "\"phzan\":\"".$goodsArr[$j]["phzan"]."\",";
		$json .= "\"sfyz\":\"".$goodsArr[$j]["sfyz"]."\",";
		$json .= "\"headimgurl\":\"".$goodsArr[$j]["headimgurl"]."\",";
		$json .= "\"wei_nickname\":\"".$goodsArr[$j]["wei_nickname"]."\"},";
	}
	$json .= "]";
	echo $json;
}
