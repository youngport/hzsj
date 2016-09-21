<?php

/**
 * 获取当前登录的管事员id
 * @return int
 */
function get_current_admin_id(){
	return $_SESSION['ADMIN_ID'];
}

/**
 * 获取当前登录的管事员id
 * @return int
 */
function sp_get_current_admin_id(){
	return get_current_admin_id();
}

/**
 * 获取当前登录前台用户id,推荐使用sp_get_current_userid
 * @return int
 */
function get_current_userid(){

	if(isset($_SESSION['user'])){
		return $_SESSION['user']['id'];
	}else{
		return 0;
	}
}

/**
 * 获取当前登录前台用户id
 * @return int
 */
function sp_get_current_userid(){
	return get_current_userid();
}

/**
 * 返回带协议的域名
 */
function sp_get_host(){
	$host=$_SERVER["HTTP_HOST"];
	$protocol=is_ssl()?"https://":"http://";
	return $protocol.$host;
}

/**
 * CMF密码加密方法
 * @param string $pw 要加密的字符串
 * @return string
 */
function sp_password($pw){
	$decor=md5(C('DB_PREFIX'));
	$mi=md5($pw);
	return substr($decor,0,12).$mi.substr($decor,-4,4);
}


function sp_log($content,$file="log.txt"){
	file_put_contents($file, $content,FILE_APPEND);
}

/**
 * 随机字符串生成
 * @param int $len 生成的字符串长度
 * @return string
 */
function sp_random_string($len = 6) {
	$chars = array(
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
			"l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
			"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
			"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
			"S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
			"3", "4", "5", "6", "7", "8", "9"
	);
	$charsLen = count($chars) - 1;
	shuffle($chars);    // 将数组打乱
	$output = "";
	for ($i = 0; $i < $len; $i++) {
		$output .= $chars[mt_rand(0, $charsLen)];
	}
	return $output;
}

/**
 * 清空系统缓存，兼容sae
 */
function sp_clear_cache(){
		import ( "ORG.Util.Dir" );
		$dirs = array ();
		// runtime/
		$rootdirs = sp_scan_dir( RUNTIME_PATH."*" );
		//$noneed_clear=array(".","..","Data");
		$noneed_clear=array(".","..");
		$rootdirs=array_diff($rootdirs, $noneed_clear);
		foreach ( $rootdirs as $dir ) {
			
			if ($dir != "." && $dir != "..") {
				$dir = RUNTIME_PATH . $dir;
				if (is_dir ( $dir )) {
					//array_push ( $dirs, $dir );
					$tmprootdirs = sp_scan_dir ( $dir."/*" );
					foreach ( $tmprootdirs as $tdir ) {
						if ($tdir != "." && $tdir != "..") {
							$tdir = $dir . '/' . $tdir;
							if (is_dir ( $tdir )) {
								array_push ( $dirs, $tdir );
							}else{
								@unlink($tdir);
							}
						}
					}
				}else{
					@unlink($dir);
				}
			}
		}
		$dirtool=new \Dir("");
		foreach ( $dirs as $dir ) {
			$dirtool->delDir ( $dir );
		}
		
		if(sp_is_sae()){
			$global_mc=@memcache_init();
			if($global_mc){
				$global_mc->flush();
			}
			
			$no_need_delete=array("THINKCMF_DYNAMIC_CONFIG");
			$kv = new SaeKV();
			// 初始化KVClient对象
			$ret = $kv->init();
			// 循环获取所有key-values
			$ret = $kv->pkrget('', 100);
			while (true) {
				foreach($ret as $key =>$value){
                    if(!in_array($key, $no_need_delete)){
                    	$kv->delete($key);
                    }
                }
				end($ret);
				$start_key = key($ret);
				$i = count($ret);
				if ($i < 100) break;
				$ret = $kv->pkrget('', 100, $start_key);
			}
			
		}
	
}

/**
 * 保存数组变量到php文件
 */
function sp_save_var($path,$value){
	$ret = file_put_contents($path, "<?php\treturn " . var_export($value, true) . ";?>");
	return $ret;
}

/**
 * 更新系统配置文件
 * @param array $data <br>如：array("URL_MODEL"=>0);
 * @return boolean
 */
function sp_set_dynamic_config($data){
	
	if(!is_array($data)){
		return false;
	}
	
	if(sp_is_sae()){
		$kv = new SaeKV();
		$ret = $kv->init();
		$configs=$kv->get("THINKCMF_DYNAMIC_CONFIG");
		$configs=empty($configs)?array():unserialize($configs);
		$configs=array_merge($configs,$data);
		$result = $kv->set('THINKCMF_DYNAMIC_CONFIG', serialize($configs));
	}elseif(defined('IS_BAE') && IS_BAE){
		$bae_mc=new BaeMemcache();
		$configs=$bae_mc->get("THINKCMF_DYNAMIC_CONFIG");
		$configs=empty($configs)?array():unserialize($configs);
		$configs=array_merge($configs,$data);
		$result = $bae_mc->set("THINKCMF_DYNAMIC_CONFIG",serialize($configs),MEMCACHE_COMPRESSED,0);
	}else{
		$config_file="./data/conf/config.php";
		if(file_exists($config_file)){
			$configs=include $config_file;
		}else {
			$configs=array();
		}
		$configs=array_merge($configs,$data);
		$result = file_put_contents($config_file, "<?php\treturn " . var_export($configs, true) . ";?>");
	}
	sp_clear_cache();
	return $result;
}


