<?php
setcookie();
if(isset($_POST['add_canshu']) && trim($_POST['add_canshu'])){
    $add_canshu = trim($_POST['add_canshu']);
    $cookie_up = $_COOKIE["fenxiang"] ;

    $ke_add = true;
    $arr = explode(",",$cookie_up);
    for($i=0;$i<count($arr);$i++){
        if($arr[$i] == $add_canshu)
            $ke_add = false;
    }
    if($ke_add){
        if($cookie_up=="")
            $cookie_up .= $add_canshu;
        else
            $cookie_up .= ",".$add_canshu;
        setcookie("fenxiang",$cookie_up, time()+3600*24);
    }
}else{
    setcookie("fenxiang","", 3600);
}
?>