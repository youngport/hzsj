<?php

class guanggaoAction extends baseAction {

    // 菜单页面
    function guanggao() {
        //dump($_GET['openid']);die;
        $dianp = M('guanggao');
        $count = $dianp->count(); // 查询满足要求的总记录数
        if ($_GET['p'])
            $dianp = $dianp->order('gg_paixu asc')->page($_GET['p'] . ',20')->select();
        else
            $dianp = $dianp->order('gg_paixu asc')->limit('0,20')->select();

        import("ORG.Util.Page"); // 导入分页类
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $this->assign('page', $show); // 赋值分页输出


        $this->assign('items_cate_list', $dianp);
        $this->display();
    }

    function guanggao_add() {
        $this->display();
    }

    function guanggao_addget() {
        if ($_FILES['img']['name'] != '') {
            $savePath = $_FILES['img'];
            import("ORG.Net.UploadFile");
            $upload = new UploadFile();
            //设置上传文件大小
            $upload->maxSize = 32922000;
            $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
            $upload->savePath = ROOT_PATH . '/data/guanggao/' . $savePath . '/';
            $upload->saveRule = uniqid;

            if (!$upload->upload()) {
                //捕获上传异常
                $this->error($upload->getErrorMsg());
            } else {
                //取得成功上传的文件信息
                $uploadList = $upload->getUploadFileInfo();
            }
            $uploadList = './data/guanggao/' . $savePath . '/' . $uploadList['0']['savename'];
            $name['gg_imgurl'] = $uploadList;
        }
        $mingp = M("guanggao");
        $name['gg_youxiao'] = $_POST['gg_youxiao'];
        $name['gg_paixu'] = $_POST['gg_paixu'];
        $bol = $mingp->add($name);
        if ($bol)
            $this->success('添加成功', U('guanggao/guanggao'));
        else
            $this->error('添加失败！');
    }

    function guanggao_xg() {
        $mingp = M("guanggao");
        $where['gg_id'] = $_GET['id'];
        $mingp = $mingp->where($where)->select();
        $this->assign('items_cate_list', $mingp);
        $this->display();
    }

    function guanggao_xgget() {
        if ($_FILES['img']['name'] != '') {
            $savePath = $_FILES['img'];
            import("ORG.Net.UploadFile");
            $upload = new UploadFile();
            //设置上传文件大小
            $upload->maxSize = 32922000;
            $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
            $upload->savePath = ROOT_PATH . '/data/guanggao/' . $savePath . '/';
            $upload->saveRule = uniqid;

            if (!$upload->upload()) {
                //捕获上传异常
                $this->error($upload->getErrorMsg());
            } else {
                //取得成功上传的文件信息
                $uploadList = $upload->getUploadFileInfo();
            }
            $uploadList = './data/guanggao/' . $savePath . '/' . $uploadList['0']['savename'];
            $name['gg_imgurl'] = $uploadList;
        }
        $mingp = M("guanggao");
        $where['gg_id'] = $_GET['id'];
        $name['gg_youxiao'] = $_POST['gg_youxiao'];
        $name['gg_paixu'] = $_POST['gg_paixu'];
        $bol = $mingp->where($where)->save($name);
        if ($bol)
            $this->success('修改成功', U('guanggao/guanggao'));
        else
            $this->error('修改失败！');
    }

    function guanggao_del() {
        $mingp = M("guanggao");
        $where['gg_id'] = $_GET['id'];
        $mingp = $mingp->where($where)->delete();

        if ($mingp)
            $this->success('修改成功', U('guanggao/guanggao'));
        else
            $this->error('修改失败！');
    }

    ////////////////////////////////////////////// 菜单页面
    function video() {
        $dianp = M('video');
        $count = $dianp->count(); // 查询满足要求的总记录数
        if ($_GET['p'])
            $dianp = $dianp->order('vi_youxiao desc,vi_paixu asc')->page($_GET['p'] . ',20')->select();
        else
            $dianp = $dianp->order('vi_youxiao desc,vi_paixu asc')->limit('0,20')->select();

        import("ORG.Util.Page"); // 导入分页类
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $this->assign('page', $show); // 赋值分页输出


        $this->assign('items_cate_list', $dianp);
        $this->display();
    }

    function video_add() {
        /*         * ************终端列表******************** */
        $erweima = M("erweima");
        $data = $erweima->field('id,dianpname')->select();
        $this->assign('list', $data);
        /*         * ************终端列表******************** */
        $this->display();
    }

    function video_addget() {
        $mingp = M("video");
        $name['vi_name'] = $_POST['vi_name'];
        $name['vi_youxiao'] = $_POST['vi_youxiao'];
        $name['vi_url'] = $_POST['vi_url'];
        $name['vi_paixu'] = $_POST['vi_paixu'];
        $name['vi_ischange']=1; 
        $name['is_default'] = $_POST['is_default'];
        $ids = $_POST['chk_list'];
        //var_dump($ids);die;
        $ids = serialize($ids); //把传过来的id数组反序列化
        //$idsArr=  unserialize($ids);
        $name['vi_recid'] = $ids;
        $bol = $mingp->add($name);
        if ($bol)
            $this->success('添加成功', U('guanggao/video'));
        else
            $this->error('添加失败！');
    }

