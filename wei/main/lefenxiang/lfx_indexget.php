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


//$fenlei = quotes($_POST["fenlei"]);
$limit = quotes($_POST["limit"]);
$searchTxt=quotes($_POST["searchTxt"]);
$openid=$openid = $_SESSION["openid"];
if(empty($searchTxt)){
    $sql = "select lfx.id,lfx.openid,lfx.biaoti,lfx.fenlei as lfx_cate,lfx.neirong,from_unixtime(lfx.shijian) shijian,me.wei_nickname,me.open_id,me.headimgurl, (select count(*) from sk_lfx_dz where lfxid=lfx.id) dianzan, (select count(*) from sk_lfx_pinglun where lfx_id=lfx.id) pinglun from sk_lfx lfx left join sk_member me on me.open_id=lfx.openid where  lfx.shenhe=1 order by shijian desc limit ".$limit.",21";
}else{
    $sql = "select lfx.id,lfx.openid,lfx.biaoti,lfx.fenlei as lfx_cate,lfx.neirong,from_unixtime(lfx.shijian) shijian,me.wei_nickname,me.open_id,me.headimgurl, (select count(*) from sk_lfx_dz where lfxid=lfx.id) dianzan, (select count(*) from sk_lfx_pinglun where lfx_id=lfx.id) pinglun from sk_lfx lfx left join sk_member me on me.open_id=lfx.openid where  lfx.shenhe=1 and lfx.neirong like '%".$searchTxt."%' order by shijian desc limit ".$limit.",21";
}

$resultArr = $do->selectsql($sql);
foreach($resultArr as $k=>$v){
    $resultArr[$k]['neirong']=mb_substr(strip_tags(htmlspecialchars_decode($resultArr[$k]['neirong'])),0,100,"utf-8");
    $resultArr[$k]['imgurl']=$do->selectsql("select imgurl,s_imgurl from sk_lfx_img where lfxid=".$resultArr[$k]['id']." limit 3");
    $resultArr[$k]['dz']=$do->selectsql("select count(*) as dz_count from sk_lfx_dz where openid='".$openid."' and lfxid='".$resultArr[$k]['id']."'");
    //echo "select count(*) as dz_count from sk_lfx_dz where openid='".$openid."' and lfxid='".$resultArr[$k]['id']."'"; die;
}

echo json_encode($resultArr);
?>