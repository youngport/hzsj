<?php

class huodongAction extends  baseAction {
   public function index(){

       $canyuhdid = isset($_GET['canyuhdid']) && trim($_GET['canyuhdid']) ? trim($_GET['canyuhdid']) : '';
       $leim = isset($_GET['leim']) && trim($_GET['leim']) ? trim($_GET['leim']) : '';
       if($leim != "" && $canyuhdid != ""){
           $this->assign('canyuhdid', $canyuhdid);
           $this->assign('leim', $leim);
       }
       $where = '1=1';
       if (isset($_GET['hd_youxiao'])&&$_GET['hd_youxiao']!=-1){
           $where .= " AND hd_youxiao = ".$_GET['hd_youxiao'];
           $this->assign('hd_youxiao', $_GET['hd_youxiao']);
       }else 
           $this->assign('hd_youxiao', -1);
           
       $hd_name = isset($_GET['hd_name']) && trim($_GET['hd_name']) ? trim($_GET['hd_name']) : '';
       if ($hd_name){
            $where .= " AND hd_name like '%".$hd_name."%'";
       }

       $datatime_jieshu = isset($_GET['datatime_jieshu']) && trim($_GET['datatime_jieshu']) ? trim($_GET['datatime_jieshu']) : '';
       $datatime_kaishi = isset($_GET['datatime_kaishi']) && trim($_GET['datatime_kaishi']) ? trim($_GET['datatime_kaishi']) : '';
		if ($datatime_jieshu) {
			$addtime_start_int = strtotime($datatime_jieshu);
			$where .= " AND hd_jieshu>='" . $addtime_start_int . "'";
			$this->assign('datatime_jieshu', $datatime_jieshu);
		}
		if ($datatime_kaishi) {
			$addtime_end_int = strtotime($datatime_kaishi);
			$where .= " AND hd_kaishi<='" . $addtime_end_int . "'";
			$this->assign('datatime_kaishi', $datatime_kaishi);
		}
	   $tj['_string'] = $where;
       $huodong = M('huodong');
       $count = $huodong -> where($tj) ->count();// 查询满足要求的总记录数
       if($_GET['p'])
           $huodong = $huodong -> where($tj) -> order('paixu asc') -> page($_GET['p'].',20') -> select();
       else
           $huodong = $huodong -> where($tj) -> order('paixu asc') -> limit('0,20') -> select();
       
       import("ORG.Util.Page");// 导入分页类
       $Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
       $show = $Page->show();// 分页显示输出
       $this->assign('page',$show);// 赋值分页输出
       

       $this->assign('hd_name', $hd_name);
       $this->assign('items_cate_list',$huodong);
       $this->display();
   }
   public function delete(){
	    $hdM = M("huodong");
	    $where['huodong_id'] = $_GET['huodong_id'];
	    $hdM = $hdM->where($where)->delete();
	    $bang = M("huodong_bang ");
	    $bangwhere['huodongid'] = $_GET['huodong_id'];
	    $bang = $bang->where($bangwhere)->delete();
	    
        if($hdM)
	       $this->success('删除成功',U('huodong/index'));
        else
	       $this->error('删除失败！');
   }
   public function huodong_up(){
        $hdM = M("huodong");
        $where['huodong_id'] = $_GET['huodong_id'];
        $hdM = $hdM->where($where)->find();
        $this->assign('items_cate_list',$hdM);
        $this->display();
   }
   public function huodong_up_get(){
       if ($_FILES['img']['name'] != ''){
           $savePath = $_FILES['img'];
           import("ORG.Net.UploadFile");
           $upload = new UploadFile();
           //设置上传文件大小
           $upload->maxSize = 32922000;
           $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
           $upload->savePath = ROOT_PATH.'/data/huodong/'.$savePath.'/';
           $upload->saveRule = uniqid;
       
           if (!$upload->upload()) {
               //捕获上传异常
               $this->error($upload->getErrorMsg());
           } else {
               //取得成功上传的文件信息
               $uploadList = $upload->getUploadFileInfo();
           }
           $uploadList='./data/huodong/'.$savePath.'/'.$uploadList['0']['savename'];
           $data['hd_img'] = $uploadList;
       }
        $hdM = M("huodong");
        $where['huodong_id'] = $_GET['huodong_id'];
        $data['hd_name'] = isset($_POST['hd_name']) && trim($_POST['hd_name']) ? trim($_POST['hd_name']) : '';
        $data['hd_jianjie'] = isset($_POST['hd_jianjie']) && trim($_POST['hd_jianjie']) ? trim($_POST['hd_jianjie']) : '';
        $data['hd_kaishi'] = isset($_POST['hd_kaishi']) && trim($_POST['hd_kaishi']) ? trim($_POST['hd_kaishi']) : '';
        $data['paixu'] = isset($_POST['paixu']) && trim($_POST['paixu']) ? trim($_POST['paixu']) : '';
        $data['hd_jieshu'] = isset($_POST['hd_jieshu']) && trim($_POST['hd_jieshu']) ? trim($_POST['hd_jieshu']) : '';
        $data['hd_youxiao'] = isset($_POST['hd_youxiao']) && trim($_POST['hd_youxiao']) ? trim($_POST['hd_youxiao']) : '';

	    $hdM = $hdM->where($where)->save($data); 
	    	    
        if($hdM)
	       $this->success('修改成功',U('huodong/index'));
        else
	       $this->error('修改失败！');
   }
   public function huodong_add_get(){
       if ($_FILES['img']['name'] != ''){
           $savePath = $_FILES['img'];
           import("ORG.Net.UploadFile");
           $upload = new UploadFile();
           //设置上传文件大小
           $upload->maxSize = 32922000;
           $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
           $upload->savePath = ROOT_PATH.'/data/huodong/'.$savePath.'/';
           $upload->saveRule = uniqid;
       
           if (!$upload->upload()) {
               //捕获上传异常
               $this->error($upload->getErrorMsg());
           } else {
               //取得成功上传的文件信息
               $uploadList = $upload->getUploadFileInfo();
           }
           $uploadList='./data/huodong/'.$savePath.'/'.$uploadList['0']['savename'];
           $data['hd_img'] = $uploadList;
       }
        $hdM = M("huodong");
        $where['huodong_id'] = $_GET['huodong_id'];
        $data['hd_name'] = isset($_POST['hd_name']) && trim($_POST['hd_name']) ? trim($_POST['hd_name']) : '';
        $data['hd_jianjie'] = isset($_POST['hd_jianjie']) && trim($_POST['hd_jianjie']) ? trim($_POST['hd_jianjie']) : '';
        $data['hd_kaishi'] = isset($_POST['hd_kaishi']) && trim($_POST['hd_kaishi']) ? trim($_POST['hd_kaishi']) : '';
        $data['paixu'] = isset($_POST['paixu']) && trim($_POST['paixu']) ? trim($_POST['paixu']) : '';
        $data['hd_jieshu'] = isset($_POST['hd_jieshu']) && trim($_POST['hd_jieshu']) ? trim($_POST['hd_jieshu']) : '';
        $data['hd_youxiao'] = isset($_POST['hd_youxiao']) && trim($_POST['hd_youxiao']) ? trim($_POST['hd_youxiao']) : '';

	    $hdM = $hdM->where($where)->add($data); 
	    	    
        if($hdM)
	       $this->success('添加成功',U('huodong/index'));
        else
	       $this->error('添加失败！');
   }
   public function huodong_add(){
       $this -> display();
   }
   function huodong_bang(){
       $canyuhdid = isset($_GET['canyuhdid']) && trim($_GET['canyuhdid']) ? trim($_GET['canyuhdid']) : '';
       $leim = isset($_GET['leim']) && trim($_GET['leim']) ? trim($_GET['leim']) : '';
       $huodong_id = isset($_GET['huodong_id']) && trim($_GET['huodong_id']) ? trim($_GET['huodong_id']) : '';
       if($leim != "" && $canyuhdid != "" && $huodong_id != ""){
           $huodong_bang = M('huodong_bang');
           $data['huodongid'] = $huodong_id;
           $data['canyuid'] = $canyuhdid;
           $data['canyulei'] = $leim;
           $cou = $huodong_bang -> where($data) -> count();
           if($cou>0){
	           $this->error('已绑定到该活动');
           }else{
               $cou = $huodong_bang -> add($data);
               if($cou)
        	       $this->success('绑定成功',U('huodong/index'));
                else
        	       $this->error('绑定失败！');
           }
       }
   }
   function sel_fenlei(){

       $canyuhdid = isset($_GET['huodong_id']) && trim($_GET['huodong_id']) ? trim($_GET['huodong_id']) : '';
       $Model = new Model(); 
       $Model = $Model -> query('SELECT *,(select name from sk_mingp where b.canyuid = id) mp_name,(select name from sk_gongx where b.canyuid = id) gx_name,(select name from sk_items_cate where b.canyuid = id) fl_name FROM sk_huodong_bang b where huodongid ='.$canyuhdid);
       
       $this->assign('items_cate_list',$Model);
       $this -> display();
   }
   public function delete_bang(){
	    $bang = M("huodong_bang ");
	    $where['id'] = $_GET['id'];
	    $bang = $bang->where($where)->delete();
	    
        if($bang)
	       $this->success('参与解除成功',U('huodong/index'));
        else
	       $this->error('参与解除失败！');
   }
   function jinri_hd_fenxiang_index(){
       
       $where = '1=1';
       if (isset($_GET['hd_youxiao'])&&$_GET['hd_youxiao']!=-1){
           $where .= " AND hd_youxiao = ".$_GET['hd_youxiao'];
           $this->assign('hd_youxiao', $_GET['hd_youxiao']);
       }else
           $this->assign('hd_youxiao', -1);
        
       $biaoti = isset($_GET['biaoti']) && trim($_GET['biaoti']) ? trim($_GET['biaoti']) : '';
       if ($biaoti){
           $where .= " AND biaoti like '%".$biaoti."%'";
       }
       $tj['_string'] = $where;
       $huodong = M('jrhdlfx');
       $count = $huodong -> where($tj) ->count();// 查询满足要求的总记录数
       if($_GET['p'])
           $huodong = $huodong -> where($tj) -> page($_GET['p'].',20') -> select();
       else
           $huodong = $huodong -> where($tj) -> limit('0,20') -> select();
        
       import("ORG.Util.Page");// 导入分页类
       $Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
       $show = $Page->show();// 分页显示输出
       $this->assign('page',$show);// 赋值分页输出
        
   
       $this->assign('biaoti', $biaoti);
       $this->assign('items_cate_list',$huodong);
       $this->display();
   }

