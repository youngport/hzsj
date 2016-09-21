<?php

class items_cateAction extends baseAction {

    public $cate_list;

    function _initialize() {
        parent::_initialize();
        //每次显示的时候清除缓存
        if (is_dir("./admin/Runtime")) {
            deleteCacheData("./admin/Runtime");
        }
        $this->cate_list = get_items_cate_list('0', '0', '1', 'collect_miao');
    }

    //分类列表
    function index() {
        $items_cate_mod = M('items_cate');
        $result = $items_cate_mod->order('ordid ASC')->select();
        $this->assign('items_cate_list', $this->cate_list['sort_list']);
        $this->display();
    }

    //添加分类数据
    function add() {
        if (isset($_POST['dosubmit'])) {
            $items_cate_mod = M('items_cate');
            if (false === $vo = $items_cate_mod->create()) {
                $this->error($items_cate_mod->error());
            }
            if ($vo['name'] == '') {
                $this->error('分类名称不能为空');
            }
            $result = $items_cate_mod->where("name='" . $vo['name'] . "' AND pid='" . $vo['pid'] . "'")->count();
            if ($result != 0) {
                $this->error('该分类已经存在');
            }

            if ($_FILES['img']['name'] != '') {
                $upload_list = $this->_upload('items_cate');
                $vo['img'] = $upload_list;
            }
            //保存当前数据
            $items_cate_id = $items_cate_mod->add($vo);
            $this->success(L('operation_success'));
        }
        //dump($this->cate_list['sort_list']);
        $this->assign('items_cate_list', $this->cate_list['sort_list']);
        $this->assign('show_header', false);
        $this->display('edit');
    }

