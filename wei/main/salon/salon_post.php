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
if($_POST){
    $name=addslashes($_POST['name']);
    $mobile=addslashes($_POST['mobile']);
    $status=1;
    $parm1="/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]{2,8}$/u";
    $parm2="/^(\w){6,12}$/";
    if($name!=''){
        if (!preg_match($parm1, $name)) {
            $status = 4;
        }
    }else{
        $status=3;
    }
    if($mobile!='') {
        if (!preg_match($parm2, $mobile)) {
            $status = 5;
        }
    }else{
        $status=6;
    }
    if($status==1){
        $sql="insert into sk_salon(name,mobile,addtime) values('".$name."','".$mobile."','".time()."')";
        $result=$do->dealsql($sql);
        if($result===false){
            $status=0;
        }
    }

    echo $status;
}