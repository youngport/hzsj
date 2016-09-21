<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
$menuArr = $com->mainMenu($do);
$json = "";
if(count($menuArr)>0){
	$json = "[";
	for($i=0;$i<count($menuArr);$i++){
		$json .= "{\"id\":\"".$menuArr[$i]["id"]."\",";
		$json .= "\"name\":\"".$menuArr[$i]["name"]."\",";
		$json .= "\"chmenu\":";
		$sql = "select id,name from sk_items_cate where pid='".$menuArr[$i]["id"]."'";
		$chMenuArr = $do->selectsql($sql);
		if(count($chMenuArr)>0){
			$json .= "[";
			for($j=0;$j<count($chMenuArr);$j++){
				$json .= "{\"id\":\"".$chMenuArr[$j]["id"]."\",";
				$json .= "\"name\":\"".$chMenuArr[$j]["name"]."\"},";
			}
			$json = substr($json, 0, strlen($json)-1)."]},";
		}else{
			$json .= "[]},";
		}
	}
	$json = substr($json, 0, strlen($json)-1)."]";
}
echo $json;
?>