    function delete() {
        if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的分类！');
        }
        $items_cate_mod = M('items_cate');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            /*
              foreach($_POST['id'] as $val){
              @unlink(ROOT_PATH."/data/items_cate/".$items_cate_mod->where('id='.$val)->getField('img'));
              }
             */
            $items_cate_mod->delete(implode(',', $_POST['id']));
        } else {
            $items_cate_id = intval($_GET['id']);
            /*
              @unlink(ROOT_PATH."/data/items_cate/".$items_cate_mod->where('id='.$items_cate_id)->getField('img'));
             */
            $items_cate_mod->delete($items_cate_id);
        }

        $this->success(L('operation_success'));
    }

    function edit() {
        if (isset($_POST['dosubmit'])) {
            $items_cate_mod = M('items_cate');

            $old_items_cate = $items_cate_mod->where('id=' . $_POST['id'])->find();
            //名称不能重复
            if ($_POST['name'] != $old_items_cate['name']) {
                if ($this->_items_cate_exists($_POST['name'], $_POST['pid'], $_POST['id'])) {
                    $this->error('分类名称重复！');
                }
            }

            //获取此分类和他的所有下级分类id
            $vids = array();
            $children[] = $old_items_cate['id'];
            $vr = $items_cate_mod->where('pid=' . $old_items_cate['id'])->select();
            foreach ($vr as $val) {
                $children[] = $val['id'];
            }
            if (in_array($_POST['pid'], $children)) {
                $this->error('所选择的上级分类不能是当前分类或者当前分类的下级分类！');
            }

            $vo = $items_cate_mod->create();
            if ($_FILES['img']['name'] != '') {

                $upload_list = $this->_upload('items_cate');
                $vo['img'] = $upload_list;
                //删去老图片
                $img_dir = $old_items_cate['img'];
                if (file_exists($img_dir)) {
                    @unlink($img_dir);
                }
            }

            if (!isset($_POST['is_hots'])) {
                $vo['is_hots'] = 0;
            }
            if (!isset($_POST['status'])) {
                $vo['status'] = 0;
            }
            $result = $items_cate_mod->save($vo);
            if (false !== $result) {
                $this->success('修改成功', U('items_cate/index'), 1);
            } else {
                $this->error('修改失败', U('items_cate/index'));
            }
        }
        $this->assign('items_cate_list', $this->cate_list['sort_list']);
        $items_cate_mod = M('items_cate');
        if (isset($_GET['id'])) {
            $items_cate_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select') . L('article_name'));
        }
        $items_cate_info = $items_cate_mod->where('id=' . $items_cate_id)->find();

        //print_r($items_cate_info);

        $this->assign('items_cate_info', $items_cate_info);
        $this->assign('show_header', false);
        $this->display();
    }

    //批量添加分类数据
    function add_all() {
        if (isset($_POST['dosubmit'])) {
            $items_cate_mod = M('items_cate');
            if (false === $vo = $items_cate_mod->create()) {
                $this->error($items_cate_mod->error());
            }
            if ($vo['name'] == '') {
                $this->error('分类名称不能为空');
            }

            $_name = str_replace('|', '', trim($vo['name']));
            $_name_arr = array_unique(explode("\r\n", $_name));
            $keywords = str_replace('|', '', trim($vo['keywords']));
            $keywords_arr = array_unique(explode("\r\n", $keywords));
            foreach ($_name_arr as $key => $val) {
                $result = $items_cate_mod->where("name='" . $val . "' AND pid='" . $vo['pid'] . "'")->count();
                if ($result == 0) {//如果不存在执行插入操作		
                    if (count($_name_arr) == count($keywords_arr)) {
                        $vo['keywords'] = trim($keywords_arr[$key]);
                    } else {
                        $vo['keywords'] = trim($val);
                    }
                    $vo['name'] = trim($val);
                    //保存当前数据
                    $items_cate_id = $items_cate_mod->add($vo);
                }
            }
            $this->success(L('operation_success'));
        }
        //dump($this->cate_list['sort_list']);
        $this->assign('items_cate_list', $this->cate_list['sort_list']);
        $this->assign('show_header', false);
        $this->display();
    }

    private function _items_cate_exists($name, $pid, $id = 0) {
        $result = M('items_cate')->where("name='" . $name . "' AND pid='" . $pid . "' AND id<>'" . $id . "'")->count();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function sort_order() {
        $items_cate_mod = M('items_cate');
        if (isset($_POST['listorders'])) {
            foreach ($_POST['listorders'] as $id => $sort_order) {
                $data['ordid'] = $sort_order;
                $items_cate_mod->where('id=' . $id)->save($data);
            }
            $this->success(L('operation_success'));
        } else {
            $this->error(L('operation_failure'));
        }
    }

    function mp_cate() {
        $where = array();
        if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
            $where['name|keywords'] = array('like', '%' . trim($_GET['keyword']) . '%');
        }
       
        if(!empty($_GET['pcate'])){
            if( $_GET['pcate'] !="-1"){
                $pcate=$_GET['pcate'];
               $where['cate_id']=$pcate;
            }
        }   
        
        //所属分类
        $item_cate_mod = M('items_cate');
        $arr = $item_cate_mod->field('id ,name')->where("pid='5'")->select();
        $this->assign('data', $arr);
        $cate = M('mingp_cate');
        import("ORG.Util.Page");
        $count = $cate->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $this->assign('page', $show); // 赋值分页输出
        $list = $cate->where($where)->order('sort desc,id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
//        echo  $cate->getLastSql();
        $this->assign("keywords", $_GET['keyword']);
        $this->assign("pcate", $pcate);
        $this->assign('list', $list);
        $this->display();
    }

    function mp_cate_cz() {
        $cz = $_REQUEST['cz'] ? trim($_REQUEST['cz']) : '';
        $cate = M('mingp_cate');
        if ($this->isPost()) {
            if ($cz == 'status') {
                $id = intval($_POST['id']);
                ($status = $cate->where('id=' . $id)->getField('status')) !== false || $this->error(false);
                $status = $status == 1 ? 0 : 1;
                $cate->where('id=' . $id)->setField('status', $status);
                $this->ajaxReturn($status);
            }
            $data = array();
            $data['name'] = addslashes($_POST['name']);
            $data['keywords'] = $_POST['keywords'];
            $data['sort'] = $_POST['sort'];
            $data['status'] = $_POST['status'];
            $data['cate_id'] = $_POST['cate_id'];
            if ($cz == 'add') {
                if ($_FILES['mpimg']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img']);
                    $data['mp_img'] = $upload_list;
                } else {
                    $this->error('请上传图片');
                    return FALSE;
                }
                if ($cate->add($data)) {
                    $this->success('添加成功', U('items_cate/mp_cate'));
                } else {
                    $this->error('添加失败', U('items_cate/mp_cate'));
                }
            }

            if ($cz == 'edit') {
                if ($_FILES['mpimg']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img']);
                    $data['mp_img'] = $upload_list;
                }
                $id = $_POST['id'];
                $result = $cate->where("id='$id'")->save($data);
                if ($result) {
                    $this->success('修改成功', U('items_cate/mp_cate'));
                } else {
                    $this->error('修改失败', U('items_cate/mp_cate'));
                }
            }
        } else if ($cz == 'edit') {
            $id = intval($_GET['id']);
            if (!$data = $cate->find($id))
                $this->error('没有此栏目');
            $this->assign('data', $data);
        }
        $this->assign('cz', $cz);
        //所属分类
//        $arr=array(0=>'--请选择--',1=>"母婴",2=>'美妆',3=>'保健品',4=>'休闲食品');
        $item_cate_mod = M('items_cate');
        $arr = $item_cate_mod->field('id ,name')->where("pid='5'")->select();
        $this->assign('arr', $arr);
        $this->display();
    }

    function mpcate_del() {
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
            $cate = M('mingp_cate');
            $result = $cate->where("id='$id'")->delete();
            if ($result) {
                $this->success('删除成功', U('mp_cate'));
            } else {
                $this->error('删除成功');
            }
        } else {
            $this->error('记录不存在');
        }
    }

    function mp_index() {
//        if (isset($_GET['status']) && $_GET['status'] != -1) {
//            $tj['huodong'] = $_GET['status'];
//        }
       
        if (isset($_GET['tex']) && $_GET['tex'] != "") {
            $tj['_string'] = "name like '%" . $_GET['tex'] . "%'";
            $tx = $_GET['tex'];
        }
        
        if(isset($_GET['pcate']) && $_GET['pcate'] !="-1"){
            $tj['cate_id'] = $_GET['pcate'];
        }
        
        /*  if (isset($_GET['id'])){
          $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的详情链接');
          $whe['id'] = $id;
          $mingp = M("mingp");
          $mingp -> where($whe) -> delete();
          } */
        //dump($_GET['status']);
        //dump($tj['huodong']);
        $mingp = M('mingp');
        $count = $mingp->where($tj)->count(); // 查询满足要求的总记录数
        if ($_GET['p'])
            $mingp = $mingp->where($tj)->page($_GET['p'] . ',20')->select();
        else
            $mingp = $mingp->where($tj)->limit('0,20')->select();
        //echo  $mingp->getLastSql();
        import("ORG.Util.Page"); // 导入分页类
        $Page = new Page($count, 10); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $this->assign('page', $show); // 赋值分页输出

//        if ($tj['huodong'] == "")
//            $this->assign('status', -1);
//        else
//            $this->assign('status', $tj['huodong']);
        
        if($_GET['pcate']==""){
            $this->assign("pcate",-1 );
        }else{
            $this->assign("pcate", $_GET['pcate']);
        }
         //所属分类
        $item_cate_mod = M('items_cate');
        $arr = $item_cate_mod->field('id ,name')->where("pid='5'")->select();
        $this->assign('arr', $arr);
        
        $this->assign('keyword', $tx);
        $this->assign('items_cate_list', $mingp);
        $this->display();
    }

    function mpxiugai() {
        $mingp = M("mingp");
        $where['id'] = $_GET['id'];
        $mingp = $mingp->where($where)->select();
        $cate = M('mingp_cate')->where('status=1')->field('id,name')->order('sort desc')->select();
        $this->assign('cate', $cate);
        $this->assign('items_cate_list', $mingp);
        $this->display();
    }

    function mpxiugai_get() {
        $mingp = M("mingp");
        if ($_POST['name'] == '')
            $this->error('请填写名称');
        if (!is_numeric($_POST['cid']))
            $this->error('请选择分类');
        if ($data = $mingp->create()) {
            if ($_FILES['img']['name'] != '') {
                $upload_list = $this->_upload($_FILES['img']);
                $name['img'] = $upload_list;
            }
            if ($mingp->save($data))
                $this->success('修改成功');
        } else
            $this->error('修改失败');
        /* $map['name'] = $_POST['mp_name'];
          $map['_string'] = 'not in('.$_GET['id'].')';
          $count_i = $mingp->where($map)->count();
          if($count_i != 0){
          $this->error('该品牌已存在');
          die;
          return;
          } */
//		$where['id'] = $_GET['id'];
//		$name['name'] = $_POST['mp_name'];
//		$name['cid'] = $_POST['cid'];
//		$name['huodong'] = $_POST['Fruit'];
//		$name['huodks'] = $_POST['huodks'];
//		$name['huodjs'] = $_POST['huodjs'];
        /* $count_i = $mingp->where($name)->count(); 
          if($count_i != 0){
          $this->error('该品牌已存在');
          die;
          return;
          } */
        //$mingp->where($where)->save($name);
        //$this->error('修改成功');
    }

    function mp_add_get() {
        $mingp = M("mingp");
        $mp_name = addslashes($_POST['mp_name']);

        $data = array();
        $data['name'] = $mp_name;
        $data['cate_id'] = $_POST['cate_id'];
        $data['is_enable'] = $_POST['is_enable'];
        //修改
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
            
            if ($_FILES['img']['name'] != '') {
                $upload_list = $this->_upload($_FILES['img']);
                $data['mp_img'] = $upload_list;
            }
            if ($mingp->where("id='$id'")->save($data)) {
                $this->success("修改成功", U("mp_index"));
            } else {
                $this->error('修改失败');
            }
        } else {
            // 添加
            $count = $mingp->where($data)->count();
            //echo $mingp->getLastSql();die;
            if ($count != 0) {
                $this->error('该品牌已存在');
                return;
            }
            if ($_FILES['img']['name'] != '') {
                $upload_list = $this->_upload($_FILES['img']);
                $data['mp_img'] = $upload_list;
            } else {
                $this->error('请上传图片');
                return FALSE;
            }

            if ($mingp->add($data)) {
                $this->success("添加成功", U("mp_index"));
            } else {
                $this->error('添加失败');
            }
        }
    }

    function mp_add() {
//        $cate = M('mingp_cate')->where('status=1')->field('id,name')->order('sort desc')->select();
//        $this->assign('cate', $cate);
        //所属分类
        if (!empty($_GET)) {
            $id = $_GET['id'];
            $mingp = M("mingp");
            $data = $mingp->where("id='$id'")->select();
            $this->assign('id', $id);
            $this->assign('data', $data);
        }
        $item_cate_mod = M('items_cate');
//        $arr=array(0=>'--请选择--',1=>"母婴",2=>'美妆',3=>'保健品',4=>'休闲食品');
        $arr = $item_cate_mod->field('id ,name')->where("pid='5'")->select();
        $this->assign('arr', $arr);
        $this->display();
    }

    function mp_del() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (M('mingp')->delete($id))
            $this->success('删除成功');
        else
            $this->error('删除失败');
    }

    function gx_index() {
        if (isset($_GET['status']) && $_GET['status'] != -1) {
            $tj['huodong'] = $_GET['status'];
        }
        if (isset($_GET['tex']) && $_GET['tex'] != "") {
            $tj['_string'] = "name like '%" . $_GET['tex'] . "%'";
            $tx = $_GET['tex'];
        }
        $mingp = M('gongx');
        $count = $mingp->where($tj)->count(); // 查询满足要求的总记录数
        if ($_GET['p'])
            $mingp = $mingp->where($tj)->page($_GET['p'] . ',20')->select();
        else
            $mingp = $mingp->where($tj)->limit('0,20')->select();

        import("ORG.Util.Page"); // 导入分页类
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $this->assign('page', $show); // 赋值分页输出

        if ($tj['huodong'] == "")
            $this->assign('status', -1);
        else
            $this->assign('status', $tj['huodong']);
        $this->assign('keyword', $tx);

        $this->assign('items_cate_list', $mingp);
        $this->display();
    }

    function gx_xiugai() {
        $mingp = M("gongx");
        $where['id'] = $_GET['id'];
        $mingp = $mingp->where($where)->select();
        $this->assign('items_cate_list', $mingp);
        $this->display();
    }

    function gx_xiugai_get() {

        if ($_FILES['img']['name'] != '') {
            $upload_list = $this->_upload($_FILES['img']);
            $name['img'] = $upload_list;
        }
        $mingp = M("gongx");
        $where['id'] = $_GET['id'];
        $name['name'] = $_POST['mp_name'];
        $name['huodong'] = $_POST['Fruit'];
        $name['huodks'] = $_POST['huodks'];
        $name['huodjs'] = $_POST['huodjs'];
        $count_i = $mingp->where($name)->count();
        if ($count_i != 0) {
            $this->error('该功效已存在');
            die;
            return;
        }
        $mingp->where($where)->save($name);
        $this->error('修改成功');
    }

    function gx_add_get() {
        $mingp = M("gongx");
        $name['name'] = $_POST['mp_name'];
        $count_i = $mingp->where($name)->count();
        if ($count_i != 0) {
            $this->error('该功效已存在');
            die;
            return;
        }
        $mingp->add($name);
        $this->error('添加成功');
    }

    function gx_add() {
        $this->display();
    }

    public function z_upload($imgage, $path = '', $isThumb = true) {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');

        if (empty($savePath)) {
            $upload->savePath = './public/';
        } else {
            $upload->savePath = $path;
        }

        if ($isThumb === true) {
            $upload->thumb = true;
            $upload->imageClassPath = 'ORG.Util.Image';
            $upload->thumbPrefix = 'm_';
            $upload->thumbMaxWidth = '450';
            //设置缩略图最大高度
            $upload->thumbMaxHeight = '450';
            $upload->saveRule = uniqid;
            $upload->thumbRemoveOrigin = true;
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

    function gx_delete() {
        $User = M("gongx");
        $where['id'] = $_GET['deleteid'];
        $User->where($where)->delete();
        $this->success("删除成功", U("gx_index"));
    }

    function nat_index() {
        $where = ' 1=1';
        if($_POST['keyword']){
            $nat_name=  addslashes($_POST['keyword']);
            $where .=" and  nationality like '%".$nat_name."%' ";
        }
       
        if(!empty($_POST['pcate'])){
           $pcate=$_POST['pcate'];
           if($pcate != "-1"){
             $where .= " and cate_id ='".$pcate."'";
           }
        }
        $nat = M("nationality");
        import("ORG.Util.Page");
        $count = $nat->count();
        $Page = new Page($count, 8);
        $show = $Page->show();
        $list = $nat->where($where)->order('add_time desc,id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
//        echo  $nat->getLastSql();
         //所属分类
        $item_cate_mod = M('items_cate');
        $arr = $item_cate_mod->field('id ,name')->where("pid='5'")->select();
        $this->assign('arr', $arr);
        $this->assign("pcate", $pcate);
        $this->assign('keyword',$nat_name);
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('nat_list', $list);
        $this->display();
    }

    function nat_add() {
        if (!empty($_GET)) {
            $id = $_GET['id'];
            $nat = M("nationality");
            $data = $nat->where("id='$id'")->select();
            $this->assign('id', $id);
            $this->assign('data', $data);
        }
        //所属分类
        $item_cate_mod = M('items_cate');
//        $arr=array(0=>'--请选择--',1=>"母婴",2=>'美妆',3=>'保健品',4=>'休闲食品');
        $arr = $item_cate_mod->field('id ,name')->where("pid='5'")->select();
        $this->assign('arr', $arr);
        $this->display();
    }

    function nat_add_get() {
        if (!empty($_POST)) {
            $nat = M("nationality");
            $natName = addslashes($_POST['nat_name']);
            $data = array();
            $data['nationality'] = $natName;
            $id = $_POST['id'];
            $data['cate_id'] = $_POST['cate_id'];
            //die($id);
            if (!empty($id)) {
                if ($_FILES['img']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img']);
                    $data['nationality_img'] = $upload_list;
                }
                $data['update_time'] = time();
                $resut = $nat->where("id='$id'")->save($data);
                //echo $nat->getLastSql();die;
                if ($resut) {
                    $this->success("修改成功", U("nat_index"));
                } else {
                    $this->error('修改失败');
                }
            } else {
                $count = $nat->where($data)->count();
                if ($count != 0) {
                    $this->error('该国家已存在');
                    return;
                }

                if ($_FILES['img']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img']);
                    $data['nationality_img'] = $upload_list;
                } else {
                    $this->error('请上传图片');
                    return FALSE;
                }
                $data['add_time'] = time();
                if ($nat->add($data)) {
                    $this->success("添加成功", U("nat_index"));
                } else {
                    $this->error('添加失败');
                }
            }
        }
    }

    function nat_del() {
        if (!empty($_GET['id'])) {
            $nat = M("nationality");
            $nat->where("id='" . $_GET['id'] . "'")->delete();
            $this->success("删除成功", U("nat_index"));
        } else {
            $this->error('删除失败，该条记录不存在');
        }
    }

}

?>