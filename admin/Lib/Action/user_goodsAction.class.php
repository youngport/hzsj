<?php

Vendor('PHPExcel.PHPExcel');
class user_goodsAction extends baseAction {
	


	public function add() {
		if (isset($_POST['dosubmit'])) {
            $items_mod = D('user_goods');
            $items_cate_mod = D('items_cate');
            if (false === $items_mod->create()) {
                $this->error($items_mod->error());
            }
            if (empty($items_mod->good_name)) {
                $this->error('请填写商品标题');
            }
            /*if (empty($items_mod->cate_id)) {
                $this->error('请填写商品分类');
            }
            if (empty($items_mod->mp_id)) {
                $this->error('请选择商品品牌');
            }
            if (empty($items_mod->gx_id)) {
                $this->error('请选择商品功效');
            }*/
            $items_mod->boutique=trim($items_mod->boutique);
            $items_mod->pcode=trim($items_mod->pcode);
            $items_mod->pcode=trim($items_mod->pcode);
            $items_mod->price=round((float) $items_mod->price,2);
                $items_mod->orgprice=round((float) $items_mod->orgprice,2);
                $items_mod->fp_price=round((float) $items_mod->fp_price,2);
            if (empty($items_mod->pcode)) {
                $this->error('请填写商品编号');
            }
            $result = $items_mod->where("pcode='".$items_mod->pcode."'")->count();
            if($result != 0){
                $this->error('该商品编号已经存在');
            }
            if($_REQUEST['status']==1)
                $items_mod->add_time = time();
            $items_mod->goods_addtime = time();
            $items_mod->hits = 0;
            //
            if ($_FILES['img']['name'] != '') {
                $upload_list = $this->_upload($_FILES['img']);
                //print_r($upload_list);exit;
                //$items_mod->img = $this->site_root . 'data/goods/m_' . $upload_list['0']['savename'];
                $items_mod->img = 'data/goods/' . $upload_list['0']['savename'];
                $items_mod->simg = 'data/goods/m_' . $upload_list['0']['savename'];
                $items_mod->bimg = 'data/goods/m_' . $upload_list['0']['savename'];
                $upload_list = $this->_upload($_FILES['img2']);
                $items_mod->img2 = 'data/goods/m_' . $upload_list['0']['savename'];
            } else {
                $items_mod->img = $_POST['input_img'];
            }
            $new_item_id = $items_mod->add();
            if ($new_item_id) {
                $items_cate_mod->setInc('item_nums', 1);
                $this->success("新增商品信息成功");
            } else {
                $this->error('新增商品信息成功');
            }
		}else {
            $items_cate_mod = D('items_cate');
            $cate_list = $items_cate_mod->get_list(5);
            $this->assign('cate_list', $cate_list['sort_list']);
            $mingp = M('mingp');
            $mingp = $mingp->select();
            $this->assign('mingp', $mingp);
            $gongx = M('gongx');
            $gongx = $gongx->select();
            $this->assign('gongx', $gongx);
            $this->display();
        }
	}

	/*
    *上传单个文件
    */
	public function _upload($imgage, $path = '', $isThumb = true) {
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();      
		//设置上传文件大小
		$upload->maxSize = 3292200;
		$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');

		if (empty($savePath)) {
			$upload->savePath = './data/goods/';
		} else {
			$upload->savePath = $path;
		}

		if ($isThumb === true) {
			$upload->thumb = true;
			$upload->imageClassPath = 'ORG.Util.Image';
			$upload->thumbPrefix = 'm_,s_';
			$upload->thumbMaxWidth = '10240,227,50';
			//设置缩略图最大高度
			$upload->thumbMaxHeight = '10240,283,50';
			$upload->saveRule = uniqid;
			$upload->thumbRemoveOrigin = false; 

		}

		if (!$upload->uploadOne($imgage)) {
			//捕获上传异常
			$this->error($upload->getErrorMsg());
		} else {
			//取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
		}
		return $uploadList;
	}


