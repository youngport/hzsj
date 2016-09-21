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
$_SESSION["caini_xihuan"] = intval($_POST['quid']);
$productName = addslashes($_POST["productName"]);
$sqllimit = intval($_POST["sqllimit"]);

$where_ext = "";
if($_POST['quid']) {
	$quid=$_POST['quid']+0;
	$where_ext .= "id in(select id from sk_goods where cate_id in(select id from sk_items_cate where pid = '" . $quid . "') or cate_id='" . $quid . "')";
}elseif($_POST['hots']){
	$where_ext.="is_hots=1";
}if($_POST['mp'])
	$where_ext .= " and mp_id=".intval($_POST['mp']);
elseif($_POST['fl'])
	$where_ext .= " and cate_id=".intval($_POST['fl']);
elseif($_POST['gx'])
	$where_ext .= " and gx_id=".intval($_POST['gx']);
if($where_ext != "")
	$where_ext .= " and ";
//if($productName=="")
//    $canyuhuodong = " and canyuhd=0 ";
$canyuhuodong='';
if($_POST["leix"])
	$sql = "select id,cate_id,good_name,img,price,huodongjia,canyuhd from sk_goods where ".$where_ext." good_name like '%$productName%' and goodtype=0 and status=1 ".$canyuhuodong." and sort_order not in(1,2,3,4,5) order by ".addslashes($_POST["leix"])." ".addslashes($_POST["paix"])." limit ".$sqllimit.",21";
else
	$sql = "select id,cate_id,good_name,img,price,huodongjia,canyuhd from sk_goods where ".$where_ext." good_name like '%$productName%' and goodtype=0 and status=1 ".$canyuhuodong." and sort_order not in(1,2,3,4,5) order by `sk_goods`.`xiaoliang` desc limit ".$sqllimit.",21";
$resultArr = $do->selectsql($sql);
$json = "[";
for($i=0;$i < count($resultArr); $i++){
    $json .= '{"id":"'.$resultArr[$i]['id'].'",';
	$json .= '"cate_id":"'.$resultArr[$i]['cate_id'].'",';
    $json .= '"good_name":"'.$do -> gohtml($resultArr[$i]['good_name']).'",';
    $json .= '"img":"'.$resultArr[$i]['img'].'",';
    if ($resultArr[$i]['canyuhd']==1) {//判断活动商品是否结束

    	    $id = $resultArr[$i]['id'];
    	    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 1 and canyuid=(select cate_id from sk_goods where id='$id '))";
		    $LArr = $do->selectsql($sql);
		    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 2 and canyuid=(select mp_id from sk_goods where id='$id '))";
		    $MArr = $do->selectsql($sql);
		    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 3 and canyuid=(select gx_id from sk_goods where id='$id '))";
		    $GArr = $do->selectsql($sql);
		    if($LArr[0]['cou'] > 0||$MArr[0]['cou'] > 0||$GArr[0]['cou'] > 0){
                $resultArr[$i]['canyuhd']=1;
    }else{
                $resultArr[$i]['canyuhd']=0;
    	}
    }
    $json .= '"canyuhd":"'.$resultArr[$i]['canyuhd'].'",';
    $json .= '"huodongjia":"'.$resultArr[$i]['huodongjia'].'",';
    $json .= '"price":"'.$resultArr[$i]['price'].'"},';
}
$json = substr($json, 0, strlen($json)-1)."]";
//$json = $com->createBaseJson($resultArr, "id,good_name,img,price");
echo $json;
?>