/**
 * 生成参数列表,以数组形式返回
 */
function sp_param_lable($tag = ''){
	$param = array();
	$array = explode(';',$tag);
	foreach ($array as $v){
		$v=trim($v);
		if(!empty($v)){
			list($key,$val) = explode(':',$v);
			$param[trim($key)] = trim($val);
		}
	}
	return $param;
}


/**
 * 获取后台管理设置的网站信息，此类信息一般用于前台，推荐使用sp_get_site_options
 */
function get_site_options(){
	$site_options = F("site_options");
	if(empty($site_options)){
		$options_obj = M("Options");
		$option = $options_obj->where("option_name='site_options'")->find();
		if($option){
			$site_options = json_decode($option['option_value'],true);
		}else{
			$site_options = array();
		}
		F("site_options", $site_options);
	}
	$site_options['site_tongji']=htmlspecialchars_decode($site_options['site_tongji']);
	return $site_options;	
}

/**
 * 获取CMF系统的设置，此类设置用于全局
 * @param string $key 设置key，为空时返回所有配置信息
 * @return mixed
 */
function sp_get_cmf_settings($key=""){
	$cmf_settings = F("cmf_settings");
	if(empty($cmf_settings)){
		$options_obj = M("Options");
		$option = $options_obj->where("option_name='cmf_settings'")->find();
		if($option){
			$cmf_settings = json_decode($option['option_value'],true);
		}else{
			$cmf_settings = array();
		}
		F("cmf_settings", $cmf_settings);
	}
	if(!empty($key)){
		return $cmf_settings[$key];
	}
	return $cmf_settings;
}

/**
 * 更新CMF系统的设置，此类设置用于全局
 * @param array $data 
 * @return boolean
 */
function sp_set_cmf_setting($data){
	if(!is_array($data) || empty($data) ){
		return false;
	}
	$cmf_settings['option_name']="cmf_settings";
	$options_model=M("Options");
	$find_setting=$options_model->where("option_name='cmf_settings'")->find();
	F("cmf_settings",null);
	if($find_setting){
		$setting=json_decode($find_setting['option_value'],true);
		if($setting){
			$setting=array_merge($setting,$data);
		}else {
			$setting=$data;
		}
		
		$cmf_settings['option_value']=json_encode($setting);
		return $options_model->where("option_name='cmf_settings'")->save($cmf_settings);
	}else{
		$cmf_settings['option_value']=json_encode($data);
		return $options_model->add($cmf_settings);
	}
}




/**
 * 全局获取验证码图片
 * 生成的是个HTML的img标签
 * @param string $imgparam <br>
 * 生成图片样式，可以设置<br>
 * length=4&font_size=20&width=238&height=50&use_curve=1&use_noise=1<br>
 * length:字符长度<br>
 * font_size:字体大小<br>
 * width:生成图片宽度<br>
 * heigh:生成图片高度<br>
 * use_curve:是否画混淆曲线  1:画，0:不画<br>
 * use_noise:是否添加杂点 1:添加，0:不添加<br>
 * @param string $imgattrs<br>
 * img标签原生属性，除src,onclick之外都可以设置<br>
 * 默认值：style="cursor: pointer;" title="点击获取"<br>
 * @return string<br>
 * 原生html的img标签<br>
 * 注，此函数仅生成img标签，应该配合在表单加入name=verify的input标签<br>
 * 如：&lt;input type="text" name="verify"/&gt;<br>
 */
function sp_verifycode_img($imgparam='length=4&font_size=20&width=238&height=50&use_curve=1&use_noise=1',$imgattrs='style="cursor: pointer;" title="点击获取"'){
	$src=U('Api/Checkcode/index',$imgparam);
	$img=<<<hello
<img class="verify_img" src="$src" onclick="this.src='$src&time='+Math.random();" $imgattrs/>
hello;
	return $img;
}

/**
 * 去除字符串中的指定字符
 * @param string $str 待处理字符串
 * @param string $chars 需去掉的特殊字符
 * @return string
 */
function sp_strip_chars($str, $chars='?<*.>\'\"'){
	return preg_replace('/['.$chars.']/is', '', $str);
}

/**
 * 发送邮件
 * @param string $address
 * @param string $subject
 * @param string $message
 * @return array<br>
 * 返回格式：<br>
 * array(<br>
 * 	"error"=>0|1,//0代表出错<br>
 * 	"message"=> "出错信息"<br>
 * );
 */
