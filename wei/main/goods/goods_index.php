<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
$cartnum = $_SESSION["cartnum"];
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$openid = $_SESSION["openid"];

$do = new condbwei();
$com = new commonFun();
$data=0;
if($_POST['type']==1) {
    $data = $do->selectsql("select id,cate_id,good_name,img,price,orgprice from sk_goods where is_hots=1 and status=1 and goodtype=0 order by sort_order");
    foreach ($data as $k => $v) {
        $v['good_name'] = mb_substr($v['good_name'], 0, 9, 'utf8');
        $v['price'] = round($v['price']);
        $v['orgprice'] = round($v['orgprice']);
        $data[$k] = $v;
    }
}elseif($_POST['type']==2){
    $data=$do->selectsql("select huodong_id,hd_img from sk_huodong where hd_youxiao =1 order by paixu asc");
}
echo json_encode($data);