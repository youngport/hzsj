<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$cartnum = $_SESSION["cartnum"];
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

$order_id = intval($_POST["ddid"]);
$xgsql = "select fb_plun from sk_orders where order_id='$order_id'";
$pl = $do->selectsql($xgsql);
if($pl[0]['fb_plun']==0){
    $gos_json = $_POST['gos_json'];
    
    $gos_json = explode("|",$gos_json);
    
    $sql = "";
    for($i=0;$i<count($gos_json);$i++){
    	$sna = explode(";",$gos_json[$i]);
    	$sql = "insert into sk_sppl(spid,openid, xingji, neirong, shijian) values('".intval($sna[1])."','$openid',".intval($sna[0]).",'".quotes($sna[2])."',".time().")";
    	$do->dealsql($sql);
    }
    
    	$xgsql = "update sk_orders set fb_plun=1 where order_id='$order_id'";
    	$do->selectsql($xgsql);
    	$jfsql = "insert into sk_jifen (jifen,openid,laiyuan,shijian)values(5,'$openid',4,".time().")";
    	$do->selectsql($jfsql);
    	$sql = "update sk_member set jifen=jifen+5 where open_id='$openid'";
    	$do->selectsql($sql);
    echo 1;
}else{
    echo 2;
}
?>