<?php

class userAction extends baseAction
{
    public function index()
    {
        $mod = D("member");
        $pagesize = 20;
        import("ORG.Util.Page");
        
		$time_start = isset($_GET['addtime_start']) && trim($_GET['addtime_start']) ? trim($_GET['addtime_start']) : '';
		$time_end = isset($_GET['addtime_end']) && trim($_GET['addtime_end']) ? trim($_GET['addtime_end']) : '';
		$addtime_start = isset($_GET['dltime_start']) && trim($_GET['dltime_start']) ? trim($_GET['dltime_start']) : '';
		$addtime_end = isset($_GET['dltime_end']) && trim($_GET['dltime_end']) ? trim($_GET['dltime_end']) : '';
		
		
		

        if ($time_start) {
		    //$time_start_int = strtotime($time_start);
		    $where['_string'] = " login_time >= '$time_start'";
		    $this->assign('addtime_start', $time_start);
		}
		if ($time_end) {
		    //$time_start_int = strtotime($time_end);
            $where['_string'] = " login_time <= '$time_end'";
		    $this->assign('addtime_end', $time_end);
		}
		if($time_end&&$time_start){
            $where['_string'] = " login_time <= '$time_end' and login_time >= '$time_start'";
		}
		if ($addtime_start) {
		    $time_start_int = strtotime($addtime_start);
		    $where['_string'] = " last_login >= '$time_start_int'";
		    $this->assign('dltime_start', $addtime_start);
		}
		if ($addtime_end) {
		    $time_start_intq = strtotime($addtime_end);
            $where['_string'] = " last_login <= '$time_start_intq'";
		    $this->assign('dltime_end', $addtime_end);
		}
		if($addtime_start&&$addtime_end){
            $where['_string'] = " last_login <= '$time_start_intq' and last_login >= '$time_start_int'";
		}
		
		
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
        if (!empty($keyword)) {
            $this->assign('keyword', $keyword);
            $where['_string'] = " real_name like '%$keyword%'";
        }
        $wei_name = isset($_REQUEST['wei_name']) ? $_REQUEST['wei_name'] : '';
        if (!empty($wei_name)) {
            $this->assign('wei_name', $wei_name);
            $where['_string'] = " wei_nickname like '%$wei_name%'";
        }
        $flag = isset($_GET['flag']) ? intval($_GET['flag']) : '-1';
        $this->assign('flag', $flag);
        if($flag == 3)
            $where['_string'] = 'jointag=1 or jointag=2';
        else if ($flag >= 0)
            $where['jointag'] =  $flag;
        $count = $mod->where($where)->count();
        $p = new Page($count, $pagesize);
        $list = $mod->where($where)->field('*,(select sum(pop) p from wei_pop where wei_pop.openid=sk_member.open_id) sumpop')->order("last_login desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    function edit()
    {
        if (isset($_POST['dosubmit'])) {
            $info_mod = D('member');
            $info_data = $info_mod->create();
            $usrpwd=trim($_POST['usrpwd']);
            if (!empty($usrpwd)) {
                if (strlen($usrpwd) < 6 || strlen($usrpwd) > 20) {
                    $this->error('密码长度错误，应在6到20位之间');
                }
                $info_mod->usrpwd=md5($usrpwd);
            }
            else
            {
                $userInfo=$info_mod->where("usrid=" . $info_data['usrid'])->find();
                $info_mod->usrpwd=$userInfo['usrpwd'];
            }
            if (empty($info_mod->birthday))
                $info_mod->birthday = 0;
            else
                $info_mod->birthday=strtotime($info_mod->birthday);
            $email=$info_mod->email;
            if (!empty($email)&&!is_email($email))
            {
                $this->error('电子邮箱错误');
            }
            $result_info = $info_mod->save($info_data);

            if (false !== $result_info) {
                 $this->success('修改会员信息成功');
            } else {
                $this->success('修改会员信息成功');
            }
        } else {
            $info_mod = M('member');
            if (isset($_GET['id'])) {
                $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的链接');
            }
            $user = $info_mod->where('usrid=' . $id)->find();
            $this->assign('info', $user);
            $this->display();
        }
    }


    public function delete()
    {
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            $this->error('请选择要删除的数据！');
        }
        $user_mod = D('member');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            foreach ($_POST['id'] as $val) {
                $user_mod->delete($val);
            }
        } else {
            $id = intval($_POST['id']);
            if (!empty($id)) {
                $user_mod->delete($id);
            }
        }
        $this->success(L('operation_success'));
    }
    public function  status()
    {
        $id = intval($_REQUEST['id']);
        $type = trim($_REQUEST['type']);
        $items_mod = D('member');
        $res = $items_mod->where('usrid=' . $id)->setField($type, array('exp', "(" . $type . "+1)%2"));
        $values = $items_mod->where('usrid=' . $id)->getField($type);
        $this->ajaxReturn($values[$type]);
    }

