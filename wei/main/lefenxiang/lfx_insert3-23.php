<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
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
$biaoti = quotes($_POST['biaoti']);
$fenlei = intval($_POST['fenlei']);
$xinde = quotes($_POST['xinde']);
//$xinde = $_POST['xinde'];


$lfx_id = $do->sqlTableId("sk_lfx", "id");
	$sql = "insert into sk_lfx (id,biaoti,neirong,shijian,openid,fenlei)values(".$lfx_id.",'$biaoti','$xinde',".time().",'$openid',".$fenlei.")";
	if($do->dealsql($sql)){
	    $sql = "insert into sk_lfx_img ( lfxid, imgurl, shunxu) values ";
	    for($i=0;$i<count($arr)-1;$i++)
	        $sql .= "( '$lfx_id', '".$arr[$i]."',".$i."),";
	    $do->dealsql(substr($sql,0,-1));
	    setcookie();
        $cookie_up = $_COOKIE["fenxiang"] ;
        $arr = explode(",",$cookie_up);
        $sql = "insert into sk_lfx_goods ( goodsid, lfxid, sunxu) values ";
        for($i=0;$i<count($arr);$i++){
            $sql .= "( '$arr[$i]', '".$lfx_id."',".$i."),";
        }
        $do->dealsql(substr($sql,0,-1));

        setcookie("fenxiang","", 3600);
	    echo 1;
	}
	else
	    echo 0;
?>