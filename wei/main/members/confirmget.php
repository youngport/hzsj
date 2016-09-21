<?php
session_start();
include '../../base/condbwei.php';
include '../../base/commonFun.php';
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}

if(!isset($_SESSION['confirm'])) {
    echo "<script>window.history.back(-1);</script>";
    exit;
}
$do = new condbwei();
$com = new commonFun();
$openid=$_SESSION['openid'];
$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
header("content-type:text/html;charset=utf-8");
if(!empty($_POST)){
    array_walk($_POST,"addslaches");
    $parm="/^(13[0-9]|15[0-9]|18[0-9])\d{8}$/";
    $parm2="/^(\w){6,12}$/";
    if(strlen($_POST['mobile'])!=11||!preg_match($parm,$_POST['mobile'])){
        echo "2";
        exit;
    }
    if(empty($_SESSION['check']['msm'])){
//    if(empty($_SESSION['check']['voiceverify'])){
        echo "3";
        exit;
    }
    if(strtolower($_POST['voiceverify'])!=strtolower($_SESSION['check']['msm']['code'])){
        echo "4";
        exit;
    }
    if((time()-$_SESSION['check']['msm']['time'])>600){
        echo "5";
        exit;
    }
    if(!preg_match($parm2,$_POST['password'])){
        echo "6";
        exit;
    }
    if($_POST['repassword']!=$_POST['password']){
        echo "7";
        exit;
    }
    $_POST['password']=md5($_POST['password']);
    $sql="update sk_member set usrpwd='".addslashes($_POST['password'])."',phone_tel='".$_POST['mobile']."' where open_id='$openid'";
    if($do->dealsql($sql)){
        echo 1;
        unset($_SESSION['check']['msm']);
        unset($_SESSION['confirm']);
    }
}