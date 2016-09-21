<?php

        include '../../base/luyoujiekou.php';
        $ly = new luyoujiekou();
        $data = date('Y-m-d',time()-86400);
        $zx_tu = "";//新访客
        $sy_tj = 0;//新访客
        $jiu_fanke = 0;//旧访客
        $jiu_fanke_tu = "";//旧访客
        $total_fanke = 0;//总访客
        $total_fanke_tu = "";//总访客
        //新客户
        $data_post = '{"apMac":"0C:C6:55:01:69:A8","Timeslot":[';
        for($i=0; $i<6; $i++){
            if(time()>=(strtotime($data)+14400*$i)){
                if($i==0){
                     $data_post .= '{"startTime":"'.$data.' 00:00:00'.'","endTime":"'.$data.' 04:00:00'.'"},';
                }elseif($i==1){
                     $data_post .= '{"startTime":"'.$data.' 04:00:00'.'","endTime":"'.$data.' 08:00:00'.'"},';
                }elseif($i==2){
                     $data_post .= '{"startTime":"'.$data.' 08:00:00'.'","endTime":"'.$data.' 12:00:00'.'"},';
                }elseif($i==3){
                     $data_post .= '{"startTime":"'.$data.' 12:00:00'.'","endTime":"'.$data.' 16:00:00'.'"},';
                }elseif($i==4){
                     $data_post .= '{"startTime":"'.$data.' 16:00:00'.'","endTime":"'.$data.' 20:00:00'.'"},';
                }elseif($i==5){
                     $data_post .= '{"startTime":"'.$data.' 20:00:00'.'","endTime":"'.$data.' 24:00:00'.'"},';
                }
            }
        }
        $data_post = substr($data_post, 0, strlen($data_post)-1)."]}";

        //新访客
        $sjd_int = $ly -> GetNewVistors($data_post);
        exit($sjd_int);
        $sjd_int = json_decode($sjd_int, true);
        foreach($sjd_int as $key=>$val){
            $sy_tj += $val[$key]["total"];
            $zx_tu .= $val[$key]["total"].",";
        }
        //旧访客
        $jiu_int = $ly -> GetOldVistors($data_post);
        $jiu_int = json_decode($jiu_int, true);
        foreach($jiu_int as $key=>$val){
            $jiu_fanke += $val[$key]["total"];
            $jiu_fanke_tu .= $val[$key]["total"].",";
            

            //总访客
            $l_int = $jiu_int[$key]['total']+$sjd_int[$key]['total'];
            $total_fanke += $l_int;
            $total_fanke_tu .= $l_int.",";
        }
        
        
        
        $zx_tu = substr($zx_tu, 0, strlen($zx_tu)-1);
        $jiu_fanke_tu = substr($jiu_fanke_tu, 0, strlen($jiu_fanke_tu)-1);
        $total_fanke_tu = substr($total_fanke_tu, 0, strlen($total_fanke_tu)-1);
        $data_arr = array(
            'zx_tu' => $zx_tu,//新访客
            'sy_tj' => $sy_tj,//新访客
            'jiu_fanke' => $jiu_fanke,//旧访客
            'jiu_fanke_tu' => $jiu_fanke_tu,//旧访客
            'total_fanke' => $total_fanke,//总访客
            'total_fanke_tu' => $total_fanke_tu//总访客
        );
        echo(json_encode($data_arr))

?>