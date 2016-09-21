<?php
session_start();
//include '../base/condbwei.php';
//include '../base/commonFun.php';
include '../base/sms/CommonMSM.php';
$cxtPath = "http://" . $_SERVER['HTTP_HOST'];
if (!isset($_SESSION["access_token"])) {
    header("Location:" . $cxtPath . "/wei/login.php");
    return;
}

$verify = new CommonMSM();
if (empty($_POST['mobile'])) {
    echo 0;
} else {
    if (isset($_SESSION['check']['msm'])) {
        $msm = $_SESSION['check']['msm'];
        $time = time() - $msm['time'];
        if ($time > 60) {
            unset($_SESSION['check']['msm']);
            echo $verify->msmVerify($_POST['mobile'], '77786');
        } else {
            echo 3;
        }
    } else {
        echo $verify->msmVerify($_POST['mobile'],'77786');
    }
}


?>