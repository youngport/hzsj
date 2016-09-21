<?php
session_start();
include '../../base/condbwei.php';
include '../../base/commonFun.php';
if (!isset($_SESSION["access_token"])) {
    header("Location:" . $cxtPath . "/wei/login.php");
    return;
}
$do = new condbwei();
$com = new commonFun();
$openid = $_SESSION['openid'];
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
//var_dump($_POST); die;
if (!empty($_POST)) {
    array_walk($_POST, "addslaches");
    $parm = "/^(13[0-9]|15[0-9]|18[0-9])\d{8}$/";
    $_SESSION['mobile']=$mobile=$_POST['mobile'];
    if (strlen($mobile) != 11 || !preg_match($parm, $mobile)) {
        echo "2";
        exit;
    }
    //$_SESSION['check']['msm']['code']=$this->getCode();
    //$_SESSION['check']['msm']['time']=time();
    if (empty($_SESSION['check']['msm'])) {
        echo "3";
        exit;
    }
    if ((time() - $_SESSION['check']['msm']['time']) > 600) {
        echo "4";
        exit;
    }
//    var_dump($_POST['voiceverify']);
//    echo "<br/>";
//    var_dump($_SESSION['check']['msm']);
//    die;
    if (strtolower($_POST['voiceverify']) != strtolower($_SESSION['check']['msm']['code'])) {
        echo "5";
        exit;
    }
    $count=$do->getone("select count(*) count from sk_member where open_id='$openid' and phone=$mobile");
    //die("select count(*) count from sk_member where open_id='$openid' and phone='$mobile'");
    if ($count['count']<1) {
        echo "6";
        exit;
    }
    echo 1;
    unset($_SESSION['check']['msm']);
    //$_SESSION['codeTrue']=1;
}

