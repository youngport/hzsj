<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

$openid = $_SESSION["openid"];
$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

$sql="select e.rmac from sk_member m join sk_orders o on m.open_id = o.buyer_id join sk_erweima e on e.dingdan=o.order_id where m.open_id='$openid' and o.status=4 and o.erm=1";
$xiaoxiArr = $do->selectsql($sql);
if($xiaoxiArr[0]['rmac'] != ""){
    if($_POST['xuan']==0){
        include '../../base/luyoujiekou.php';
        $ly = new luyoujiekou();
        $data = date('Y-m-d',time()-86400);
        $zx_tu = "";//新访客
        $sy_tj = 0;//新访客
        $jiu_fanke = 0;//旧访客
        $jiu_fanke_tu = "";//旧访客
        $total_fanke = 0;//总访客
        $total_fanke_tu = "";//总访客
        $get_new=0;//进入商城新访客
        $get_old=0;//进入商城旧访客
        $data_post = '{"apMac":"'.$xiaoxiArr[0]['rmac'].'","Timeslot":';
        $json_time='[';
        for($i=0; $i<6; $i++){
            if(time()>=(strtotime($data)+14400*$i)){
                if($i==0){
                    $json_time .= '{"startTime":"'.$data.' 00:00:00'.'","endTime":"'.$data.' 04:00:00'.'"},';
                }elseif($i==1){
                    $json_time .= '{"startTime":"'.$data.' 04:00:00'.'","endTime":"'.$data.' 08:00:00'.'"},';
                }elseif($i==2){
                    $json_time .= '{"startTime":"'.$data.' 08:00:00'.'","endTime":"'.$data.' 12:00:00'.'"},';
                }elseif($i==3){
                    $json_time .= '{"startTime":"'.$data.' 12:00:00'.'","endTime":"'.$data.' 16:00:00'.'"},';
                }elseif($i==4){
                    $json_time .= '{"startTime":"'.$data.' 16:00:00'.'","endTime":"'.$data.' 20:00:00'.'"},';
                }elseif($i==5){
                    $json_time .= '{"startTime":"'.$data.' 20:00:00'.'","endTime":"'.$data.' 24:00:00'.'"},';
                }
            }
        }
        $json_time=substr($json_time, 0, strlen($json_time)-1)."]";
        $data_post.=$json_time."}";

        //新访客
        $sjd_int = $ly -> GetNewVistors($data_post);
        $sjd_int = json_decode($sjd_int, true);
        foreach($sjd_int as $key=>$val){
            $sy_tj += $sjd_int[$key]["total"];
            $zx_tu .= $sjd_int[$key]["total"].",";
        } 
        //旧访客
        $jiu_int = $ly -> GetOldVistors($data_post);
        $jiu_int = json_decode($jiu_int, true);
        foreach($jiu_int as $key=>$val){
            $jiu_fanke += $jiu_int[$key]["total"];
            $jiu_fanke_tu .= $jiu_int[$key]["total"].",";
        }
        //总访客
        /* $zfk_int = $ly -> GetAllVistors($data_post);
        $zfk_int = json_decode($zfk_int, true);
        foreach($zfk_int as $key=>$val){
            $total_fanke += $zfk_int[$key]["total"];
            $total_fanke_tu .= $zfk_int[$key]["total"].",";
        } */


        //进入商场新旧访客
        $getdata = $ly -> Getreqlog($xiaoxiArr[0]['rmac'],json_decode($json_time));
        foreach($getdata as $v){
            $get_new+=$v['xin'];
            $get_old+=$v['lao'];
        }
        
        $zx_tu = substr($zx_tu, 0, strlen($zx_tu)-1);
        $jiu_fanke_tu = substr($jiu_fanke_tu, 0, strlen($jiu_fanke_tu)-1);
        //$total_fanke_tu = substr($total_fanke_tu, 0, strlen($total_fanke_tu)-1);
        $data_arr = array(
            'zx_tu' => $zx_tu,//新访客
            'sy_tj' => $sy_tj,//新访客
            'jiu_fanke' => $jiu_fanke,//旧访客
            'jiu_fanke_tu' => $jiu_fanke_tu,//旧访客
            //'total_fanke' => $total_fanke,//总访客
            //'total_fanke_tu' => $total_fanke_tu//总访客
            'get_new'=>$get_new,
            'get_old'=>$get_old
        );
        exit(json_encode($data_arr));
    }else if($_POST['xuan']==1){
        /* 客流数据获取 */
        include '../../base/luyoujiekou.php';
        $ly = new luyoujiekou();
        $zx_tu = "";//新访客
        $sy_tj = 0;//新访客
        $jiu_fanke = 0;//旧访客
        $jiu_fanke_tu = "";//旧访客
        $total_fanke = 0;//总访客
        $total_fanke_tu = "";//总访客
        $xiabiao_tu = "";//图片x轴
        $get_new=0;//进入商城新访客
        $get_old=0;//进入商城旧访客
        $weekarray=array("日","一","二","三","四","五","六");
        switch($weekarray[date("w")]){
            case "日":
                $jit_i=6;
            break;
            case "一":
                $jit_i=0;
            break;
            case "二":
                $jit_i=1;
            break;
            case "三":
                $jit_i=2;
            break;
            case "四":
                $jit_i=3;
            break;
            case "五":
                $jit_i=4;
            break;
            case "六":
                $jit_i=5;
            break;
        }
        
        
        $data_post = '{"apMac":"'.$xiaoxiArr[0]['rmac'].'","Timeslot":';
        $json_time='[';
        for($i=$jit_i; $i>=0; $i--){
            $data = date("Y-m-d",strtotime("-".$i." day"));
            $xiabiao_tu .= "'$data',";
            $end_data = date("Y-m-d",strtotime("-".($i-1)." day"));
            $starttime = $data.' 00:00:00';
            $endtime = $end_data.' 00:00:00';

            $json_time .= '{"startTime":"'.$data.' 00:00:00'.'","endTime":"'.$end_data.' 00:00:00'.'"},';
        }
        $json_time=substr($json_time, 0, strlen($json_time)-1)."]";
        $data_post.=$json_time."}";
        //print_r($data_post);exit;
        //新访客
        $sjd_int = $ly -> GetNewVistors($data_post);
        $sjd_int = json_decode($sjd_int, true);
        foreach($sjd_int as $key=>$val){
            $sy_tj += $sjd_int[$key]["total"];
            $zx_tu .= $sjd_int[$key]["total"].",";
        }
        //旧访客
        $jiu_int = $ly -> GetOldVistors($data_post);
        $jiu_int = json_decode($jiu_int, true);
        foreach($jiu_int as $key=>$val){
            $jiu_fanke += $jiu_int[$key]["total"];
            $jiu_fanke_tu .= $jiu_int[$key]["total"].",";
        }

        //进入商场新旧访客
        $getdata = $ly -> Getreqlog($xiaoxiArr[0]['rmac'],json_decode($json_time));
        foreach($getdata as $v){
            $get_new+=$v['xin'];
            $get_old+=$v['lao'];
        }

        $zx_tu = substr($zx_tu, 0, strlen($zx_tu)-1);
        $jiu_fanke_tu = substr($jiu_fanke_tu, 0, strlen($jiu_fanke_tu)-1);
        $xiabiao_tu = substr($xiabiao_tu, 0, strlen($xiabiao_tu)-1);
        
        $data_arr = array(
            'zx_tu' => $zx_tu,//新访客
            'sy_tj' => $sy_tj,//新访客
            'jiu_fanke' => $jiu_fanke,//旧访客
            'jiu_fanke_tu' => $jiu_fanke_tu,//旧访客
            'xiabiao_tu' => $xiabiao_tu,//图片x轴
            'get_new'=>$get_new,
            'get_old'=>$get_old
        );
        exit(json_encode($data_arr));
    }else if($_POST['xuan']==2){
        /* 客流数据获取 */
        include '../../base/luyoujiekou.php';
        $ly = new luyoujiekou();
        $data = date('Y-m-d',time());
        $zx_tu = "";//新访客
        $sy_tj = 0;//新访客
        $jiu_fanke = 0;//旧访客
        $jiu_fanke_tu = "";//旧访客
        $total_fanke = 0;//总访客
        $total_fanke_tu = "";//总访客
        $get_new=0;//进入商城新访客
        $get_old=0;//进入商城旧访客
        
        $sy_t = date('d');
        
        
        

        $data_post = '{"apMac":"'.$xiaoxiArr[0]['rmac'].'","Timeslot":';
        $json_time='[';
        while ($sy_t>0) {
            $data = date("Y-m-d",strtotime("-".($sy_t-1)." day"));
            if(date("Y-m-d",strtotime("-".($sy_t-7)." day"))>date("Y-m-d"))
                $end_data = date("Y-m-d");
            else
                $end_data = date("Y-m-d",strtotime("-".($sy_t-7)." day"));
            $xiabiao_tu .= "'".$data." 00:00:00至".$end_data." 00:00:00',";
            $starttime = $data.' 00:00:00';
            $endtime = $end_data.' 00:00:00';
        
            $json_time .= '{"startTime":"'.$data.' 00:00:00'.'","endTime":"'.$end_data.' 00:00:00'.'"},';
            $sy_t -=6;
        }
        $json_time=substr($json_time, 0, strlen($json_time)-1)."]";
        $data_post.= $json_time."}";
        
        //新访客
        $sjd_int = $ly -> GetNewVistors($data_post);
        $sjd_int = json_decode($sjd_int, true);
        foreach($sjd_int as $key=>$val){
            $sy_tj += $sjd_int[$key]["total"];
            $zx_tu .= $sjd_int[$key]["total"].",";
        }
        //旧访客
        $jiu_int = $ly -> GetOldVistors($data_post);
        $jiu_int = json_decode($jiu_int, true);
        foreach($jiu_int as $key=>$val){
            $jiu_fanke += $jiu_int[$key]["total"];
            $jiu_fanke_tu .= $jiu_int[$key]["total"].",";
        }


        //进入商场新旧访客
        $getdata = $ly -> Getreqlog($xiaoxiArr[0]['rmac'],json_decode($json_time));
        foreach($getdata as $v){
            $get_new+=$v['xin'];
            $get_old+=$v['lao'];
        }


        $zx_tu = substr($zx_tu, 0, strlen($zx_tu)-1);
        $jiu_fanke_tu = substr($jiu_fanke_tu, 0, strlen($jiu_fanke_tu)-1);
        $xiabiao_tu = substr($xiabiao_tu, 0, strlen($xiabiao_tu)-1);
        
        /* 客流数据获取 */
        $data_arr = array(
            'zx_tu' => $zx_tu,//新访客
            'sy_tj' => $sy_tj,//新访客
            'jiu_fanke' => $jiu_fanke,//旧访客
            'jiu_fanke_tu' => $jiu_fanke_tu,//旧访客
            'xiabiao_tu' => $xiabiao_tu,//图片x轴
            'get_new'=>$get_new,
            'get_old'=>$get_old
        );
        exit(json_encode($data_arr));
    }
}else{
    $data_arr = array(
        'mac' => 0//新访客
    );
    exit(json_encode($data_arr));
}

?>