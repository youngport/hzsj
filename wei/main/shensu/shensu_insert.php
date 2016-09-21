<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
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
$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];


$arr = explode(",",quotes($_POST['imgname']));
$wei_id = quotes($_POST['wei_id']);
$number = intval($_POST['number']);
$appeal_id = $do->sqlTableId("sk_appeal", "id");
	$sql = "insert into sk_appeal (id,wei_id,openid,number,shenhe,create_time)values(".$appeal_id.",'$wei_id','$openid','$number','2',".time().")";
	if($do->dealsql($sql)){
	    $sql = "insert into sk_appeal_img (appeal_id,img_path) values ";
	    for($i=0;$i<count($arr)-1;$i++)
	        $sql .= "( '$appeal_id', '".$arr[$i]."'),";
	    $do->dealsql(substr($sql,0,-1));
	    echo 1;
	}
	else
	    echo 0;
?>