function sp_send_email($address,$subject,$message){
	import("PHPMailer");
	$mail=new \PHPMailer();
	// 设置PHPMailer使用SMTP服务器发送Email
	$mail->IsSMTP();
	$mail->IsHTML(true);
	// 设置邮件的字符编码，若不指定，则为'UTF-8'
	$mail->CharSet='UTF-8';
	// 添加收件人地址，可以多次使用来添加多个收件人
	$mail->AddAddress($address);
	// 设置邮件正文
	$mail->Body=$message;
	// 设置邮件头的From字段。
	$mail->From=C('SP_MAIL_ADDRESS');
	// 设置发件人名字
	$mail->FromName=C('SP_MAIL_SENDER');;
	// 设置邮件标题
	$mail->Subject=$subject;
	// 设置SMTP服务器。
	$mail->Host=C('SP_MAIL_SMTP');
	// 设置SMTP服务器端口。
	$port=C('SP_MAIL_SMTP_PORT');
	$mail->Port=empty($port)?"25":$port;
	// 设置为"需要验证"
	$mail->SMTPAuth=true;
	// 设置用户名和密码。
	$mail->Username=C('SP_MAIL_LOGINNAME');
	$mail->Password=C('SP_MAIL_PASSWORD');
	// 发送邮件。
	if(!$mail->Send()) {
		$mailerror=$mail->ErrorInfo;
		return array("error"=>1,"message"=>$mailerror);
	}else{
		return array("error"=>0,"message"=>"success");
	}
}

/**
 * 转化数据库保存的文件路径，为可以访问的url
 * @param string $file
 * @param boolean $withhost
 * @return string
 */
function sp_get_asset_upload_path($file,$withhost=false){
	if(strpos($file,"http")===0){
		return $file;
	}else if(strpos($file,"/")===0){
		return $file;
	}else{
		$filepath=C("TMPL_PARSE_STRING.__UPLOAD__").$file;
		if($withhost){
			if(strpos($filepath,"http")!==0){
				$http = 'http://';
				$http =is_ssl()?'https://':$http;
				$filepath = $http.$_SERVER['HTTP_HOST'].$filepath;
			}
		}
		return $filepath;
		
	}                    			
                        		
}


function sp_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : C("AUTHCODE"));
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function sp_authencode($string){
	return sp_authcode($string,"ENCODE");
}

