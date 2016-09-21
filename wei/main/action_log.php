<?php
include '../base/condbwei.php';
include '../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}
function getIPaddress()
{
    $IPaddress='';
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $IPaddress = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $IPaddress = getenv("HTTP_CLIENT_IP");
        } else {
            $IPaddress = getenv("REMOTE_ADDR");
        }
    }
    return $IPaddress;
}
if($_POST){
    $actions=array("goods");//包含在内的操作才可以记录
    $action=addslashes($_POST['action']);
    $aid=intval($_POST['aid']);
    if(!in_array($action,$actions)||$aid<1){
        echo 2;exit;
    }
    $sql="select * from sk_action_log where openid='$openid' and action='$action' and aid=$aid order by time desc limit 1";
    $data=$do->getone($sql);
    if(empty($data)||($data['time']+5*60)<=time()){
        $sql="insert into sk_action_log(openid,action,aid,time,ip) values ('$openid','$action',$aid,".time().",'".getIPaddress()."')";
        $result=$do->dealsql($sql);
        echo $result!==false?1:0;
    }
}