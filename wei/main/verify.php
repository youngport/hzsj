<?php
session_start();
//include '../base/condbwei.php';
//include '../base/commonFun.php';
include '../base/verify/verify.class.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}
$config=array();
if(!empty($_GET)) {
    $config = $_GET;
}
$verify=new verify($config);

if(empty($_POST['mobile']))
    $verify->verify();
else {
    if(isset($_SESSION['check']['voiceverify'])){
        $voice=$_SESSION['check']['voiceverify'];
        $time=time()-$voice['time'];
        if($time>60){
            unset($_SESSION['check']['voiceverify']);
            echo $verify->voiceverify($_POST['mobile'],$config);
        }else{
            echo 3;
        }
    }else{
        echo $verify->voiceverify($_POST['mobile'],$config);
    }
}
?>