    public  function  checkusr(){
        $usrname= trim($_REQUEST['usrname']);
        if(empty($usrname))
            $this->ajaxReturn('','参数为空',-1);
        $items_mod = D('user_info');
        $usr= $items_mod->where(array('usrname'=>$usrname))->find();
        if(!empty($usr))
            $this->ajaxReturn('','已被占用',0);
        else
            $this->ajaxReturn('','未被占用',1);
    }
	function geren_dianpu(){
	    if (isset($_GET['openid'])){
	        $tj['openid'] = $_GET['openid'];
	    }
	    $tj['status'] = 4;
	    

	    if (isset($_GET['status'])&&$_GET['status']!=-1){
	        $tj['shenhe'] = $_GET['status'];
	    }
	    if (isset($_GET['tex'])&&$_GET['tex']!=""){
	        $tj['_string'] = "dianpname like '%".$_GET['tex']."%'";
	        $tx = $_GET['tex'];
	    }
	    //dump($_GET['openid']);die;
	    $dianp = M('erweima');
	    $count = $dianp -> where($tj) -> join('sk_orders on sk_erweima.dingdan=sk_orders.order_id') ->count();// 查询满足要求的总记录数
	    if($_GET['p'])
	        $dianp = $dianp -> where($tj) -> join('sk_orders on sk_erweima.dingdan=sk_orders.order_id') -> page($_GET['p'].',20') -> select();
	    else 
	        $dianp = $dianp -> where($tj) -> join('sk_orders on sk_erweima.dingdan=sk_orders.order_id') -> limit('0,20') -> select();
	    
	    import("ORG.Util.Page");// 导入分页类
	    $Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
	    $show = $Page->show();// 分页显示输出
	    $this->assign('page',$show);// 赋值分页输出

	    if($tj['shenhe']=="")
	        $this->assign('status', -1);
	    else
	        $this->assign('status', $tj['shenhe']);
	    $this->assign('keyword', $tx);
	    $this->assign('openid', $tj['openid']);
		$this->assign('items_cate_list',$dianp);
		$this->display(); 
	}
	function geren_dpshenhe(){
	    if (isset($_GET['id'])){
	        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的详情链接');
	    }
	    $whe['id'] = $id;
	    $dianpu = M('erweima');
	    $dianpu = $dianpu -> where($whe) -> join('sk_member on sk_member.open_id = sk_erweima.openid')-> select();
	    $this->assign('items_cate_list', $dianpu);

	    $this -> display();
	}
	function dianpuchakan(){
	    if (isset($_GET['id'])){
	        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的详情链接');
	    }
	    $whe['id'] = $id;
	    $dianpu = M('erweima');
	    $dianpu = $dianpu -> where($whe) -> join('sk_member on sk_member.open_id = sk_erweima.openid')-> select();
	    $this->assign('dianpu', $dianpu);

	    $this -> display();
	}
	function dianpu_shenheget(){    
		if ($_FILES['img']['name'] != ''){
			$upload_list = $this->_upload($_FILES['img']);
			$name['dianp_img'] = $upload_list;
		}
		$dianpu = M("erweima");
		$where['id'] = $_GET['id'];
		$name['dianpname'] = $_POST['dianpname'];
		$name['dianp_lxfs'] = $_POST['dianp_lxfs'];
		$name['zuobiao'] = $_POST['zuobiao'];
		$name['xxdizhi'] = $_POST['xxdizhi'];
		$name['shenhe'] = $_POST['shenhe'];

		if($dianpu->where($where)->save($name))
		    $this->error('修改成功');
		else 
		    $this->error('修改失败');
	}
    function xiaoxi(){
        $info_mod = M('member');
        if (isset($_GET['id'])) {
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的链接');
        }
        $user = $info_mod->where('usrid=' . $id)->find();
        $this->assign('info', $user);
        $this->display();
    }
    function xiaoxiget(){

        $id = $_POST['id'];
        $neirong = $_POST['neirong'];
        $biaoti = $_POST['biaoti'];
        
            $xiaoxi = M("xiaoxi"); // 实例化User对象
            $data['openid'] = $id;
            $data['biaoti'] = $biaoti;
            $data['neirong'] = $neirong;
            /* $data['openid'] = 'oc4cpuKadXviOYYPCRwrrQ2Xvfds';
            $data['biaoti'] = '4444';
            $data['neirong'] = '55555555555555555'; */
            $data['sanchu'] = 0;
            
            $data['shijian'] = time();
            $dianpu = $xiaoxi->add($data);
            $this->ajaxReturn($dianpu);
    }
}