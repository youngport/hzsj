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
$limit_t = intval($_POST["limit_t"]);
$sql = "select neirong,from_unixtime(pl.shijian) shijian,me.wei_nickname,me.headimgurl from sk_lfx_pinglun pl join sk_member me on me.open_id=pl.openid where pl.lfx_id='$id' order by shijian desc limit ".$limit_t.",21";
$resultArr = $do->selectsql($sql);
//$json = $com->createBaseJson($resultArr, "neirong,shijian,wei_nickname,headimgurl");

$json = "";
if(count($resultArr)>0){
    $json = "[";
    for($i=0;$i<count($resultArr);$i++){
        $json .= "{\"wei_nickname\":\"".$resultArr[$i]["wei_nickname"]."\",";
        $json .= "\"shijian\":\"".$resultArr[$i]["shijian"]."\",";
        $json .= "\"neirong\":\"".$do -> uphtml($resultArr[$i]["neirong"])."\",";
        $json .= "\"headimgurl\":\"".$resultArr[$i]["headimgurl"]."\"},";
    }
    $json = substr($json, 0, strlen($json)-1)."]";
}
echo $json;

?>