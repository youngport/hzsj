<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
$do = new condbwei();
$com = new commonFun();
session_start();
if(!isset($_SESSION["access_token"])){
	//header("Location:".$cxtPath."/wei/login.php");
	//return;
}
if($_POST["san"]){
	$san = intval($_POST["san"]);

	if($san==2){
		$json = "[";
		$sql = "SELECT s.id,s.tuan_img,s.tuan_img_jiage,s.remark,s.good_name,s.desc,s.orgprice,s.tuangoujia FROM sk_goods s where tuang=1 and status=1";
		$gxArr = $do->selectsql($sql);
		if(count($gxArr)>0){
			for($i=0;$i<count($gxArr);$i++){
				$json .= "{\"id\":\"".$gxArr[$i]["id"]."\",";
				$json .= "\"good_name\":\"".$gxArr[$i]["good_name"]."\",";
				$json .= "\"orgprice\":\"".$gxArr[$i]["orgprice"]."\",";
				$json .= "\"tuangoujia\":\"".$gxArr[$i]["tuangoujia"]."\",";
				//$json .= "\"desc\":\"".$gxArr[$i]["desc"]."\",";
				$json .= "\"lei\":\"2\",";
				$json .= "\"remark\":\"".$gxArr[$i]["remark"]."\",";
				$json .= "\"tuan_img_jiage\":\"".$gxArr[$i]["tuan_img_jiage"]."\",";
				$json .= "\"tuan_img\":\"".$gxArr[$i]["tuan_img"]."\"},";
			}
		$json = substr($json, 0, strlen($json)-1)."]";
		echo $json;
		}
	}
	else if($san==1){
		$json = "[";
		//$sql = "select id,img from sk_gongx where huodong=1 and huodks <'".date('Y-m-d H:i:s',time())."' and huodjs > '". date('Y-m-d H:i:s',time())."'";
		$sql = "select huodong_id,hd_img from sk_huodong where hd_youxiao =1 order by paixu asc";
		$gxArr = $do->selectsql($sql);
		if(count($gxArr)>0){
			for($i=0;$i<count($gxArr);$i++){
				$json .= "{\"huodong_id\":\"".$gxArr[$i]["huodong_id"]."\",";
				$json .= "\"hd_img\":\"".$gxArr[$i]["hd_img"]."\"},";
			}
		}
		$json = substr($json, 0, strlen($json)-1)."]";
		echo $json;
		}
	
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


