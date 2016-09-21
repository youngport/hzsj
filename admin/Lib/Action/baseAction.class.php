<?php
/**
 * 基础Action
 */
class baseAction extends Action {
	public $user_mode='';  //用户模型
	public $user_info='';  //用户模型
	public $admin_mod='';  //管理员模型
	public $items_cate_mod='';   //项目分类
	public $role_mod='';//权限表
    public $sendMsg_mod='';
    protected $seo_mod;
	function mod_init(){
		$this->admin_mod=D('admin');
		$this->items_cate_mod=D('items_cate');
		$this->user_mode=D('user');
		$this->user_info=D('user_info');
        $this->sendMsg_mod=M('UserMsg');
        $this->seo_mod = M('seo');
	}
	function _initialize() {		
		//过滤所有的GET POST请求			
		//判断是否允许ip访问
//		$banip=getBanip();
//		if($banip){
//			foreach ($banip as $key=>$value){
//				banip($value[0], $value[1]);
//			}
//		}

		include ROOT_PATH.'/includes/lib_common.php';	
		$this->mod_init();		
		$this->site_root="http://".$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']==80?'':':'.$_SERVER['SERVER_PORT']).__ROOT__."/";

		$this->assign('site_root',$this->site_root);
		// 用户权限检查
		$this->check_priv();
		//需要登陆
		$admin_info =$_SESSION['admin_info'];
        
		$this->role_mod=D("role");
		//获取用户角色
		$admin_level=$this->role_mod->field('id','name')->where('id='.$_SESSION['admin_info']['role_id'].'')->find();
		
		$this->assign('admin_level',$admin_level);
		$this->assign('my_info', $admin_info);

		// 顶部菜单
		$model	=	M("group");
		$top_menu	=$model->field('id,title')->where('status=1')->order('sort ASC')->select();
		$this->assign('top_menu',$top_menu);

		//获取网站配置信息
		$setting_mod = M('setting');
		$setting = $setting_mod->select();
		foreach ( $setting as $val ) {
			$set[$val['name']] = $val['data'];
		}
		$this->setting = $set;

		
		
		$this->assign('show_header', true);
		$this->assign('const',get_defined_constants());

		$this->assign('iframe',$_REQUEST['iframe']);
		$def=array(
			'request'=>$_REQUEST
		);	
		$this->assign('def',json_encode($def));
        
	}
	//检查权限
	public function check_priv()
	{
		if ((!isset($_SESSION['admin_info']) || !$_SESSION['admin_info']) && !in_array(ACTION_NAME, array('login','verify_code'))) {
			$this->redirect('public/login');
		}
		//如果是超级管理员，则可以执行所有操作
		if($_SESSION['admin_info']['id'] == 1) {
			return true;
		}
		if(in_array(ACTION_NAME,array('status','sort_order','ordid'))){
			return true;
		}
		//排除一些不必要的权限检查
		foreach (C('IGNORE_PRIV_LIST') as $key=>$val){
			if(MODULE_NAME==$val['module_name']){
				if(count($val['action_list'])==0)return true;

				foreach($val['action_list'] as $action_item){
					if(ACTION_NAME==$action_item)return true;
				}
			}
		}
		$node_mod = D('node');
		$node_id = $node_mod->where(array('module'=>MODULE_NAME, 'action'=>ACTION_NAME))->getField('id');

		$access_mod = D('access');
		$rel = $access_mod->where(array('node_id'=>$node_id, 'role_id'=>$_SESSION['admin_info']['role_id']))->count();
		if ($rel==0) {
			$this->error(L('_VALID_ACCESS_'));
		}
	}

	
	//截取中文字符串
	public function mubstr($str,$start,$length)
	{
		import('ORG.Util.String');
		$a = new String();
		$b = $a->msubstr($str,$start,$length);
		return($b);
	}
	//失败页面重写
	protected function error($message, $url_forward='',$ms = 3, $dialog=false, $ajax=false, $returnjs = '')
	{
		$this->jumpUrl = $url_forward;
		$this->waitSecond = $ms;
		$this->assign('dialog',$dialog);
		$this->assign('returnjs',$returnjs);
		parent::error($message, $ajax);
	}
	//成功页面重写
	protected function success($message, $url_forward='',$ms = 3, $dialog=false, $ajax=false, $returnjs = '')
	{
		$this->jumpUrl = $url_forward;
		$this->waitSecond = $ms;
		$this->assign('dialog',$dialog);
		$this->assign('returnjs',$returnjs);
		parent::success($message, $ajax);
	}