function sp_file_write($file,$content){
	
	if(sp_is_sae()){
		$s=new SaeStorage();
		$arr=explode('/',ltrim($file,'./'));
		$domain=array_shift($arr);
		$save_path=implode('/',$arr);
		return $s->write($domain,$save_path,$content);
	}else{
		try {
			$fp2 = @fopen( $file , "w" );
			fwrite( $fp2 , $content );
			fclose( $fp2 );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
}

function sp_get_relative_url($url){
	if(strpos($url,"http")===0){
		$url=str_replace(array("https://","http://"), "", $url);
		
		$pos=strpos($url, "/");
		if($pos===false){
			return "";
		}else{
			$url=substr($url, $pos+1);
			$root=preg_replace("/^\//", "", __ROOT__);
			$root=str_replace("/", "\/", $root);
			$url=preg_replace("/^".$root."\//", "", $url);
			return $url;
		}
	}
	return $url;
}


/**
 * URL组装 支持不同URL模式
 * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $domain 是否显示域名
 * @return string
 */
function leuu($url='',$vars='',$suffix=true,$domain=false){
	$routes=sp_get_routes();
	if(empty($routes)){
		return U($url,$vars,$suffix,$domain);
	}else{
		// 解析URL
		$info   =  parse_url($url);
		$url    =  !empty($info['path'])?$info['path']:ACTION_NAME;
		if(isset($info['fragment'])) { // 解析锚点
			$anchor =   $info['fragment'];
			if(false !== strpos($anchor,'?')) { // 解析参数
				list($anchor,$info['query']) = explode('?',$anchor,2);
			}
			if(false !== strpos($anchor,'@')) { // 解析域名
				list($anchor,$host)    =   explode('@',$anchor, 2);
			}
		}elseif(false !== strpos($url,'@')) { // 解析域名
			list($url,$host)    =   explode('@',$info['path'], 2);
		}
		
		// 解析子域名
		//TODO?
		
		// 解析参数
		if(is_string($vars)) { // aaa=1&bbb=2 转换成数组
			parse_str($vars,$vars);
		}elseif(!is_array($vars)){
			$vars = array();
		}
		if(isset($info['query'])) { // 解析地址里面参数 合并到vars
			parse_str($info['query'],$params);
			$vars = array_merge($params,$vars);
		}
		
		$vars_src=$vars;
		
		ksort($vars);
		
		// URL组装
		$depr       =   C('URL_PATHINFO_DEPR');
		$urlCase    =   C('URL_CASE_INSENSITIVE');
		if('/' != $depr) { // 安全替换
			$url    =   str_replace('/',$depr,$url);
		}
		// 解析模块、控制器和操作
		$url        =   trim($url,$depr);
		$path       =   explode($depr,$url);
		$var        =   array();
		$varModule      =   C('VAR_MODULE');
		$varController  =   C('VAR_CONTROLLER');
		$varAction      =   C('VAR_ACTION');
		$var[$varAction]       =   !empty($path)?array_pop($path):ACTION_NAME;
		$var[$varController]   =   !empty($path)?array_pop($path):CONTROLLER_NAME;
		if($maps = C('URL_ACTION_MAP')) {
			if(isset($maps[strtolower($var[$varController])])) {
				$maps    =   $maps[strtolower($var[$varController])];
				if($action = array_search(strtolower($var[$varAction]),$maps)){
					$var[$varAction] = $action;
				}
			}
		}
		if($maps = C('URL_CONTROLLER_MAP')) {
			if($controller = array_search(strtolower($var[$varController]),$maps)){
				$var[$varController] = $controller;
			}
		}
		if($urlCase) {
			$var[$varController]   =   parse_name($var[$varController]);
		}
		$module =   '';
		
		if(!empty($path)) {
			$var[$varModule]    =   array_pop($path);
		}else{
			if(C('MULTI_MODULE')) {
				if(MODULE_NAME != C('DEFAULT_MODULE') || !C('MODULE_ALLOW_LIST')){
					$var[$varModule]=   MODULE_NAME;
				}
			}
		}
		if($maps = C('URL_MODULE_MAP')) {
			if($_module = array_search(strtolower($var[$varModule]),$maps)){
				$var[$varModule] = $_module;
			}
		}
		if(isset($var[$varModule])){
			$module =   $var[$varModule];
		}
		
		if(C('URL_MODEL') == 0) { // 普通模式URL转换
			$url        =   __APP__.'?'.http_build_query(array_reverse($var));
			
			if($urlCase){
				$url    =   strtolower($url);
			}
			if(!empty($vars)) {
				$vars   =   http_build_query($vars);
				$url   .=   '&'.$vars;
			}
		}else{ // PATHINFO模式或者兼容URL模式
			
			if(empty($var[C('VAR_MODULE')])){
				$var[C('VAR_MODULE')]=MODULE_NAME;
			}
				
			$module_controller_action=strtolower(implode($depr,array_reverse($var)));
			
			$has_route=false;
			
			if(isset($routes[$module_controller_action])){
				$urlrules=$routes[$module_controller_action];
				
				$empty_query_urlrule=array();
				
				foreach ($urlrules as $ur){
					$intersect=array_intersect($ur['query'], $vars);
					if($intersect){
						$vars=array_diff_key($vars,$ur['query']);
						$url= $ur['url'];
						$has_route=true;
						break;
					}
					if(empty($empty_query_urlrule) && empty($ur['query'])){
						$empty_query_urlrule=$ur;
					}
				}
				
				if(!empty($empty_query_urlrule)){
					$url=$empty_query_urlrule['url'];
					foreach ($vars as $key =>$value){
						if(strpos($url, ":$key")!==false){
							$url=str_replace(":$key", $value, $url);
							unset($vars[$key]);
						}
					}
					$url=str_replace(array("\d","$"), "", $url);
					$has_route=true;
				}
				
				if($has_route){
					if(!empty($vars)) { // 添加参数
						foreach ($vars as $var => $val){
							if('' !== trim($val))   $url .= $depr . $var . $depr . urlencode($val);
						}
					}
					
					$url =__APP__."/".$url ;
					
				}
				
			
			}
			
			if(!$has_route){
				$module =   defined('BIND_MODULE') ? '' : $module;
				$url    =   __APP__.'/'.implode($depr,array_reverse($var));
					
				if($urlCase){
					$url    =   strtolower($url);
				}
					
				if(!empty($vars)) { // 添加参数
					foreach ($vars as $var => $val){
						if('' !== trim($val))   $url .= $depr . $var . $depr . urlencode($val);
					}
				}
			}
			
			
			if($suffix) {
				$suffix   =  $suffix===true?C('URL_HTML_SUFFIX'):$suffix;
				if($pos = strpos($suffix, '|')){
					$suffix = substr($suffix, 0, $pos);
				}
				if($suffix && '/' != substr($url,-1)){
					$url  .=  '.'.ltrim($suffix,'.');
				}
			}
		}
		
		if(isset($anchor)){
			$url  .= '#'.$anchor;
		}
		if($domain) {
			$url   =  (is_ssl()?'https://':'http://').$domain.$url;
		}
		
		return $url;
	}
}

/**
 * URL组装 支持不同URL模式
 * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $domain 是否显示域名
 * @return string
 */
function UU($url='',$vars='',$suffix=true,$domain=false){
	return leuu($url,$vars,$suffix,$domain);
}


function sp_get_routes($refresh=false){
	$routes=F("routes");
	
	if( (!empty($routes)||is_array($routes)) && !$refresh){
		return $routes;
	}
	$routes=M("Route")->where("status=1")->order("listorder asc")->select();
	$module_routes=array();
	$cache_routes=array();
	foreach ($routes as $er){
		$full_url=$er['full_url'];
			
		// 解析URL
		$info   =  parse_url($full_url);
			
		$path       =   explode("/",$info['path']);
		if(count($path)!=3){//必须是完整 url
			continue;
		}
			
		$module=strtolower($path[0]);
			
		// 解析参数
		$vars = array();
		if(isset($info['query'])) { // 解析地址里面参数 合并到vars
			parse_str($info['query'],$params);
			$vars = array_merge($params,$vars);
		}
			
		$vars_src=$vars;
			
		ksort($vars);
			
		$path=$info['path'];
			
		$full_url=$path.(empty($vars)?"":"?").http_build_query($vars);
			
		$url=$er['url'];
			
		$cache_routes[$path][]=array("query"=>$vars,"url"=>$url);
			
		$module_routes[$module][$url]=$full_url;
			
	}
	F("routes",$cache_routes);
	$route_dir=SITE_PATH."/data/conf/route/";
	foreach ($module_routes as $module => $routes){
		
		if(!file_exists($route_dir)){
			mkdir($route_dir);
		}
			
		$route_file=$route_dir."$module.php";
			
		$route_ruels=array();
			
		file_put_contents($route_file, "<?php\treturn " . stripslashes(var_export($routes, true)) . ";");
	}
	
	return $cache_routes;
	
	
}

/**
 * 判断是否为手机访问
 * @return  boolean
 */
function sp_is_mobile() {
	static $sp_is_mobile;

	if ( isset($sp_is_mobile) )
		return $sp_is_mobile;

	if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		$sp_is_mobile = false;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
		$sp_is_mobile = true;
	} else {
		$sp_is_mobile = false;
	}

	return $sp_is_mobile;
}

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook,$params=array()){
	tag($hook,$params);
}

