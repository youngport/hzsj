<?php

/**
 * 给商家发优惠券的后台功能
 * 用来设置 商家的优惠券信息
 * *@author weiyh
 * @copyright (c) 2016.7.16, John Doe
 */
class hycouponAction extends baseAction {

    function index() {
        $hycoupon = M('hycoupon');
        import("ORG.Util.Page");
        $condition = array();
        $con = array();
        if (!empty($_POST['openid'])) {
            $condition['openid'] = array('like', '%' . $_POST['openid'] . '%');
            $con['openid'] = $_POST['openid'];
        }
        if (!empty($_POST['start_time'])) {
            $condition['start_time'] = array('egt', strtotime($_POST['start_time']));
            $con['start_time'] = strtotime($_POST['start_time']);
        }

        if (!empty($_POST['end_time'])) {
            $condition['end_time'] = array('elt', strtotime($_POST['end_time']));
            $con['end_time'] = strtotime($_POST['end_time']);
        }
        $count = $hycoupon->where($condition)->count();
        $p = new Page($count, 20);
        $list = $hycoupon->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order(" add_time desc")->select();
        $page = $p->show();
        
        $this->assign('page', $page);
        $this->assign("list", $list);
        $this->assign("condition", $con);
        $this->display();
    }

    function add() {

		if(!empty($_POST)){
			
			$hycouponModel = D('hycoupon');
// 			dump($_POST);exit();
			if($hycouponModel->create()){
				if($hycouponModel->add()){
					
					$this->success('添加成功!',U("hycoupon/index"));
					exit();
				}else{
                    $this->error('处理失败!');
				}
			}else{
					$this->error('添加失败!',U('hycoupon/add'));
			}
			
		}
		
		$this->display();
    	
    	
    }

    function edit() {

    	$hycoupon = D('hycoupon');
    	if(!empty($_POST)){
    		
    	if($hycoupon->create()){
    		
    		if($hycoupon->save() !== false){
    			
    			$this->success('修改成功!',U('hycoupon/index'));
    			exit();
    		}else{
    			$this->error('修改失败!');
    		}
    		
    	}else{
    		
    		$this->error('修改失败!');
    		
    	}
    	
    }
    
        $id = $_GET['id'];
	    $data = $hycoupon->where("id='" . $id . "'")->find();
	    $this->assign("data", $data);
	    $this->display();
    	
  }  
    
    
    function del() {
    	
    	if ($_REQUEST) {
            $hycoupon = M('hycoupon');
    		$id = $_REQUEST['id'];
    		
    		//批量删除
			if(is_array($id)){
				
				$id = implode(',', $id);
				
			}
				
            $data = $hycoupon->delete($id);
            if($data){
                $this->success("删除成功", U("hycoupon/index"));
                exit();
            }else{
                $this->error("删除失败");
            }
        }
        
    }
    
    public function coupon_list(){
		
    	$couponModel = M('coupon');
    	
    	import("ORG.Util.Page");
    	$condition = array();
    	$con = array();
    	if (!empty($_POST['openid'])) {
    		$condition['rec'] = array('like', '%' . $_POST['openid'] . '%');
    		$con['openid'] = $_POST['openid'];
    	}
    	if (!empty($_POST['start_time'])) {
    		$condition['start_time'] = array('egt', strtotime($_POST['start_time']));
    		$con['start_time'] = $_POST['start_time'];
    	}
    	
    	if (!empty($_POST['end_time'])) {
    		$condition['end_time'] = array('elt', strtotime($_POST['end_time']));
    		$con['end_time'] = $_POST['end_time'];
    	}
    	$condition['hycoupon_id'] = $_REQUEST['id'];
    	$count = $couponModel->where($condition)->count();
    	$p = new Page($count, 10);
    	$page = $p->show();
    	$data = $couponModel->alias('c')->join(' LEFT JOIN `sk_member` k on c.rec=k.open_id')->field('c.*,k.wei_nickname')->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order(" add_time desc")->select();
		echo $couponModel->getLastSql();exit();
//     	dump($data);
		$this->assign('id',$_REQUEST['id']);
		$this->assign('con',$con);
		$this->assign('page',$page);
    	$this->assign('list',$data);
    	$this->display();
    	
    }

}
