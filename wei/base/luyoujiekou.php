<?php
class luyoujiekou {

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
    //获取商场访问人数,因为接口那边工程师放假,接口先用GET提交...没法做新旧筛选,需要二次调用对比
    function Getreqlog($mac,$get_data,$domain='m.hz41319.com'){
        $url="http://mooncake.neobv.com:8020/wifiprobe/getreqlog.php?";
        $data=array();
        $oget=array("ap_mac"=>$mac,'starttime'=>'2016-1-1','endtime'=>'','domain'=>$domain);//根据需求starttime来获取过去的数据...
        foreach($get_data as $k=>$v){
            $get['starttime']=$v->startTime;
            $get['endtime']=$v->endTime;
            $oget['endtime']=$get['starttime'];
            $get['ap_mac']=$mac;
            $get['domain']=$domain;
            $now=json_decode($this->get($url.http_build_query($get)));//请求的时间段统计
            $old=$this->get($url.http_build_query($oget));
            $data[$k]['lao']=0;
            $data[$k]['xin']=0;
            if(!empty($now)) {
                foreach ($now as $i) {
                    if (stripos($old, $i->_id->mac) !== false) {
                        $data[$k]['lao']++;
                    }else
                        $data[$k]['xin']++;
                }
            }
        }
        return $data;
    }
     //post发送
     function post($url,$post_data){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
     }
    //get发送
    function get($url){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_BINARYTRANSFER,1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
?>