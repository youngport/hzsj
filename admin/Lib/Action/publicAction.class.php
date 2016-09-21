<?php
class publicAction extends baseAction
{
	// 菜单页面
	public function menu(){
		//显示菜单项
		$id	=	intval($_REQUEST['tag'])==0?6:intval($_REQUEST['tag']);
		$menu  = array();
		$role_id = D('admin')->where('id='.$_SESSION['admin_info']['id'])->getField('role_id');
		$node_ids_res = D("access")->where("role_id=".$role_id)->field("node_id")->select();
		
		$node_ids = array();
		foreach ($node_ids_res as $row) {
			array_push($node_ids,$row['node_id']);
		}
		//读取数据库模块列表生成菜单项
		$node    =   M("node");
		$where = "auth_type<>2 AND status=1 AND is_show=0 AND group_id=".$id;
		$list	=$node->where($where)->field('id,action,action_name,module,module_name,data')->order('sort DESC')->select();
		foreach($list as $key=>$action) {
			$data_arg = array();
			if ($action['data']){
				$data_arr = explode('&', $action['data']);
				foreach ($data_arr as $data_one) {
					$data_one_arr = explode('=', $data_one);
					$data_arg[$data_one_arr[0]] = $data_one_arr[1];
				}
			}
			$action['url'] = U($action['module'].'/'.$action['action'], $data_arg);
			if ($action['action']) {
				$menu[$action['module']]['navs'][] = $action;
			}
			$menu[$action['module']]['name']	= $action['module_name'];
			$menu[$action['module']]['id']	= $action['id'];
		}
		$this->assign('menu',$menu);
		$this->display('left');
	}

