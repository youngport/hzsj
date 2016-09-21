<?php
class goods_gongyingAction extends baseAction {
	public function gys_index() {
	    //搜索
	    $keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : '';
	    
	    if ($keyword) {
	        $where['_string'] = " gys_name LIKE '%" . $keyword . "%'";
	        $this->assign('keyword', $keyword);
	    }

	    $items_cate_mod = M('goods_gongying');
	    $count = $items_cate_mod -> where($where) ->count();// 查询满足要求的总记录数
	    if($_GET['p'])
	        $items_cate_mod = $items_cate_mod -> where($where) -> page($_GET['p'].',20') -> select();
	    else
	        $items_cate_mod = $items_cate_mod -> where($where) -> limit('0,20') -> select();
	    
	    import("ORG.Util.Page");// 导入分页类
	    $Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
	    $show = $Page->show();// 分页显示输出
	    $this->assign('page',$show);// 赋值分页输出
	    $this->assign('items_cate_list', $items_cate_mod);
	    $this->display();
	}
	function gys_add(){
	    $this->display();
	}
	function gys_add_get(){
	    if(isset($_POST['gys_bianma']) && trim($_POST['gys_bianma']))
	         $data['gys_bianma'] = trim($_POST['gys_bianma']);
	    if(isset($_POST['gys_name']) && trim($_POST['gys_name']))
	         $data['gys_name'] = trim($_POST['gys_name']);
	    if(isset($_POST['gys_qq']) && trim($_POST['gys_qq']))
	         $data['gys_qq'] = trim($_POST['gys_qq']);
	    if(isset($_POST['gys_shouji']) && trim($_POST['gys_shouji']))
	         $data['gys_shouji'] = trim($_POST['gys_shouji']);
	    
	    $gys_m = M('goods_gongying');
	    
	    $new_item_id = $gys_m->add($data);
	    if ($new_item_id) { 
	        $this->success("新增供应商成功",U("gys_index"));
	    } else {
	        $this->error('新增供应商失败');
	    }
	}
	function gys_bianji(){
	    $gys_m = M('goods_gongying');

	    if(isset($_GET['id']) && trim($_GET['id']))
	        $where['gys_id'] = trim($_GET['id']);
	    $gys_m = $gys_m -> where($where) -> select();
	    $this->assign('items_cate_list', $gys_m);
	    $this -> display();
	}
	function gys_bianji_get(){

	    if(isset($_GET['id']) && trim($_GET['id']))
	        $where['gys_id'] = trim($_GET['id']);
	    if(isset($_POST['gys_bianma']) && trim($_POST['gys_bianma']))
	         $data['gys_bianma'] = trim($_POST['gys_bianma']);
	    if(isset($_POST['gys_name']) && trim($_POST['gys_name']))
	         $data['gys_name'] = trim($_POST['gys_name']);
	    if(isset($_POST['gys_qq']) && trim($_POST['gys_qq']))
	         $data['gys_qq'] = trim($_POST['gys_qq']);
	    if(isset($_POST['gys_shouji']) && trim($_POST['gys_shouji']))
	         $data['gys_shouji'] = trim($_POST['gys_shouji']);
	    
	    $gys_m = M('goods_gongying');
	    
	    $new_item_id = $gys_m->where($where)->save($data);
	    if ($new_item_id) { 
	        $this->success("供应商修改成功",U("gys_index"));
	    } else {
	        $this->error('供应商修改失败');
	    }
	}
	function gys_delete(){
	    if(isset($_GET['id']) && trim($_GET['id'])){
            $where['gys_id'] = trim($_GET['id']);
            $gys_m = M('goods_gongying');
            $tr_fla = $gys_m->where($where)->delete();
    	    if ($tr_fla) { 
    	        $this->success("供应商删除改成功",U("gys_index"));
    	    } else {
    	        $this->error('供应商删除失败');
    	    }
	    }
	}
	public function gys_cangku_index() {
	    
	    //搜索
	    $keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : '';
	    $gys = isset($_GET['gys']) && trim($_GET['gys']) ? trim($_GET['gys']) : '';
	    $goodsid = isset($_GET['goodsid']) && trim($_GET['goodsid']) ? trim($_GET['goodsid']) : '';
	    
	    if ($keyword) {
	        $where['_string'] = " cangku_name LIKE '%" . $keyword . "%'";
	        $this->assign('keyword', $keyword);
	    }
	    if ($gys) {
	        $where['_string'] = " gongying_bianhao = '" . $gys . "'";
	        $this->assign('gys', $gys);
	    }
	    if ($goodsid) {
	        $where['_string'] = " bang_goods_id = '" . $goodsid . "'";
	        $this->assign('goodsid', $goodsid);
	        

	        $items_cate_mod = M('goods_cangku');
	        $count = $items_cate_mod -> join('sk_goods_gongying_cangku on cangku_bianhao = sk_goods_cangku.bang_cangku_id') -> join('sk_goods_gongying on gys_bianma = sk_goods_gongying_cangku.gongying_bianhao') -> where($where) -> count();// 查询满足要求的总记录数
	        if($_GET['p'])
	            $items_cate_mod = $items_cate_mod -> join('sk_goods_gongying_cangku on cangku_bianhao = sk_goods_cangku.bang_cangku_id') -> join('sk_goods_gongying on gys_bianma = sk_goods_gongying_cangku.gongying_bianhao') -> where($where) -> page($_GET['p'].',20') -> select();
	        else
	            $items_cate_mod = $items_cate_mod -> join('sk_goods_gongying_cangku on cangku_bianhao = sk_goods_cangku.bang_cangku_id') -> join('sk_goods_gongying on gys_bianma = sk_goods_gongying_cangku.gongying_bianhao') -> where($where) -> limit('0,20') -> select();
	        import("ORG.Util.Page");// 导入分页类
	        $Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
	        $show = $Page->show();// 分页显示输出
	        $this->assign('page',$show);// 赋值分页输出
	        $this->assign('items_cate_list', $items_cate_mod);
	        $this->display();
	    }else{
    	    $items_cate_mod = M('goods_gongying_cangku');
    	    $count = $items_cate_mod -> join('sk_goods_gongying on gys_bianma = sk_goods_gongying_cangku.gongying_bianhao') -> where($where) -> count();// 查询满足要求的总记录数
    	    if($_GET['p'])
    	        $items_cate_mod = $items_cate_mod -> join('sk_goods_gongying on gys_bianma = sk_goods_gongying_cangku.gongying_bianhao') -> where($where) -> page($_GET['p'].',20') -> select();
    	    else
    	        $items_cate_mod = $items_cate_mod -> join('sk_goods_gongying on gys_bianma = sk_goods_gongying_cangku.gongying_bianhao') -> where($where) -> limit('0,20') -> select();
    	    
    	    import("ORG.Util.Page");// 导入分页类
    	    $Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
    	    $show = $Page->show();// 分页显示输出
    	    $this->assign('page',$show);// 赋值分页输出
    	    $this->assign('items_cate_list', $items_cate_mod);
    	    $this->display();
	    }
	}

