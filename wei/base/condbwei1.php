<?php
class condbwei {
// 	public $serverName = "localhost";
// 	public $uid = "root";
// 	public $pwd = "qiu";
// 	public $database = "suoka";

	public $serverName = "localhost";
	public $uid = "root";
	public $pwd = "7bea8eacd0";
	public $database = "zhangzongweisha";

	function connectdb(){
		$conn=mysql_connect($this->serverName, $this->uid, $this->pwd);
		mysql_query("SET NAMES 'UTF8'");
		return $conn;
	}
	function getone($sql){
		$conn = $this->connectdb();
		mysql_select_db($this->database,$conn);
		$result = mysql_query($sql);
		return $resArray=mysql_fetch_assoc($result);
	}
	function query($sql){
		$conn = $this->connectdb();
		mysql_select_db($this->database,$conn);
		return mysql_query($sql);
	}
	function sql_cunchu($openid){
		$conn = $this->connectdb();
		mysql_select_db($this->database,$conn);
		mysql_query("call sk_member(@return_012,@openid,'$openid');");
		$result = mysql_query("select @return_012 as joingat,@openid pid;");
		$tag = false;
		while($resArray = mysql_fetch_assoc($result)){
			$tag = true;
			$conData[] = $resArray;
		}
		mysql_close($conn);
		if($tag == true){
			return $conData;
		}else{
			return null;
		}
	}
	function selectsql($sql){
		$conn = $this->connectdb();
		mysql_select_db($this->database,$conn);
		$result = mysql_query($sql);
		$tag = false;
		while($resArray = mysql_fetch_assoc($result)){
			$tag = true;
			$conData[] = $resArray;
		}
		mysql_close($conn);
		if($tag == true){
			return $conData;
		}else{
			return null;
		}
	}
	function selectSingleSql($sql, $col){
		$conn = $this->connectdb();
		mysql_select_db($this->database,$conn);
		$result = mysql_query($sql);
		$tag = false;
		while($resArray = mysql_fetch_array($result)){
			$tag = true;
			$conData[] = $resArray[$col];
		}
		mysql_close($conn);
		if($tag == true){
			return $conData;
		}else{
			return null;
		}
	}
	function dealsql($sql){
		$conn = $this->connectdb();
		mysql_select_db($this->database,$conn);
		$count = mysql_query($sql);
		mysql_close($conn);
		if($count>0){
			return true;
		}else{
			return false;
		}
	}
	function sqlTableId($table, $col){
		$conn = $this->connectdb();
		mysql_select_db($this->database,$conn);
		$result = mysql_query("SELECT MAX($col) AS $col FROM $table");
		$tag = false;
		while($resArray = mysql_fetch_array($result)){
			$tag = true;
			$conData[] = $resArray;
		}
		mysql_close($conn);
		if($tag == true){
			return intval($conData[0][$col])+1;
		}else{
			return 0;
		}
	}
	function list_tables($database){
		$rs = mysql_query("SHOW TABLES FROM $database");
		$tables = array();
		while ($row = mysql_fetch_array($rs)){
			if($row[0]=="adv_type_tot" || $row[0]=="content_tot" || $row[0]=="life_tot" || $row[0]=="nodeadv" || $row[0]=="nodeser" || $row[0]=="service_type_tot" ) continue;
			$tables[] = $row[0];
		}
		$tables[] = "content_tot";
		$tables[] = "life_tot";
		$tables[] = "nodeser";
		$tables[] = "service_type_tot";
		mysql_free_result($rs);
		return $tables;
	}
	function gohtml($content){
	    if (is_array($content))	{
	        foreach ($content as $key=>$value){
	            $content[$key] = addslashes($value);
	        }
	    }else{
	        $content=addslashes($content);
	    }
	    return htmlspecialchars($content);
	}
	function uphtml($content){
	    if (is_array($content))	{
	        foreach ($content as $key=>$value){
	            $content[$key] = addslashes($value);
	        }
	    }else{
	        $content=addslashes($content);
	    }
	    return htmlspecialchars_decode($content);
	}
	function weixintishi($openid,$weixintxt){
	    $token_url = $this->token();
	    //用公共号发送信息
	    $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token_url;
	    $data = "{
                        \"touser\": \"".$openid."\",
                        \"msgtype\": \"text\",
                        \"text\": {
                            \"content\": \"".htmlspecialchars($weixintxt)."\"
                        }
                    }";
	    
	    $ch = curl_init ();
	    curl_setopt ( $ch, CURLOPT_URL, $url );
	    curl_setopt ( $ch, CURLOPT_POST, 1 );
	    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	    $return = curl_exec ( $ch );
	    curl_close ( $ch );
	    $output_array = json_decode($return,true);
	}
	function randomkeys($length) {
	    $returnStr='';
	    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
	    for($i = 0; $i < $length; $i ++) {
	        $returnStr .= $pattern {mt_rand ( 0, 61 )};
	    }
	    return $returnStr;
	}
	function token(){
		$token_txt = $this -> getone("select * from sk_weixin_token");
		$token_url = $token_txt['token'];
		if((time() - $token_txt['time'])>7100){//数据库里 微信token 已过期  需要跟新
			$ch = curl_init();
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx8b17740e4ea78bf5&secret=bbd06a32bdefc1a00536760eddd1721d";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$return = curl_exec ( $ch );
			curl_close ( $ch );
			$output_array = json_decode($return,true);
			$token_url = $output_array['access_token'];
			$data_up['time'] = time();
			$this -> dealsql("update sk_weixin_token set time=".time().",token='".$output_array['access_token']."' where id='".$token_txt['id']."'");
		}
		return $token_url;
	}
}
?>