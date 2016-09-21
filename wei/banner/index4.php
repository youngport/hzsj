<?php 
include '../base/condbwei.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST']."/";

$do = new condbwei();
$mac=isset($_POST['mac'])?addslashes($_POST['mac']):"0";
$sql = "select gg_imgurl as banner_url from sk_guanggao where gg_youxiao=1 order by gg_paixu asc";
$bannerArr = $do->selectsql($sql);
$sql = "select vi_url as video_url from sk_video where (vi_rec='' or vi_rec='$mac') and vi_youxiao=1 order by vi_paixu asc ";
$videoArr = $do->selectsql($sql);
if($videoArr||$bannerArr)
    $code = "1";
else 
    $code = "0";
foreach($bannerArr as $key=>$val){
    $bannerArr[$key]['banner_url']=$cxtPath.$val["banner_url"];
}
foreach($videoArr as $key=>$val){
    $name=explode("/",$val["video_url"]);
    $data_arr[$key]['video_name']=$name[count($name)-1];
}
$data = array(
    'code' => $code,
    'result' => array(
        'banner' => $bannerArr,
        'video' => $videoArr,
        'name'  => $data_arr
    )
);
exit(json_encode($data));
?>