	function gys_cangku_bianji(){
	    $gys_m = M('goods_gongying_cangku');
	
	    if(isset($_GET['id']) && trim($_GET['id']))
	        $where['cangku_id'] = trim($_GET['id']);
	    $gys_m = $gys_m -> join('sk_goods_gongying on gys_bianma = sk_goods_gongying_cangku.gongying_bianhao') -> where($where) -> select();
	    $this->assign('items_cate_list', $gys_m);
	    $this -> display();
	}
	function cangku_bianji_get(){
        if(isset($_POST['gys_bianma']) && trim($_POST['gys_bianma'])){

            $gys_data['gys_bianma'] = trim($_POST['gys_bianma']);
            $items_cate_mod = M('goods_gongying');
            
            $count = $items_cate_mod -> where($gys_data) ->count();// 查询满足要求的总记录数
            if($count>0){
        	        $data['gongying_bianhao'] = trim($_POST['gys_bianma']);
        	    if(isset($_GET['cangku_id']) && trim($_GET['cangku_id']))
        	        $where['cangku_id'] = trim($_GET['cangku_id']);
        	    if(isset($_POST['cangku_bianhao']) && trim($_POST['cangku_bianhao']))
        	         $data['cangku_bianhao'] = trim($_POST['cangku_bianhao']);
        	    if(isset($_POST['cangku_name']) && trim($_POST['cangku_name']))
        	         $data['cangku_name'] = trim($_POST['cangku_name']);
        	    if(isset($_POST['cangku_date']) && trim($_POST['cangku_date']))
        	         $data['cangku_date'] = trim($_POST['cangku_date']);
        	    if(isset($_POST['cangku_fahuodizhi']) && trim($_POST['cangku_fahuodizhi']))
        	         $data['cangku_fahuodizhi'] = trim($_POST['cangku_fahuodizhi']);
        	    
        	    $gys_m = M('goods_gongying_cangku');
        	    
        	    $new_item_id = $gys_m->where($where)->save($data);
        	    if ($new_item_id) { 
        	        $this->success("仓库修改成功",U("gys_cangku_index"));
        	    } else {
        	        $this->error('仓库修改失败');
        	    }
            }else 
                $this->error('不存在该供应商编码');
        }else 
            $this->error('供应商编码不能为空');
            
	}
    function gys_cangku_add(){
        $this -> display();
    }
	function gys_cangku_addget(){
        if(isset($_POST['gys_bianma']) && trim($_POST['gys_bianma'])){

            $gys_data['gys_bianma'] = trim($_POST['gys_bianma']);
            $items_cate_mod = M('goods_gongying');
            
            $count = $items_cate_mod -> where($gys_data) ->count();// 查询满足要求的总记录数
            if($count>0){
        	        $data['gongying_bianhao'] = trim($_POST['gys_bianma']);
        	    if(isset($_POST['cangku_bianhao']) && trim($_POST['cangku_bianhao']))
        	         $data['cangku_bianhao'] = trim($_POST['cangku_bianhao']);
        	    if(isset($_POST['cangku_name']) && trim($_POST['cangku_name']))
        	         $data['cangku_name'] = trim($_POST['cangku_name']);
        	    if(isset($_POST['cangku_date']) && trim($_POST['cangku_date']))
        	         $data['cangku_date'] = trim($_POST['cangku_date']);
        	    if(isset($_POST['cangku_fahuodizhi']) && trim($_POST['cangku_fahuodizhi']))
        	         $data['cangku_fahuodizhi'] = trim($_POST['cangku_fahuodizhi']);
        	    
        	    $gys_m = M('goods_gongying_cangku');
        	    
        	    $new_item_id = $gys_m->add($data);
        	    if ($new_item_id) { 
        	        $this->success("仓库添加成功",U("gys_cangku_index"));
        	    } else {
        	        $this->error('仓库添加失败');
        	    }
            }else 
                $this->error('不存在该供应商编码');
        }else 
            $this->error('供应商编码不能为空');
	}

