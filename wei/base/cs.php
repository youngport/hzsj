<?php
include './luyoujiekou.php';
$ly = new luyoujiekou();
//echo $ly -> totalnumWifiProbe('0C:C6:55:01:6D:87','2014-1-1%2000:00:00','2015-12-8%2000:00:00')."<br />";

$data = date('Y-m-d',time());
$zx_tu = "";
$sy_tj = 0;
echo $data.'%2004:00:00'."<br />";
echo $data.'%2000:00:00'."<br />";
echo $ly -> totalnumWifiProbe('0C:C6:55:01:69:A8',$data.'%2000:00:00',$data.'%2004:00:00');
?>