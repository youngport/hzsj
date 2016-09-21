<?php
	include './condbwei.php';
	$do = new condbwei();
	if($_GET['bianhao']){
		$bianhao = $_GET['bianhao'];
		if(preg_match('/^\d*$/',$bianhao)){
			$sql = "select * from sk_erweima where bianhao = '".$bianhao."'";//"and dingdan = '".$_POST['dingdan']."'";
			$co = $do->selectsql($sql);
			if(count($co)>0)
				echo 1;
			else
				echo 0;
		}
		else
			echo 0;
	}else
		echo 0;
//输出0错误或不存在  1该编号存在