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

/*$json = "[";
$sql = "SELECT s.id,s.img,s.good_name,s.orgprice,s.shangoujia FROM sk_goods s where sg_date=current_date and shangou=1 ";
$gxArr = $do->selectsql($sql);
if(count($gxArr)>0){
	for($i=0;$i<count($gxArr);$i++){
		$json .= "{\"id\":\"".$gxArr[$i]["id"]."\",";
		$json .= "\"good_name\":\"".$gxArr[$i]["good_name"]."\",";
		$json .= "\"orgprice\":\"".$gxArr[$i]["orgprice"]."\",";
		$json .= "\"shangoujia\":\"".$gxArr[$i]["shangoujia"]."\",";
		$json .= "\"img\":\"".$gxArr[$i]["img"]."\"},";
	}
$json = substr($json, 0, strlen($json)-1)."]";
echo $json;
}*/

/* $y=date("Y",time());
$m=date("m",time());
$d=date("d",time()); 
$start_time = mktime(0, 0, 0, $m, $d ,$y); 
$time = time();
$miao = $time-$start_time;

$yu = $miao%30;
$shu = ($miao-$yu)/30;


$sql = "SELECT s.id,s.img,s.good_name,s.orgprice,s.shangoujia,s.sg_int FROM sk_goods s where sg_date=current_date and shangou=1 ";
$goodsArr = $do->selectsql($sql);

$shu = $shu%count($goodsArr);//轮到第几个商品闪购
if($shu==0)
	$shu = count($goodsArr);//最后一个

$json = "[";
$json .= "{\"yu\":\"".$yu."\",";//倒计时
$json .= "\"shu\":\"".$shu."\",";//第几个商品
$json .= "\"shop\":";
if(count($goodsArr)>0){
	$json .= "[";
	for($j=0;$j<count($goodsArr);$j++){
		$json .= "{\"id\":\"".$goodsArr[$j]["id"]."\",";
		$json .= "\"good_name\":\"".$goodsArr[$j]["good_name"]."\",";
		$json .= "\"orgprice\":\"".$goodsArr[$j]["orgprice"]."\",";
		$json .= "\"shangoujia\":\"".$goodsArr[$j]["shangoujia"]."\",";
		$json .= "\"sg_int\":\"".$goodsArr[$j]["sg_int"]."\",";
		$json .= "\"img\":\"".$goodsArr[$j]["img"]."\"},";
	}
	$json = substr($json, 0, strlen($json)-1)."]},";
}else{
	$json .= "[]},";
}
$json = substr($json, 0, strlen($json)-1)."]";

echo $json; */
$y=date("Y",time());
$m=date("m",time());
$d=date("d",time());
$start_time = mktime(0, 0, 0, $m, $d ,$y);
$end_time = $start_time+3600*24;
$time = time();
$miao = $time-$start_time;

$yu = $miao%30;
$shu = ($miao-$yu)/30;
$sql="select a.id,a.img,a.good_name,a.orgprice,b.price shangoujia,b.number sg_int from sk_goods a inner join sk_sg b on a.id=b.gid where b.status=1 and b.start_time<".time()." and end_time>".time();
$goodsArr = $do->selectsql($sql);
if(count($goodsArr)<=0){
    echo 2;die;
}
$shu = $shu%count($goodsArr);//轮到第几个商品闪购
if($shu==0)
    $shu = count($goodsArr);//最后一个

$json = "[";
$json .= "{\"yu\":\"".$yu."\",";//倒计时
$json .= "\"shu\":\"".$shu."\",";//第几个商品
$json .= "\"shop\":";
if(count($goodsArr)>0){
    $json .= "[";
    for($j=0;$j<count($goodsArr);$j++){
        $json .= "{\"id\":\"".$goodsArr[$j]["id"]."\",";
        $json .= "\"good_name\":\"".$goodsArr[$j]["good_name"]."\",";
        $json .= "\"orgprice\":\"".$goodsArr[$j]["orgprice"]."\",";
        $json .= "\"shangoujia\":\"".$goodsArr[$j]["shangoujia"]."\",";
        $json .= "\"sg_int\":\"".$goodsArr[$j]["sg_int"]."\",";
        $json .= "\"img\":\"".$goodsArr[$j]["img"]."\"},";
    }
    $json = substr($json, 0, strlen($json)-1)."]},";
}else{
    $json .= "[]},";
}
$json = substr($json, 0, strlen($json)-1)."]";

echo $json;
?>