/**
 * 替代scan_dir的方法
 * @param string $pattern 检索模式 搜索模式 *.txt,*.doc; (同glog方法)
 * @param int $flags
 */
function sp_scan_dir($pattern,$flags=null){
	$files = array_map('basename',glob($pattern, $flags));
	return $files;
}

/**
 * 获取所有钩子，包括系统，应用，模板
 */
function sp_get_hooks($refresh=false){
	if(!$refresh){
		$return_hooks = F('all_hooks');
		if(!empty($return_hooks)){
			return $return_hooks;
		}
	}
	
	$return_hooks=array();
	$system_hooks=array(
		"url_dispatch","app_init","app_begin","app_end",
		"action_begin","action_end","module_check","path_info",
		"template_filter","view_begin","view_end","view_parse",
		"view_filter","body_start","footer","footer_end","sider","comment",'admin_home'
	);
	
	$app_hooks=array();
	
	$apps=sp_scan_dir(SPAPP."*",GLOB_ONLYDIR);
	foreach ($apps as $app){
		$hooks_file=SPAPP.$app."/hooks.php";
		if(is_file($hooks_file)){
			$hooks=include $hooks_file;
			$app_hooks=is_array($hooks)?array_merge($app_hooks,$hooks):$app_hooks;
		}
	}
	
	$tpl_hooks=array();
	
	$tpls=sp_scan_dir("tpl/*",GLOB_ONLYDIR);
	
	foreach ($tpls as $tpl){
		$hooks_file= sp_add_template_file_suffix("tpl/$tpl/hooks");
		if(file_exists_case($hooks_file)){
			$hooks=file_get_contents($hooks_file);
			$hooks=preg_replace("/[^0-9A-Za-z_-]/u", ",", $hooks);
			$hooks=explode(",", $hooks);
			$hooks=array_filter($hooks);
			$tpl_hooks=is_array($hooks)?array_merge($tpl_hooks,$hooks):$tpl_hooks;
		}
	}
	
	$return_hooks=array_merge($system_hooks,$app_hooks,$tpl_hooks);
	
	$return_hooks=array_unique($return_hooks);
	
	F('all_hooks',$return_hooks);
	return $return_hooks;
	
}

/**
 * 检查权限
 * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
 * @param uid  int           认证用户的id
 * @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
 * @return boolean           通过验证返回true;失败返回false
 */
function sp_auth_check($uid,$name=null,$relation='or'){
	if(empty($uid)){
		return false;
	}
	
	$iauth_obj=new \Common\Lib\iAuth();
	if(empty($name)){
		$name=strtolower(MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME);
	}
	return $iauth_obj->check($uid, $name, $relation);
}

/**
 * 兼容之前版本的ajax的转化方法，如果你之前用参数只有两个可以不用这个转化，如有两个以上的参数请升级一下
 * @param array $data
 * @param string $info
 * @param int $status
 */
function sp_ajax_return($data,$info,$status){
	$return = array();
	$return['data'] = $data;
	$return['info'] = $info;
	$return['status'] = $status;
	$data = $return;
	
	return $data;
}

/**
 * 判断是否为SAE
 */
function sp_is_sae(){
	if(defined('APP_MODE') && APP_MODE=='sae'){
		return true;
	}else{
		return false;
	}
}