    function video_xg() {
        $mingp = M("video");
        $where['vi_id'] = $_GET['id'];
        $mingp_data = $mingp->where($where)->select();
        $ids = $mingp_data[0]['vi_recid'];

        $ids = unserialize($ids);
        $this->assign('ids', $ids);
        $this->assign('items_cate_list', $mingp_data);

        /*         * ************终端列表******************** */
        $erweima = M("erweima");
        $data = $erweima->field('id,dianpname')->select();

        foreach ($data as $key => $val) {
            if (in_array($val['id'], $ids)) {
                $data[$key]['type'] = 1;
            } else {
                $data[$key]['type'] = 0;
            }
        }
        $this->assign('list', $data);
        /*         * ************终端列表******************** */
//        var_dump($data);
        $this->display();
    }

    function video_xgget() {
        $mingp = D("video");
        $where['vi_id'] = $_GET['id'];
        $name['vi_youxiao'] = $_POST['vi_youxiao'];
        $name['vi_url'] = $_POST['vi_url'];
        $name['vi_name'] = $_POST['vi_name'];
        $name['vi_paixu'] = $_POST['vi_paixu'];
        $name['is_default'] = $_POST['is_default'];
        $name['vi_change']=1;
        $ids = $_POST['chk_list'];
        $ids = serialize($ids);
        $name['vi_recid'] = $ids;
        $bol = $mingp->where($where)->data($name)->save();
        if ( FALSE !== $bol){
            $this->success('修改成功', U('guanggao/video'));
        }
        else{
            $this->error('修改失败！');
        }
    }

    function video_del() {
        $mingp = M("video");
        $where['vi_id'] = $_GET['id'];
        $mingp = $mingp->where($where)->delete();

        if ($mingp)
            $this->success('删除成功', U('guanggao/video'));
        else
            $this->error('删除失败！');
    }

    /*
     * 添加培训广告
     */
    function train_video_addget() {

        $data['vi_name'] = $_POST['vi_name'];
        $data['vi_cate'] = $_POST['vi_cate'];
        $data['vi_youxiao'] = $_POST['vi_youxiao'];
        $data['vi_paixu'] = $_POST['vi_paixu'];


        if ($_FILES) {
            
                $upload_list = $this->uploads();
                $data['vi_url'] = 'http://m.hz41319.com/data/guanggao/' . $upload_list['0']['savename'];         
                $data['vi_bgimg'] = 'http://m.hz41319.com/data/guanggao/' . $upload_list['1']['savename'];
            
        }

       $train_video=M('train_video','sk_');
       $res=$train_video->data($data)->add();
       if($res){
            $this->success('添加成功', U('guanggao/train_video'));
       }else{
           $this->error('添加失败！');
       }
    }
    //培训视频编辑
    function train_video_xg(){
        $id=$_GET['id'];
        $result=M('train_video')->where(array('vi_id'=>$id))->find();
        $this->assign('vo',$result);
        $this->display();

    }

    //培训视频编辑保存处理
    function train_video_xgget(){
        $id=$_GET['id'];
        $data['vi_url']=$_POST['vi_url'];
        $data['vi_name']=$_POST['vi_name'];
        $data['vi_youxiao']=$_POST['vi_youxiao'];
        $data['vi_paixu']=$_POST['vi_paixu'];
        $result=M('train_video')->where(array('vi_id'=>$id))->save($data);
        if($result){
            $this->success('修改成功!');
        }else{
            $this->error('修改失败!');
        }

    }

    //培训视频删除
    function train_video_del(){
        $id=$_GET['id'];
        $result=M('train_video')->where(array('vi_id'=>$id))->delete();  
        if($result){
            $this->success('删除成功!');
        }else{
            $this->error('删除失败!');
        }      
    }
    
    function train_video(){
        $train_video=M('train_video');
        $data=$train_video->select();
        $this->assign('list',$data);
        $this->display();
    }

    function uploads(){
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 3145728000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','mp4','wmv','avi');// 设置附件上传类型
        $upload->savePath = ROOT_PATH.'/data/guanggao/';// 设置附件上传目录
        $upload->saveRule = uniqid;
        if(!$upload->upload()) {// 上传错误提示错误信息
        $this->error($upload->getErrorMsg());
        }else{// 上传成功 获取上传文件信息
        $uploadList =  $upload->getUploadFileInfo();
        }
       
        
        return $uploadList;

    }

    /**
     * webuploader 上传文件
     */
    public function ajax_upload(){
        // 根据自己的业务调整上传路径、允许的格式、文件大小
        ajax_upload('/data/video/');

    }
 
    /**
     * webuploader 上传demo
     */
    public function webuploader(){
        // 如果是post提交则显示上传的文件 否则显示上传页面
        if(!empty($_POST)){
            
            $data['vi_name'] = $_POST['vi_name'];
            $data['vi_cate'] = $_POST['vi_cate'];
            $data['vi_youxiao'] = $_POST['vi_youxiao'];
            $data['vi_paixu'] = $_POST['vi_paixu'];
            $data['vi_url'] = $_POST['video'];
            
            if ($_FILES) {
            
                $upload_list = $this->uploads();
                $data['vi_bgimg'] = 'http://m.hz41319.com/data/guanggao/' . $upload_list['0']['savename'];
            
            }
            $train_video=M('train_video','sk_');
            $res=$train_video->data($data)->add();
            if($res){
                $this->success('添加成功', U('guanggao/webuploader'));
            }else{
                $this->error('添加失败！');
            }
            
        }else{
            $this->display('info');
        }
    }

}

?>