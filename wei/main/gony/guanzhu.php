<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
<?php
session_start();
$subscribe = $_SESSION["subscribe"];
if($subscribe == "0"){
?>
<div style="position:fixed;_position:absolute;top:0px;height:50px;width:100%;background:red;z-index:100;background-color:#000000; background-color:rgba(0,0,0,0.5);">
	<img style="width:50px;height:50px;" src="<?php echo $_SESSION["tuiheadimgurl"]; ?>" />
	<span style="font-size:14px;color:white;"><?php echo $_SESSION["tuinickname"]; ?>推荐</span>
	<img onclick="closeGuan(this)" style="margin-top:10px;width:30px;height:30px;float:right;" src="<?php echo $cxtPath ?>/wei/images/multiply.png" />
	<a href="http://mp.weixin.qq.com/s?__biz=MzA4OTcyOTM5NQ==&mid=209415610&idx=1&sn=e073ffaa48bacda621dadecf9966123f#rd"><div style="margin-top:10px;margin-right:10px;width:90px;height:30px;float:right;background:#02b526;border-radius:5px;text-align:center;line-height:30px;font-size:14px;"><font style="color:white;">关注我们</font></div></a>
</div>
<?php
}
?>