function sp_alpha_id($in, $to_num = false, $pad_up = 4, $passKey = null){
	$index = "aBcDeFgHiJkLmNoPqRsTuVwXyZAbCdEfGhIjKlMnOpQrStUvWxYz0123456789";
	if ($passKey !== null) {
		// Although this function's purpose is to just make the
		// ID short - and not so much secure,
		// with this patch by Simon Franz (http://blog.snaky.org/)
		// you can optionally supply a password to make it harder
		// to calculate the corresponding numeric ID

		for ($n = 0; $n<strlen($index); $n++) $i[] = substr( $index,$n ,1);

		$passhash = hash('sha256',$passKey);
		$passhash = (strlen($passhash) < strlen($index)) ? hash('sha512',$passKey) : $passhash;

		for ($n=0; $n < strlen($index); $n++) $p[] =  substr($passhash, $n ,1);

		array_multisort($p,  SORT_DESC, $i);
		$index = implode($i);
	}

	$base  = strlen($index);

	if ($to_num) {
		// Digital number  <<--  alphabet letter code
		$in  = strrev($in);
		$out = 0;
		$len = strlen($in) - 1;
		for ($t = 0; $t <= $len; $t++) {
			$bcpow = pow($base, $len - $t);
			$out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
		}

		if (is_numeric($pad_up)) {
			$pad_up--;
			if ($pad_up > 0) $out -= pow($base, $pad_up);
		}
		$out = sprintf('%F', $out);
		$out = substr($out, 0, strpos($out, '.'));
	}else{
		// Digital number  -->>  alphabet letter code
		if (is_numeric($pad_up)) {
			$pad_up--;
			if ($pad_up > 0) $in += pow($base, $pad_up);
		}

		$out = "";
		for ($t = floor(log($in, $base)); $t >= 0; $t--) {
			$bcp = pow($base, $t);
			$a   = floor($in / $bcp) % $base;
			$out = $out . substr($index, $a, 1);
			$in  = $in - ($a * $bcp);
		}
		$out = strrev($out); // reverse
	}

	return $out;
}

/**
 * 验证码检查，验证完后销毁验证码增加安全性 ,<br>返回true验证码正确，false验证码错误
 * @return boolean <br>true：验证码正确，false：验证码错误
 */
function sp_check_verify_code(){
	$verify = new \Think\Verify();
	return $verify->check($_REQUEST['verify'], "");
}

/**
 * 执行SQL文件  sae 环境下file_get_contents() 函数好像有间歇性bug。
 * @param string $sql_path sql文件路径
 * @author 5iymt <1145769693@qq.com>
 */
function sp_execute_sql_file($sql_path) {
    	
	$context = stream_context_create ( array (
			'http' => array (
					'timeout' => 30 
			) 
	) ) ;// 超时时间，单位为秒
	
	// 读取SQL文件
	$sql = file_get_contents ( $sql_path, 0, $context );
	$sql = str_replace ( "\r", "\n", $sql );
	$sql = explode ( ";\n", $sql );
	
	// 替换表前缀
	$orginal = 'sp_';
	$prefix = C ( 'DB_PREFIX' );
	$sql = str_replace ( "{$orginal}", "{$prefix}", $sql );
	
	// 开始安装
	foreach ( $sql as $value ) {
		$value = trim ( $value );
		if (empty ( $value )){
			continue;
		}
		$res = M ()->execute ( $value );
	}
}

/**
 * 给没有后缀的模板文件，添加后缀名
 * @param string $filename_nosuffix
 */
function sp_add_template_file_suffix($filename_nosuffix){
    
    
    
    if(file_exists_case($filename_nosuffix.C('TMPL_TEMPLATE_SUFFIX'))){
        $filename_nosuffix = $filename_nosuffix.C('TMPL_TEMPLATE_SUFFIX');
    }else if(file_exists_case($filename_nosuffix.".php")){
        $filename_nosuffix = $filename_nosuffix.".php";
    }else{
        $filename_nosuffix = $filename_nosuffix.C('TMPL_TEMPLATE_SUFFIX');
    }
    
    return $filename_nosuffix;
}

/**
 * 获取当前主题名
 * @param string $default_theme 指定的默认模板名
 * @return string
 */
function sp_get_current_theme($default_theme=''){
    $theme      =    C('SP_DEFAULT_THEME');
    if(C('TMPL_DETECT_THEME')){// 自动侦测模板主题
        $t = C('VAR_TEMPLATE');
        if (isset($_GET[$t])){
            $theme = $_GET[$t];
        }elseif(cookie('think_template')){
            $theme = cookie('think_template');
        }
    }
    $theme=empty($default_theme)?$theme:$default_theme;
    
    return $theme;
}

/**
 * 判断模板文件是否存在，区分大小写
 * @param string $file 模板文件路径，相对于当前模板根目录，不带模板后缀名
 */
function sp_template_file_exists($file){
    $theme= sp_get_current_theme();
    $filepath=C("SP_TMPL_PATH").$theme."/".$file;
    $tplpath = sp_add_template_file_suffix($filepath);
    
    if(file_exists_case($tplpath)){
        return true;
    }else{
        return false;
    }
    
}

/**
 * 盟友统计
 * @param $openid 需要统计的微信ID
 * @param array $where 对下级用户筛选条件
 * @param array $field 需要的字段,根据$type
 * @param int $first 是否需要统计第一级盟友
 * @param string $type 默认count统计数量,info则配合$field来获取用户信息组
 */
