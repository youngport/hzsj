<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
$do = new condbwei();
$com = new commonFun();
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
if($_POST["dd"]){
	$id = intval($_POST["dd"]);
	$lei = intval($_POST["ll"]);//功效 1, 名牌 2, 类别 3
	if($lei==1)
		$sql = "select g.id,g.img,g.good_name,g.huodongjia,g.price,g.zhekou,g.hdtjtext from sk_goods g join sk_gongx x on g.gx_id=x.id where x.huodong=1 and g.goodtype=0 and g.status=1 and canyuhd=1 and x.id='$id' and g.sort_order>5 and g.hdtuijian=1 limit 0,1";
	else if($lei==2)
		$sql = "select g.id,g.img,g.good_name,g.huodongjia,g.price,g.zhekou,g.hdtjtext from sk_goods g join sk_mingp x on g.mp_id=x.id where x.huodong=1 and g.goodtype=0 and g.status=1 and canyuhd=1 and x.id='$id' and g.sort_order>5 and g.hdtuijian=1 limit 0,1";
	else if($lei==3)
		$sql = "select g.id,g.img,g.good_name,g.huodongjia,g.price,g.zhekou,g.hdtjtext from sk_goods g join sk_items_cate x on g.cate_id=x.id where x.huodong=1 and (x.id='$id' or x.id in(SELECT id FROM sk_items_cate WHERE pid='$id')) and g.goodtype=0 and g.status=1 and canyuhd=1 and g.sort_order>5 and g.hdtuijian=1 limit 0,1";
	$gxArr = $do->selectsql($sql);
	$json = "[";
	if(count($gxArr)>0){
		for($i=0;$i<count($gxArr);$i++){
			$json .= "{\"id\":\"".$gxArr[$i]["id"]."\",";
			$json .= "\"good_name\":\"".$gxArr[$i]["good_name"]."\",";
			$json .= "\"huodongjia\":\"".$gxArr[$i]["huodongjia"]."\",";
			$json .= "\"price\":\"".$gxArr[$i]["price"]."\",";
			$json .= "\"zhekou\":\"".$gxArr[$i]["zhekou"]."\",";
			$json .= "\"hdtjtext\":\"".$gxArr[$i]["hdtjtext"]."\",";
			$json .= "\"img\":\"".$gxArr[$i]["img"]."\"},";
		}
	}
	$json = substr($json, 0, strlen($json)-1)."]";
	echo $json;
}

/*	$sql = "select id,good_name,img,price from sk_goods where ".$where_ext." good_name like '%$productName%' and goodtype=0 and status=1 and sort_order not in(1,2,3,4,5) order by `sk_goods`.`hits` desc";
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "id,good_name,img,price");*/




/*if(count($menuArr)>0){
	$json = "[";
	for($i=0;$i<count($menuArr);$i++){
		$json .= "{\"id\":\"".$menuArr[$i]["id"]."\",";
		$json .= "\"name\":\"".$menuArr[$i]["name"]."\",";
		$json .= "\"shop\":[]},";
		}
	}
	$json = substr($json, 0, strlen($json)-1)."]";
}*/
?>


