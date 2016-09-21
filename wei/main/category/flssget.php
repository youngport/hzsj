<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$fen = "";
if($_POST["fen"])
	$fen = addslashes($_POST["fen"]);
$menuArr = $com->mainMenu($do);
$json = "";
if(count($menuArr)>0){
	$json = "[";
	for($i=0;$i<count($menuArr);$i++){
		$json .= "{\"id\":\"".$menuArr[$i]["id"]."\",";
		$json .= "\"name\":\"".$menuArr[$i]["name"]."\",";
		$json .= "\"shop\":";
		//SELECT goo.mp_id,goo.gx_id,gx.name,mp.name FROM sk_goods goo join sk_gongx gx on goo.gx_id=gx.id join sk_mingp mp on mp.id=goo.mp_id 
		if($fen == 'pp')//品牌
			$sql = "select goo.mp_id id,mp.name name from sk_goods goo join sk_mingp mp on mp.id=goo.mp_id  where goo.goodtype=0 and(cate_id='".$menuArr[$i]["id"]."' or cate_id in(select id from sk_items_cate where pid='".$menuArr[$i]["id"]."')) group by goo.mp_id";
		elseif($fen == 'fl')//分类
			$sql = "select goo.cate_id id,cate.name name from sk_goods goo join sk_items_cate cate on cate.id=goo.cate_id where goo.goodtype=0 and cate_id in(select id from sk_items_cate where pid='".$menuArr[$i]["id"]."') group by goo.cate_id";
		elseif($fen == 'gx')//功效
			$sql = "select goo.gx_id id,gx.name name from sk_goods goo join sk_gongx gx on gx.id=goo.gx_id  where goo.goodtype=0 and(cate_id='".$menuArr[$i]["id"]."' or cate_id in(select id from sk_items_cate where pid='".$menuArr[$i]["id"]."')) group by goo.gx_id";
		$goodsArr = $do->selectsql($sql);
		if(count($goodsArr)>0){
			$json .= "[";
			for($j=0;$j<count($goodsArr);$j++){
				$json .= "{\"id\":\"".$goodsArr[$j]["id"]."\",";
				$json .= "\"fen\":\"".$fen."\",";
				$json .= "\"name\":\"".$goodsArr[$j]["name"]."\"},";
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