    /*
     * 上传多个文件
     */
    public function _uploadM($imgage, $path = '', $isThumb = true) {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        $upload->saveRule = uniqid;
    
        if (empty($path)) {
            $upload->savePath = './data/goods/';
        } else {
            $upload->savePath = $path;
        }
    
        if ($isThumb === true) {
            $upload->thumb = true;
            $upload->imageClassPath = 'ORG.Util.Image';
            $upload->thumbPrefix = 'm_,s_';
            $upload->thumbMaxWidth = '10240,227,50';
            //设置缩略图最大高度
            $upload->thumbMaxHeight = '10240,283,50';
            $upload->saveRule = uniqid;
            $upload->thumbRemoveOrigin = false;
    
        }
    
        if (!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
        }
        return $uploadList;
    }
	
  function ug(){
        $where=array();
        if (isset($_GET['gid'])&&$_GET['gid']!=""){
            $where['gid']=intval($_GET['gid']);
        }
        if (isset($_GET['good_name'])&&$_GET['good_name']!=""){
            $where['good_name']=array("like","%".trim($_GET['good_name'])."%");
        }
        $sg=M('user_goods');
        //$sglist=$sg->query("select sg.*,goods.good_name,goods.orgprice from sk_sg sg inner join sk_goods goods on gs.gid=goods.id".$where." order by id desc");
        
        $sglist=$sg->field("sk_user_goods.*,g.id gid,good_name,orgprice")->join("sk_goods g on sk_user_goods.gid=g.id")->where($where)->order(array("id"=>"desc"))->select();
        //print_r($sglist);exit;
        //echo $sg->getLastSql();exit;
        import("ORG.Util.Page");
        $count=$sg->where($where)->count();
        $Page=new Page($count,15);
        $show=$Page->show();
        $this->assign('list',$sglist);
        $this->display();
    }
    function ug_cz(){
        $cz=$_REQUEST['cz']?trim($_REQUEST['cz']):'';
        $sg=M('user_goods');
        $id=$_REQUEST['id']?trim($_REQUEST['id']):0;
        if($this->isPost()){
            if($cz=='status'){
                ($status = $sg->where('id='.$id)->getField('status'))!==false||$this->error(false);
                $status=$status==1?0:1;
                $sg->where('id='.$id)->setField('status',$status);
                $this->ajaxReturn($status);
            }else if($cz=='del'&&$sg->delete(implode(',',$_POST['id'])))$this->success('删除成功');
            if($sg->create()){
                $sg->tax_price = $this->insertTax($sg->gid);
                $sg->start_time=strtotime($sg->start_time);
                $sg->end_time=strtotime($sg->end_time);
                if ($_FILES['view']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['view']);
                    $sg->view= 'data/goods/m_' . $upload_list['0']['savename'];
                }
                if ($_FILES['price_view']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['price_view']);
                    $sg->price_view='data/goods/m_' . $upload_list['0']['savename'];
                }
                if($cz=='add'){
                    $data=$sg->where('gid='.$sg->gid)->order('end_time desc')->getField('end_time');
                    if(isset($data['end_time'])&&$data['end_time']>time())
                        $this->error('已有该商品且还没过期');
                    else
                        if($sg->add())$this->success('添加成功',U('user_goods/ug'));
                }else if($cz=='edit'&&$sg->save()!==false)$this->success('修改成功',U('user_goods/ug'));
                $this->error('数据失败');
            }
        }else if($cz=='edit'){
            $data=$sg->find($id);
            if(empty($data))$this->error('没有此会员商品');
            $this->assign('data',$data);
        }
        $this->assign('cz',$cz);
        $this->display();

    }

        public function search(){
        if($this->isAjax()){
            if(is_numeric($_POST['good'])){
                $good=intval($_POST['good']);
                $where['id']=$good;
            }else {
                $good=trim($_POST['good']);
                $where['good_name']=array('like','%'.$good.'%');
            }
            $status=1;$info='ok';
            $data=M('goods')->where($where)->field('id,img')->find();
            if(empty($data)){
                $status=0;
                $info='no';
            }
            $this->ajaxReturn($data,$info,$status);
        }
    }


        /*
         * 微商城会员区图片
         */
        public function activity(){
            if(!empty($_POST)){
                $info = $this->_uploadM($_FILES,'data/member_activity/',false);
                $arr = array();
                foreach ($info as $k=>$v){
                    $arr['img_path' . ($k +1)] = $v['savepath'] . $v['savename'];
                }
                
                $result = M('member_img')->data($arr)->where("id = '1'")->save();
                // echo M('member_img')->getLastSql();exit();
                if($result !== false){
                    $this->success('添加成功!',U('user_goods/activity_list'));
                    exit();
                }else {
                    $this->error('添加失败!');
                }
            }
            $this->display();
        }
        
        /*
         * 展示
         */
        public function activity_list(){
            
            $data = M('member_img')->where("id = '1'")->find();
            $this->assign('data',$data);
            $this->display();
        }
        /*
         * 修改
         */
        public function activity_modify(){
            
            $data = M('member_img')->where("id = '1'")->find();
            if(!empty($_POST)){
                
                foreach ($_FILES as $k=>$v){
                
                if($v['size'] > 0 ){
                    $arr[$k] = $v;
                }
              }
              
              $info = $this->_uploadM($arr,'data/member_activity/',false);
              $i = 0;
              $picture = array();
              foreach ($arr as $kk=>$vv){
                $picture[$kk] = $info[$i]['savepath'] . $info[$i]['savename'];
                $i++;
              }
//            dump($picture);exit();
              
              $result = M('member_img')->data($picture)->where("id = '1'")->save();
              if($result !== false){
                $this->success('添加成功!',U('user_goods/activity_list'));
                exit();
              }else {
                $this->error('添加失败!');
              }
              
            }
            
            $this->assign('data',$data);
            $this->display();
            
            
        }
        
        
        public function insertTax($goods_id){
            

            $goodsModel = M('goods','sk_');
            $arr_goods = $goodsModel->field('cate_id,price,fh_type,gx_id')->where(array('id'=>$goods_id))->find();
//          dump($arr_goods);


                    
                $cate_id = $arr_goods["cate_id"];
                $model = M();
                $goods_type = $model->query("select pid from sk_items_cate where id='$cate_id' ");
                //          dump($goods_type);exit();
                if ($goods_type[0]['pid'] == '100' && $arr_goods["fh_type"] == '保税') {
                    $tax_rate = 0.329; //保税——美妆
                } elseif (($goods_type[0]['pid'] == '65' || $goods_type[0]['pid'] == '147' || $goods_type[0]['pid'] == '118') && $arr_goods["fh_type"] == '保税') {
                    $tax_rate = 0.119; //保税——母婴、食品、保健品
                } elseif ($goods_type[0]['pid'] == '100' && $arr_goods["fh_type"] == '直邮' && ($arr_goods["gx_id"] == '26' || $arr_goods["gx_id"] == '27' || $arr_goods["gx_id"] == '34' || $arr_goods["gx_id"] == '35' )) {
                    $tax_rate = 0.60; //直邮——美妆——(眼妆、面部彩妆、化妆卸妆、口红)
                } elseif (($goods_type[0]['pid'] == '65' || $goods_type[0]['pid'] == '147' || $goods_type[0]['pid'] == '118') && $arr_goods["fh_type"] == '直邮') {
                    $tax_rate = 0.15; //直邮——母婴、食品、保健品
                } else {
                    $tax_rate = 0.30; //直邮——美妆——(眼妆、面部彩妆、化妆卸妆、口红)以外的
                }
                    
                if($tax_rate * $arr_goods["price"] < 50 && $arr_goods['fh_type'] == '直邮'){
                    $tax_member = 0;
                }else{
                    $tax_member = round($tax_rate * $arr_goods["price"], 2);
                }
                return $tax_member;

      
                
        }


}

?>
