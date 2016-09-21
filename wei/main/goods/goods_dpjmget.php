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
$subscribe = $_SESSION["subscribe"];

$do = new condbwei();
$com = new commonFun();

$shifoudingji = $do -> selectsql("select pid,jointag from sk_member where open_id ='$openid'");
if(($shifoudingji[0]['pid'] == "" || $shifoudingji[0]['pid'] == null) && $shifoudingji[0]['jointag'] != 2){
    echo "{\"success\":\"1\"}";
}else
    echo "{\"success\":\"2\"}";