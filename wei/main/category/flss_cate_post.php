<?php

include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://" . $_SERVER['HTTP_HOST'];

session_start();
if (!isset($_SESSION["access_token"])) {
    header("Location:" . $cxtPath . "/wei/login.php");
    return;
}
$cartnum = $_SESSION["cartnum"];
$categoryid = intval($_GET["categoryid"]);
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];
$dianji = $_GET["ddjm"];

$tiaoid = ""; //跳转页面所带参数
$do = new condbwei();
$com = new commonFun();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$imgurl = 'http://' . $_SERVER['HTTP_HOST'] . "/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$resArr = array();

if (isset($_POST['id'])) {
    $sql_ = "select id, nationality,nationality_img from sk_nationality where cate_id='65'";
$nationArr = $do->selectsql($sql_);

// 查出商品所属品类
    $id = addslashes($_POST['id']);
    $sql = "select id,name,mp_img,cate_id from sk_mingp_cate where cate_id=" . $id;
    $result = $do->selectsql($sql);

        //$sql_ = "select good_name,img,id,cate_id,canyuhd from sk_goods where cate_id=".$value['id']." limit 0,6";
        //查出商品所属国籍
   
        $sql_ = "select id,nationality,nationality_img from sk_nationality where cate_id=".$id;
        $bb = $do -> selectsql($sql_);
   
    //print_r($result[$key]['val']);
       /* $sql_ = "select id,nationality,nationality_img from sk_nationality where cate_id=".$id;
        $nationArr= $do->selectsql($sql_);*/
        //print_r($nationArr);exit;
        // 查出商品所属品类
        
        $sql_c="select id,name ,mp_img from sk_mingp where cate_id='$id'";
        $cc=$do->selectsql($sql_c);
    
 $arr = array('a'=>$result,'b'=>$bb,'c'=>$cc);

    //print_r($arr);exit;
}
echo json_encode($arr);