	function cangku_delete(){
	    if(isset($_GET['cangku_id']) && trim($_GET['cangku_id'])){
	        $where['cangku_id'] = trim($_GET['cangku_id']);
	        $gys_m = M('goods_gongying_cangku');
	        $tr_fla = $gys_m->where($where)->delete();
	        if ($tr_fla) {
	            $this->success("仓库删除改成功",U("gys_cangku_index"));
	        } else {
	            $this->error('仓库删除失败');
	        }
	    }
	}

	function goods_cangku_bang(){
	    $goods_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : '';
	    if($goods_id){
	        $data['id'] = $goods_id;
	        $goods = M('goods');
	        $goods = $goods -> where($data) -> select();
	        $this -> assign('items_cate_list',$goods);
	    }
	    $this -> display();
	}
	function goods_cangku_bangget(){
	    $goods_id = isset($_GET['goods_id']) && intval($_GET['goods_id']) ? intval($_GET['goods_id']) : '';
	    $cangku_bianhao = isset($_POST['cangku_bianhao']) && intval($_POST['cangku_bianhao']) ? intval($_POST['cangku_bianhao']) : '';

	    $goods_gongying_cangku = M('goods_gongying_cangku');

	    $goods_gongying_cangku_data['cangku_bianhao'] = $cangku_bianhao;
	    $count = $goods_gongying_cangku -> where($goods_gongying_cangku_data)->count();// 查询满足要求的总记录数
	    if($count>0){
    	    if($goods_id !="" && $cangku_bianhao !=""){
    	        $data['bang_goods_id'] = $goods_id;
	            $data['bang_cangku_id'] = $cangku_bianhao;
    	        $goods_cangku = M('goods_cangku');
	            if($goods_cangku -> where($data) -> count() == 0){
        	        $goods_cangku = $goods_cangku -> add($data);
        	        if($goods_cangku)
    	               $this->success("绑定成功",U("goods/index"));
        	        else
    	               $this->error('绑定失败，请稍后再试');
    	        }else
    	            $this->error('该商品已绑定该仓库，不能重复绑定');
    	    }else{
	           $this->error('仓库编号不能为空');
    	    }
	    }else{
	        $this->error('不存在该仓库编码');
	    }
	}
	function delect_bang(){
	    $goods_id = isset($_GET['goods_id']) && intval($_GET['goods_id']) ? intval($_GET['goods_id']) : '';
	    $cangku_bianhao = isset($_GET['cangku_bianhao']) && intval($_GET['cangku_bianhao']) ? intval($_GET['cangku_bianhao']) : '';
	    if($goods_id && $cangku_bianhao){
	        $data['bang_cangku_id'] = $cangku_bianhao;
	        $data['bang_goods_id'] = $goods_id;
	        
	        $delect = M('goods_cangku');
	        $delect = $delect -> where($data) -> delete();
	        if($delect)
               $this->success("删除成功",U("goods/index"));
	        else
               $this->error('删除失败，请稍后再试');
	    }
	}
}
?>