function utcount($openid,$where=array(),$field=array("open_id","jointag"),$first=0,$type='count'){
	if(empty($openid))
		return false;
	$member=M("member","sk_");
	static $count=array('total'=>array("zcount"=>0,"zmcount"=>0),'now'=>array("zcount"=>0,"zmcount"=>0));
	static $info=array();
	$where['pid']=$openid;
	$partner=$member->where($where)->field($field)->select();
	foreach($partner as $k=>$v){
		if($type=='count') {
			$count['total']['zcount']++;
			if ($first == 1) {
				$count['now']['zcount']++;
				if ($v['jointag'] == 1) {
					$count['now']['zmcount']++;
				}
			}
			if ($v['jointag'] == 1) {
				$count['total']['zmcount']++;
			}
		}elseif($type=='info'){
			$info[]=(count($field)==1)?current($v):$v;
		}
		if($member->where(array("pid"=>$v['open_id']))->count()>0) {
			utcount($v['open_id'], $where, $field, 0, $type);
		}
	}
	return $$type;
}

/**
 * 盟友统计,按天计算
 * @param $openid 微信ID
 * @param array $where 对下级用户筛选条件
 * @param int $type 统计第一级盟友
 */ 
function utcount_days($openid,$where=array(),$type=0){
	if(empty($openid))
		return false;
	$member=M("member","sk_");
	static $count=array('total'=>array("zcount"=>0,"zmcount"=>0),'now'=>array("ncount"=>0,"nmcount"=>0));
	$day =round((strtotime($where['login_time'][1][1]) - strtotime($where['login_time'][0][1]))/86400);
	//var_dump($day);exit;
	$stime=strtotime($where['login_time'][0][1]);
	for($i=0;$i<=$day;$i++){
		$date=date("Y-m-d",$stime);
		if(!array_key_exists($date,$count)) {
			$stime += 86400;
			$count[$date] = array("zcount"=>0,"zmcount"=>0,"ncount"=>0,"nmcount"=>0);
		}
	}
	$where['pid']=$openid;
	$partner=$member->where($where)->field("open_id,jointag,login_time")->select();
	foreach($partner as $v){
		$date=date("Y-m-d",strtotime($v['login_time']));
		$count[$date]['zcount']++;
		$count['total']['zcount']++;
		if($v['jointag']==1){
			$count[$date]['zmcount']++;
			$count['total']['zmcount']++;
		}
		if($type==1){
			$count[$date]['ncount']++;
			$count['now']['ncount']++;
			if($v['jointag']==1){
				$count[$date]['nmcount']++;
				$count['now']['nmcount']++;
			}
		}
		if($member->where("pid='".$v['open_id']."'")->count()>0)
			utcount_days($v['open_id'],$where);
	}
	return $count;
}

/**
 * 订单统计,按天计算
 * @param $openid 微信ID
 * @param array $where 对下级盟友的订单条件筛选
 */
function otcount_days($openid,$where=array()){
	if(empty($openid))
		return false;
	$member=M("member","sk_");
	$orders=M("orders","sk_");
	static $count=array('total'=>array("xd"=>0,"fd"=>0,"xdmoney"=>0,"fdmoney"=>0));
	$day = round((strtotime($where['add_time'][1][1]) - strtotime($where['add_time'][0][1])) / 86400);
	//var_dump($where);exit;
	$stime=strtotime($where['add_time'][0][1]);
	for($i=0;$i<=$day;$i++){
		$date=date("Y-m-d",$stime);
		if(!array_key_exists($date,$count)) {
			$stime += 86400;
			$count[$date] = array("xd" => 0, "fd" => 0, "xdmoney" => 0, "fdmoney" => 0);
		}
	}
	$partner=$member->where("pid='$openid'")->getField("open_id",true);
	if(empty($partner))
		return false;
	$where['buyer_id']=array("in",$partner);
	$where['status']=array("in","0,1");
	$result=$orders->field("status,add_time,order_amount")->where($where)->select();
	
		foreach($result as $v){
			if (empty($result['add_time'])) {
				if($member->where("pid='".$v['open_id']."'")->count()>0)
					otcount_days($v,$where);
			}else{
				$date=date("Y-m-d",$v['add_time']);
				if(in_array($v['status'],array(1,2,3,4))&&$v['pay_time']<=$where['add_time'][0][1]){
					$count[$date]['fd']++;
					$count['total']['fd']++;
					$count[$date]['fdmoney']+=$v['order_amount'];
					$count['total']['fdmoney']+=$v['order_amount'];
				}
				$count[$date]['xd']++;
				$count['total']['xd']++;
				$count[$date]['xdmoney']+=$v['order_amount'];
				$count['total']['xdmoney']+=$v['order_amount'];
		}
		foreach($partner as $v){
			if($member->where("pid='".$v."'")->count()>0)
				otcount_days($v,$where);
		}
	}
	
	return $count;
}

/**
 * 订单总统计
 * @param $openid 微信ID
 * @param array $where 对下级盟友的订单条件筛选
 * @param array $field 需要的字段,根据$type
 * @param string $type 默认count统计数量,info则配合$field来获取用户信息组
 */
