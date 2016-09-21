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

$openid = $_SESSION["openid"];
$id = intval($_POST["id"]);
$do->selectsql("update sk_lfx set dianji = dianji + 1 where sk_lfx.id='$id'");
$lcount=$do->getone("select count(*) count from sk_lfx_count where lfx_id=$id and openid='$openid'");
if($lcount['count']>0)
	$do->dealsql("update sk_lfx_count set count=count+1 where lfx_id=$id and openid='$openid'");
else {
	$do->dealsql("insert into sk_lfx_count (lfx_id,openid) value ($id,'$openid')");
}

$sql = "select lfx.biaoti,lfx.neirong,from_unixtime(lfx.shijian) shijian,lfx.dianji,me.wei_nickname,(select count(*) from sk_lfx_dz where lfxid=lfx.id) dianzan,(select count(*) from sk_lfx_pinglun where lfx_id=lfx.id ) pinlun from sk_lfx lfx join sk_member me on me.open_id=lfx.openid where lfx.id='$id'";
$menuArr = $do->selectsql($sql);
$json = "";
if(count($menuArr)>0){
	$json = "[";
	for($i=0;$i<count($menuArr);$i++){
		$json .= "{\"id\":\"".$menuArr[$i]["id"]."\",";
		$json .= "\"biaoti\":\"".$menuArr[$i]["biaoti"]."\",";
		$json .= "\"neirong\":\"".$do -> uphtml($menuArr[$i]["neirong"])."\",";
		$json .= "\"shijian\":\"".$menuArr[$i]["shijian"]."\",";
		$json .= "\"dianji\":\"".$menuArr[$i]["dianji"]."\",";
		$json .= "\"dianzan\":\"".$menuArr[$i]["dianzan"]."\",";
		$json .= "\"pinlun\":\"".$menuArr[$i]["pinlun"]."\",";
		$json .= "\"wei_nickname\":\"".$menuArr[$i]["wei_nickname"]."\",";
		$json .= "\"imgurl\":";
			$sql = "select imgurl from sk_lfx_img where lfxid='$id' order by shunxu asc";
		$goodsArr = $do->selectsql($sql);
		if(count($goodsArr)>0){
			$json .= "[";
			for($j=0;$j<count($goodsArr);$j++){
				$json .= "{\"imgurl\":\"".$goodsArr[$j]["imgurl"]."\"},";
			}
			$json = substr($json, 0, strlen($json)-1)."],";
		}else{
			$json .= "[],";
		}
		$json .= "\"goods_imgurl\":";
			$sql = "select g.img,lg.goodsid from sk_lfx_goods lg join sk_goods g on g.id=lg.goodsid where lfxid='$id' order by sunxu asc";
		$goodsArr = $do->selectsql($sql);
		if(count($goodsArr)>0){
			$json .= "[";
			for($j=0;$j<count($goodsArr);$j++){
				$json .= "{\"img\":\"".$goodsArr[$j]["img"]."\",";
				$json .= "\"goodsid\":\"".$goodsArr[$j]["goodsid"]."\"},";
			}
			$json = substr($json, 0, strlen($json)-1)."],";
		}else{
			$json .= "[],";
		}
		$json .= "\"open_imgurl\":";
			$sql = "select sk_member.headimgurl from sk_lfx_dz join sk_member on sk_member.open_id=sk_lfx_dz.openid where lfxid='$id' order by shijian desc limit 0,10";
		$goodsArr = $do->selectsql($sql);
		if(count($goodsArr)>0){
			$json .= "[";
			for($j=0;$j<count($goodsArr);$j++){
				$json .= "{\"headimgurl\":\"".$goodsArr[$j]["headimgurl"]."\"},";
			}
			$json = substr($json, 0, strlen($json)-1)."]},";
		}else{
			$json .= "[{}]},";
		}
	}
	$json = substr($json, 0, strlen($json)-1)."]";
}
echo $json;






?>