<?php

$y=date("Y",time());
$m=date("m",time());
$d=date("d",time()); 
$start_time = mktime(8, 0, 0, $m, $d ,$y); 
//echo $y."   ".$m."   ".$d."   ".$start_time."   ".$end_time."<br />";
echo date('Y-m-d H:i:s',"$start_time")."<br />";//."   ".date('Y-m-d H:i:s',"$end_time")."<br />";
echo $time = time()."<br />";
echo date('Y-m-d',"$time")."<br />";
$aa = date('Y-m-d',"$time");
echo "??  ".strtotime($aa)."<br />";
$aa = strtotime($aa);
$aa = $aa+86400;
echo date('Y-m-d H:i:s',"$aa")."<br />";

echo $m = $time-$start_time."<br />";

echo $start_time = mktime(8, 0, 0, 9, 5 ,2015); 
echo date('Y-m-d H:i:s',"$start_time")."<br />";



$aa = date('Y-m-d',time());
$dangtian = strtotime($aa);//当天 0点时间戳
$xiatian = $dangtian+86400;//明天 0点时间戳
echo date('Y-m-d H:i:s',"$dangtian")."<br />";
echo date('Y-m-d H:i:s',"$xiatian")."<br />";