	/**	 
	 * 后台主页
	 */
	public function main()
	{
		$security_info=array();
		if(count($security_info)<=0){
			$this->assign('no_security_info',0);
		}
		else{
			$this->assign('no_security_info',1);
		}	
		$this->assign('security_info',$security_info);
        $disk_space = @disk_free_space(".")/pow(1024,2);
        $server_info = array(
            '操作系统'=>PHP_OS,
            '服务器解译引擎'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP版本'=>phpversion(),
            'MYSQL版本'=>$this->mysql_version(),
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '允许URL打开文件'=>(ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>'),
            '安全模式'=>(ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>'),
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '时区'=>function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone",
            'GD库'=>$this->showResult('imageline'),
            'POST限制'=>$this->showResult('post_max_size'),
            'ZEND支持'=>$this->showResult('zend_version'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round($disk_space < 1024 ? $disk_space:$disk_space/1024 ,2).($disk_space<1024?'M':'G'),
        );
        $this->assign('set',$this->setting);
		$this->assign('server_info',$server_info);	
		$this->display();
	}
    function showResult($str=''){
        if(function_exists($str)){
            return '<font color="#1194be">支持</font>';
        }else{
            return '<font color="#f17564">不支持</font>';
        }
    }
    private function mysql_version()
    {
        $Model = M();
        $version = $Model->query("select version() as ver");
        return $version[0]['ver'];
    }
	public function login()
	{
		//unset($_SESSION);
		$admin_mod=M('admin');
		if ($_POST) {
			$username = $_POST['username'] && trim($_POST['username']) ? trim($_POST['username']) : '';
			$password = $_POST['password'] && trim($_POST['password']) ? trim($_POST['password']) : '';
			if (!$username || !$password) {
				redirect(u('public/login'));
			}
			if($this->setting['check_code']==1){
				if ($_SESSION['verify'] != md5($_POST['verify']))
				{
					$this->error(L('verify_error'));
				}
			}
			//生成认证条件
			$map  = array();
			// 支持使用绑定帐号登录
			$map['user_name']	= $username;
			$map["status"]	=	array('gt',0);			
			$admin_info=$admin_mod->where("user_name='$username'")->find();

			//使用用户名、密码和状态的方式进行认证
			if(false === $admin_info) {
				$this->error('帐号不存在或已禁用！');
			}else {
				if($admin_info['password'] != md5($password)) {
					$this->error('密码错误！');
				}

				$_SESSION['admin_info'] =$admin_info;
				if($admin_info['user_name']=='admin') {
					$_SESSION['administrator'] = true;
				}
				$this->success('登录成功！',u('index/index'));
				exit;
			}
		}
		$this->assign('set',$this->setting);
		$this->display();
	}

	public function logout()
	{
		if(isset($_SESSION['admin_info'])) {
			unset($_SESSION['admin_info']);			
			$this->success('退出登录成功！',u('public/login'));
		} else {
			$this->error('已经退出登录！');
		}
	}
    public  function  loadcity(){
        $city=$_POST['pid'];
        if(!empty($city))
        {
            $obj=D('city');
            $citys=$obj->where(array('city_parent_id'=>$city))->field('city_id as id,cityname as name')->select();
            if(!empty($citys))
            {
                $this->ajaxReturn($citys,'读取成功',1);
            }else
                $this->ajaxReturn(array(),'无子级数据',0);
        }
        else
            $this->ajaxReturn(array(),'参数错误',-1);
    }
    public function genxin(){
    	//dump('aaaa');die;
        $this->assign('list', "首页更新完成！");
    	$this->buildHtml('index', 'wei/',"wei/index.php");
    	$this -> display();
    }
    
    public function fankui(){
	    if (isset($_GET['id'])){
	    	$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的详情链接');
		    $whe['id'] = $id;
		    $fankui = M('fankui');
		    $fankui -> where($whe) -> delete();
	    }
    	$fankui = M('fankui');
		$count = $fankui->count();// 查询满足要求的总记录数
		if($_GET['p'])
    		$fankui = $fankui -> order('date desc') -> join('sk_member on sk_member.open_id = sk_fankui.openid')->page($_GET['p'].',20') -> select();
    	else
    		$fankui = $fankui -> order('date desc') -> join('sk_member on sk_member.open_id = sk_fankui.openid')->limit('0,20') -> select();

		import("ORG.Util.Page");// 导入分页类
		$Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出


    	
    	
    	
    	
    	
    	$this->assign('fankui', $fankui);
    	$this -> display();
    }
    public function fankui_xq(){//反馈详情
	    if (isset($_GET['id'])){
	    	$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的详情链接');
	    }
	    $whe['id'] = $id;
	    $fankui = M('fankui');
	    $fankui = $fankui -> where($whe) -> join('sk_member on sk_member.open_id = sk_fankui.openid')-> select();
	    $this->assign('fankui', $fankui);
	    $this -> display();
    }
    public function shenhe_fankui(){//反馈审核
	    if (isset($_GET['id'])){
	    	$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的详情链接');
	    }
	    $whe['id'] = $id;
	    $fankui = M('fankui');
	    $fankui = $fankui -> where($whe) -> join('sk_member on sk_member.open_id = sk_fankui.openid')-> select();
	    $this->assign('fankui', $fankui);
	    $this -> display();
    }
    public function fanhuijifen(){//反回积分 jifen_fanhuan
	    if (isset($_POST['opid'])){
	    	$opid = $_POST['opid'];
	    }
	    if (isset($_POST['id'])){
	    	$id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : $this->error('请选择审核的反馈');
	    }
	    if (isset($_POST['jifen_fanhuan'])){
	    	$jifen_fanhuan = isset($_POST['jifen_fanhuan']) && intval($_POST['jifen_fanhuan']) ? intval($_POST['jifen_fanhuan']) : $this->error('请选择审核结果');
	    }
	    $whe['id'] = $id;
	    $whe_ren['open_id'] = $opid;
	    $sva['jifen_fanhuan'] = $jifen_fanhuan;
	    
	    $fankui = M('fankui');
	    $fankui_d = $fankui -> where($whe) -> select();
	    if($fankui_d[0]['jifen_fanhuan']==0){
	        
    	    $fankui = $fankui -> where($whe) -> save($sva);
    	    if($jifen_fanhuan==1){
        	    $tianj['jifen'] = 20;
        	    $tianj['openid'] = $opid;
        	    $tianj['laiyuan'] = 3;
        	    $tianj['shijian'] = time();
        	    $jifen = M('jifen');
        	    $jifen -> add($tianj);
        	    $member = M('member');
        	    $i = $member = $member -> where($whe_ren) -> setInc('jifen',20); //加有效积分 5分
        	    $this->ajaxReturn(5,5,$i); 
    	    }else if($jifen_fanhuan==2){
	            $this->ajaxReturn(5,5,2);
    	    }
	    }else{
	        $this->ajaxReturn(5,5,0);
	    }
    }
    public function weixin_huifu(){
        $token = M('weixin_token');
        $token_txt = $token -> select();
        $token_url = $token_txt[0]['token'];
        if((time() - $token_txt[0]['time'])>7100){//数据库里 微信token 已过期  需要跟新
            $ch = curl_init();
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx8b17740e4ea78bf5&secret=bbd06a32bdefc1a00536760eddd1721d";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $return = curl_exec ( $ch );
            curl_close ( $ch );
            $output_array = json_decode($return,true);
            

            $data_up['token'] = $output_array['access_token'];
            $token_url = $output_array['access_token'];
            $data_up['time'] = time();
            $data_up_whe['id'] = $token_txt[0]['id'];
            $token -> where($data_up_whe) -> save($data_up);
        }
        //用公共号发送信息
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token_url;
        $data = "{
                    \"touser\": \"".$_POST["openid"]."\",
                    \"msgtype\": \"text\",
                    \"text\": {
                        \"content\": \"".htmlspecialchars($_POST["huifu_txt"])."\"
                    }
                }";
        //var_dump(http_post_data($url, $data));
        
        $ch = curl_init ();
        
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        $output_array = json_decode($return,true);
        echo $output_array['errcode'];
        
    }
	function banner(){
	    //dump($_GET['openid']);die;
	    $dianp = M('banner');
	    $count = $dianp -> count();// 查询满足要求的总记录数
	    if($_GET['p'])
	        $dianp = $dianp -> order('paixu asc') -> page($_GET['p'].',20') -> select();
	    else
	        $dianp = $dianp -> order('paixu asc') -> limit('0,20') -> select();
	     
	    import("ORG.Util.Page");// 导入分页类
	    $Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
	    $show = $Page->show();// 分页显示输出
	    $this->assign('page',$show);// 赋值分页输出
	

	    $this->assign('items_cate_list',$dianp);
	    $this->display();
	}
	function banner_add(){
	    $this->display();
	}
	function banner_addget(){
	    if ($_FILES['img']['name'] != ''){
	        $savePath = $_FILES['img'];
	        import("ORG.Net.UploadFile");
	        $upload = new UploadFile();
	        //设置上传文件大小
	        $upload->maxSize = 32922000;
	        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
	        $upload->savePath = ROOT_PATH.'/data/banner/'.$savePath.'/';
	        $upload->saveRule = uniqid;
	         
	        if (!$upload->upload()) {
	            //捕获上传异常
	            $this->error($upload->getErrorMsg());
	        } else {
	            //取得成功上传的文件信息
	            $uploadList = $upload->getUploadFileInfo();
	        }
	        $uploadList='./data/banner/'.$savePath.'/'.$uploadList['0']['savename'];
	        $name['imgurl'] = $uploadList;
	    }
	    $mingp = M("banner");	
	    $name['links'] = $_POST['links'];
	    $name['paixu'] = $_POST['paixu'];
	    $bol = $mingp->add($name);
        if($bol)
	       $this->success('添加成功',U('public/banner'));
        else
	       $this->error('添加失败！');
	}
	function banner_xg(){
		$mingp = M("banner");
		$where['id'] = $_GET['id'];		
		$mingp = $mingp->where($where)->select(); 
		$this->assign('items_cate_list',$mingp);
		$this->display();
	}
	function banner_xgget(){
	    if ($_FILES['img']['name'] != ''){
	        $savePath = $_FILES['img'];
	        import("ORG.Net.UploadFile");
	        $upload = new UploadFile();
	        //设置上传文件大小
	        $upload->maxSize = 32922000;
	        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
	        $upload->savePath = ROOT_PATH.'/data/banner/'.$savePath.'/';
	        $upload->saveRule = uniqid;
	         
	        if (!$upload->upload()) {
	            //捕获上传异常
	            $this->error($upload->getErrorMsg());
	        } else {
	            //取得成功上传的文件信息
	            $uploadList = $upload->getUploadFileInfo();
	        }
	        $uploadList='./data/banner/'.$savePath.'/'.$uploadList['0']['savename'];
	        $name['imgurl'] = $uploadList;
	    }
	    $mingp = M("banner");
		$where['id'] = $_GET['id'];		
	    $name['links'] = $_POST['links'];
	    $name['paixu'] = $_POST['paixu'];
	    $bol = $mingp->where($where)->save($name);
        if($bol)
	       $this->success('修改成功',U('public/banner'));
        else
	       $this->error('修改失败！');
	}
	function banner_del(){
	    $mingp = M("banner");
	    $where['id'] = $_GET['id'];
	    $mingp = $mingp->where($where)->delete();
	    
        if($mingp)
	       $this->success('修改成功',U('public/banner'));
        else
	       $this->error('修改失败！');
	}
	
}
?>