   public function jinri_hd_fenxiang_add_get(){
       if ($_FILES['img']['name'] != ''){
           $savePath = $_FILES['img'];
           import("ORG.Net.UploadFile");
           $upload = new UploadFile();
           //设置上传文件大小
           $upload->maxSize = 32922000;
           $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
           $upload->savePath = ROOT_PATH.'/data/huodong/'.$savePath.'/';
           $upload->saveRule = uniqid;
            
           if (!$upload->upload()) {
               //捕获上传异常
               $this->error($upload->getErrorMsg());
           } else {
               //取得成功上传的文件信息
               $uploadList = $upload->getUploadFileInfo();
           }
           $uploadList='./data/huodong/'.$savePath.'/'.$uploadList['0']['savename'];
           $data['img'] = $uploadList;
       }
       $hdM = M("jrhdlfx");
       $data['biaoti'] = isset($_POST['biaoti']) && trim($_POST['biaoti']) ? trim($_POST['biaoti']) : '';
       $data['neirong'] = isset($_POST['neirong']) && trim($_POST['neirong']) ? trim($_POST['neirong']) : '';
       $data['hd_youxiao'] = isset($_POST['hd_youxiao']) && trim($_POST['hd_youxiao']) ? trim($_POST['hd_youxiao']) : '';
   
       $hdM = $hdM->add($data);
   
       if($hdM)
           $this->success('添加成功',U('huodong/jinri_hd_fenxiang_index'));
       else
           $this->error('添加失败！');
   }
   public function jinri_hd_fenxiang_add(){
       $this -> display();
   }
   public function jinri_hd_fenxiang_up(){
       $hdM = M("jrhdlfx");
       $where['jrhdlfxid'] = $_GET['jrhdlfxid'];
       $hdM = $hdM->where($where)->find();
       $this->assign('items_cate_list',$hdM);
       $this->display();
   }
   public function jinri_hd_fenxiang_up_get(){
       if ($_FILES['img']['name'] != ''){
           $savePath = $_FILES['img'];
           import("ORG.Net.UploadFile");
           $upload = new UploadFile();
           //设置上传文件大小
           $upload->maxSize = 32922000;
           $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
           $upload->savePath = ROOT_PATH.'/data/huodong/'.$savePath.'/';
           $upload->saveRule = uniqid;
            
           if (!$upload->upload()) {
               //捕获上传异常
               $this->error($upload->getErrorMsg());
           } else {
               //取得成功上传的文件信息
               $uploadList = $upload->getUploadFileInfo();
           }
           $uploadList='./data/huodong/'.$savePath.'/'.$uploadList['0']['savename'];
           $data['img'] = $uploadList;
       }
       $hdM = M("jrhdlfx");
       $where['jrhdlfxid'] = $_GET['jrhdlfxid'];
       $data['biaoti'] = isset($_POST['biaoti']) && trim($_POST['biaoti']) ? trim($_POST['biaoti']) : '';
       $data['neirong'] = isset($_POST['neirong']) && trim($_POST['neirong']) ? trim($_POST['neirong']) : '';
       $data['hd_youxiao'] = isset($_POST['hd_youxiao']) && trim($_POST['hd_youxiao']) ? trim($_POST['hd_youxiao']) : '';
   
       $hdM = $hdM->where($where)->save($data);
   
       if($hdM)
           $this->success('修改成功',U('huodong/jinri_hd_fenxiang_index'));
       else
           $this->error('修改失败！');
   }
   public function jinri_hd_fenxiang_delete(){
	    $hdM = M("jrhdlfx");
	    $where['jrhdlfxid'] = $_GET['jrhdlfxid'];
	    $hdM = $hdM->where($where)->delete();
	    
        if($hdM)
	       $this->success('删除成功',U('huodong/jinri_hd_fenxiang_index'));
        else
	       $this->error('删除失败！');
   }
}