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
header("content-type:text/html;charset=utf-8");
if (!empty($_POST)) {
    array_walk($_POST, "addslaches");
    $parm2 = "/^(\w){6,12}$/";
    if (!preg_match($parm2, $_POST['password'])) {
        echo "6";
        exit;
    }
    if ($_POST['repassword'] != $_POST['password']) {
        echo "7";
        exit;
    }
    $_POST['password'] = md5($_POST['password']);
    $mobile=$_SESSION['mobile'];
    $sql = "update sk_member set usrpwd='" . addslashes($_POST['password']) . "',phone_tel='" .$mobile . "' where open_id='$openid'";
    if ($do->dealsql($sql)) {
        echo 1;
        unset($_SESSION['check']['msm']);
        unset($_SESSION['confirm']);
    }
}