<?php 
//$url = "http://mooncake.neobv.com:8020/mooncakev2/index.php/WifiClientSearch/GetNewVistors";
//$url = "http://mooncake.neobv.com:8020/mooncakev2/index.php/WifiClientSearch/GetOldVistors";
$url = "http://mooncake.neobv.com:8020/mooncakev2/index.php/WifiClientSearch/GetAllVistors";
$post_data='{"apMac":"0C:C6:55:01:69:A8","Timeslot":[{"startTime":"2015-12-24 06:00:00","endTime":"2015-12-26 08:00:00"},{"startTime":"2015-12-26 08:00:00","endTime":"2015-12-26 13:00:00"},{"startTime":"2015-12-26 14:00:00","endTime":"2015-12-26 16:00:00"},{"startTime":"2015-12-26 18:00:00","endTime":"2015-12-26 22:00:00"}]}';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
$output = curl_exec($ch);
curl_close($ch);

exit($output);
?>