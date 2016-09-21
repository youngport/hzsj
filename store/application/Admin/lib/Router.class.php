<?php
class router{

    //新访客数
     function GetNewVistors($post_data){
         $url = "http://mooncake.neobv.com:8020/mooncakev2/index.php/WifiClientSearch/GetNewVistors";    
         return $this -> post($url,$post_data);
     }
     //旧访客数
     function GetOldVistors($post_data){
         $url = "http://mooncake.neobv.com:8020/mooncakev2/index.php/WifiClientSearch/GetOldVistors";
         return $this -> post($url,$post_data);
     }
     //所有访客数
     function GetAllVistors($post_data){
         $url = "http://mooncake.neobv.com:8020/mooncakev2/index.php/WifiClientSearch/GetAllVistors";
         return $this -> post($url,$post_data);
     }
     //post发送
     function post($url,$post_data){
        set_time_limit(0);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output);
     }
}
?>