	public function simplexml_obj2array($obj)
	{
		if ($obj instanceof SimpleXMLElement) {
			$obj = (array)$obj;
		}

		if (is_array($obj)) {
			$result = $keys = array();
			foreach ( $obj as $key=>$value) {
				isset($keys[$key]) ? ($keys[$key] += 1) : ($keys[$key] = 1);

				if ( $keys[$key] == 1 ) {
					$result[$key] = self::simplexml_obj2array($value);
				} elseif ( $keys[$key] == 2 ) {
					$result[$key] = array($result[$key], self::simplexml_obj2array($value));
				} else if( $keys[$key] > 2 ) {
					$result[$key][] = self::simplexml_obj2array($value);
				}
			}
			return $result;
		} else {
			return $obj;
		}
	}
	public function saddslashes($value)
	{
		if (empty($value)) {
			return $value;
		} else {
			return is_array($value) ? array_map(array('BaseAction','saddslashes'), $value) : addslashes($value);
		}
	}
	/*
	 * 通用删除操作
	 * */
	public function delete(){
		$mod=D(MODULE_NAME);

		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$ids = implode(',', $_POST['id']);
			$result=$mod->delete($ids);
		} else {
			$id = intval($_GET['id']);
			$result=$mod->delete($id);
		}

		if($result){
			$this->success(L('operation_success'));
		}else{
			$this->error(L('operation_failure'));
		}
	}
	public function check_res($result){
		if($result){
			$this->success(L('operation_success'));
		}else{
			$this->error(L('operation_failure'));
		}
	}
	/*
	 * 通用改变状态
	 * */
	public function status(){
		$mod = D(MODULE_NAME);
		$id     = intval($_REQUEST['id']);
		$type   = trim($_REQUEST['type']);
		$sql    = "update ".C('DB_PREFIX').MODULE_NAME." set $type=($type+1)%2 where id='$id'";
		$res    = $mod->execute($sql);
		$values = $mod->where('id='.$id)->find();
		$this->ajaxReturn($values[$type]);
	}
	/*
	 * 通用排序方法单个排序
	 * */
	public function sort(){
		$mod = D(MODULE_NAME);
		$id     = intval($_REQUEST['id']);
		$type   = trim($_REQUEST['type']);
		$num=trim($_REQUEST['num']);
		if(!is_numeric($num)){
			$values = $mod->where('id='.$id)->find();			
			$this->ajaxReturn($values[$type]);
			exit;
		}
		$sql    = "update ".C('DB_PREFIX').MODULE_NAME." set $type=$num where id='$id'";
        
		$res    = $mod->execute($sql);
		$values = $mod->where('id='.$id)->find();
		$this->ajaxReturn($values[$type]);
	}	
	
	/*
	 * 通用检查值是否存在,存在则返回true
	 * */
	public function ajax_check_exist(){
		$mod = D(MODULE_NAME);
		$clientid=$_REQUEST['clientid'];
		if(!isset($clientid))exit;

		$clientid_val=$_REQUEST[$clientid];
		$id=intval($_REQUEST['id']);	
		if($id>0){
			//edit
			$where="$clientid='$clientid_val' and id!=$id";
		}else{
			//add
			$where="$clientid='$clientid_val'";
		}
		$this->ajaxReturn($mod->where($where)->count()>0);
	}
	/*
	 * 通用排序
	 * */
	public function sort_order(){
		$mod = D(MODULE_NAME);
		if (isset($_POST['listorders'])) {
			foreach ($_POST['listorders'] as $id=>$sort_order) {
				$data['sort_order'] = $sort_order;
				$mod->where('id='.$id)->save($data);
			}
			$this->success(L('operation_success'));
		}
		$this->error(L('operation_failure'));
	}
	public function _stripcslashes($arr){
		if(ini_get('magic_quotes_gpc')!='1')return $arr;
		foreach ($arr as $key=>$val){
			$arr[$key]=stripcslashes($val);
		}
		return $arr;
	}
	//返回分页信息
	public function pager($count,$pagesize=20){
		import("ORG.Util.Page");
		$pager=new Page($count,$pagesize);
		$this->assign('page', $pager->show());
		return $pager;
	}
	public function append_user($res){
		foreach($res as $key=>$val){
			$res[$key]['user']=$this->user_mode->where('id='.$val['uid'])->find();
		}
		return $res;
	}	
	//公共上传图片方法
	public function _upload($savePath)
	{
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 32922000;
		$upload->allowExts = explode(',', 'jpg,gif,png,jpeg,xls');
		$upload->savePath = ROOT_PATH.'/data/'.$savePath.'/';
		$upload->saveRule = uniqid;

		if (!$upload->upload()) {
			//捕获上传异常
			$this->error($upload->getErrorMsg());
		} else {
			//取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
		}
		$uploadList='./data/'.$savePath.'/'.$uploadList['0']['savename'];
		
		return $uploadList;
	}

}
?>
