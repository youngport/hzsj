<?php
include '../../base/condbwei.php';
$do = new condbwei();

$uploaddir = '../../demo/uploads/';
$uploadfile = $uploaddir. $_FILES['file']['name'];

$arr = explode(".",$_FILES['file']['name']);
$img_name = time().mt_rand(100,999).".".$arr[count($arr)-1];
$couarr = $do->selectsql("select count(*) cou from sk_lfx_img where imgurl = 'wei/demo/uploads/".$img_name."'");

while($couarr[0]['cou']>0){
    $img_name = time().mt_rand(100,999).".".$arr[count($arr)-1];
    $couarr = $do->selectsql("select count(*) cou from sk_lfx_img where imgurl = 'wei/demo/uploads/".$img_name."'");
}


//move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $_FILES['file']['name']);//$_FILES['file']['name']  name + 后缀
move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $img_name);

echo "wei/demo/uploads/".$img_name;

?>