function otcount($openid,$where=array(),$field=array("status","add_time","order_amount"),$type='count'){
	if(empty($openid))
		return false;
	$member=M("member","sk_");
	$orders=M("orders","sk_");
	static $count=array('total'=>array("xd"=>0,"fd"=>0,"xdmoney"=>0,"fdmoney"=>0));
	static $info=array();
	$partner=$member->where("pid='$openid'")->getField("open_id",true);
	if(empty($partner))
		return false;
	$where['buyer_id']=array("in",$partner);
	$where['status']=array("in","0,1");
	$result=$orders->field($field)->where($where)->select();
	foreach($result as $v){
		if($type=='count') {
			if (in_array($v['status'], array(1, 2, 3, 4))) {
				$count['total']['fd']++;
				$count['total']['fdmoney'] += $v['order_amount'];
			}
			$count['total']['xd']++;
			$count['total']['xdmoney'] += $v['order_amount'];
		}elseif($type=='info'){
			$v['wei_nickname']=$member->getFieldByOpen_id($v['buyer_id'],'wei_nickname');
			$info[]=$v;
		}
	}
	foreach($partner as $v){
		if($member->where("pid='".$v."'")->count()>0)
			otcount_days($v,$where,$field,$type);
	}
	return $$type;
}




/**
 * 收益统计,按天计算
 * @param $openid 微信ID
 * @param array $where 对下级盟友的收益条件筛选
 */
function income_days($openid,$where=array()){
	if(empty($openid))
		return false;
	$member=M("member","sk_");
	$pop=M("pop","wei_");
	$day = round(($where['shijianc'][1][1] - $where['shijianc'][0][1]) / 86400);	
	$stime=$where['shijianc'][0][1];
	for($i=0;$i<=$day;$i++){
		$date=date("Y-m-d",$stime);
		if(!array_key_exists($date,$count)) {
			$stime += 86400;
			$count[$date] = array("id" => 0, "order_amount" => 0, "pop" => 0);
		}
	}
	$partner=$member->where("pid='$openid'")->getField("open_id",true);
	if(empty($partner))
		return false;
	$where['openid']=array("in",$partner);
	$result=$pop->join("left join sk_orders o on wei_pop.order_sn=o.order_sn")->field("sum(wei_pop.pop) as pop,sum(o.order_amount) as order_amount,count(wei_pop.id) as id,wei_pop.shijianc")->where($where)->select();
	
	foreach($result as $v){
			if (empty($v['shijianc'])) {
				foreach($partner as $v){
					if($member->where("pid='".$v['open_id']."'")->count()>0)
						otcount_days($v,$where);
					    return $count;
				}
			}else{
			$date=date("Y-m-d",$v['shijianc']);	
			$count[$date]['id']+=$v['id'];
			$count[$date]['order_amount']+=$v['order_amount'];
			$count[$date]['pop']+=$v['pop'];
		}
	}	
	foreach($partner as $v){
		if($member->where("pid='".$v."'")->count()>0)
			otcount_days($v,$where);
	}
	return $count;
}





/**
 * 根据盟友访问记录来商品统计
 * @param $openid 微信ID
 * @param array $where1 对盟友的条件筛选
 * @param array $where2 对订单的条件筛选
 * @param int $limit 需要多少条数据
 * @param string $type 暂时只有goods统计功能
 */
function atcount($openid,$where1=array(),$where2=array(),$limit=10,$type="goods"){
	if(empty($openid))
		return false;
	$infos=utcount($openid,$where1,array("open_id"),0,"info");
	if(empty($infos)){
		return false;
	}
	$action_log=M("action_log","sk_");
	$count=array();
	$count2=array();
	foreach($infos as $v){
		$where2['openid']=$v;
		$result=$action_log->join("left join sk_goods g on g.id=aid")->where($where2)->field("g.id,good_name")->select();
		foreach($result as $g){
			if(!isset($count[$g['id']])) {
				$count[$g['id']]['id'] = $g['id'];
				$count[$g['id']]['good_name'] = $g['good_name'];
			}
			$count[$g['id']]['count']++;
			$count2[$g['id']]++;
		}
	}
	array_multisort($count2,SORT_DESC,$count);
	return array_slice($count,0,$limit);
}

/*
某个商品详情页浏览人数及次数
编写人：noob
*/
function goods_even($open_id,$where1=array(),$where2=array(),$limit=10,$type="goods"){
    if (empty($open_id)) {
    	return false;
    }
    $infos=utcount($open_id,$where1,array("open_id"),0,"info");
	if(empty($infos)){
		return false;
	}
	$action_log=M("action_log","sk_");  
	$count=array();
	$count2=array();
	foreach($infos as $v){
		//$where2['openid']='oc4cpuIh8OYxAeBrSNCbenVeH-j8';
		$where2['openid']=$v;
		$result=$action_log->join("left join sk_goods g on g.id=aid")->where($where2)->field("g.id,good_name,openid")->select();
		foreach($result as $g){
			if(!isset($count[$g['id']])) {
				$count[$g['id']]['id'] = $g['id'];
				$count[$g['id']]['good_name'] = $g['good_name'];
			}
            if ($count[$g['id']]['openid']==$g['openid']) {
                $count[$g['id']]['r_count']++;	
            }			
			$count[$g['id']]['openid'] = $g['openid'];			
			$count[$g['id']]['l_count']++;
			$count2[$g['id']]++;
		}
	}
	array_multisort($count2,SORT_DESC,$count);
	return array_slice($count,0,$limit);

}


/**
 * UTF8计算字符
 * @param null $string 字体
 */
function utf8_strlen($string = null) {
	preg_match_all("/./us", $string, $match);
	return count($match[0]);
}