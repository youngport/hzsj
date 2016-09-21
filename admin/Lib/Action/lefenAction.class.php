<?php

class lefenAction extends baseAction {

    // 菜单页面
    public function index() {

        if (isset($_GET['status']) && $_GET['status'] != -1) {
            $tj['shenhe'] = $_GET['status'];
        }
        if (isset($_GET['fenlei_sel']) && $_GET['fenlei_sel'] != -1) {
            $tj['fenlei'] = $_GET['fenlei_sel'];
        }
        $keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($keyword) {
            $tj['_string'] = "biaoti like '%" . $keyword . "%'";
        }
//        $tj['shunxu'] = 0;
//        $tj['_logic'] = 'or';
//        $tj['open_id']='';
//        $tj['_string'] = "shunxu=0 ";
        //dump($_GET['openid']);die;
        $lfx = M('lfx');
        //SELECT sk_lfx.*,(select wei_nickname from sk_member where open_id=openid) wei_nickname FROM `sk_lfx` left join sk_lfx_img on lfxid=id where shunxu=0 ORDER BY shijian desc LIMIT 0,20
        $count = $lfx->field(" *,(select wei_nickname from sk_member where open_id=openid) as wei_nickname")->where($tj)->order('shijian desc')->join('left join sk_lfx_img on sk_lfx_img.lfxid=sk_lfx.id')->count(); // 查询满足要求的总记录数
        if ($_GET['p'])
            $lfx_list = $lfx->field("*,(select wei_nickname from sk_member where open_id=openid) as wei_nickname")->where($tj)->order('shijian desc')->join(' left join sk_lfx_img on sk_lfx_img.lfxid=sk_lfx.id')->group("id")->page($_GET['p'] . ',20')->select();
        else
        //$dianp = $dianp->where($tj)->order('shijian desc')->join('left join sk_member on sk_member.open_id=sk_lfx.openid')->join('sk_lfx_img on sk_lfx_img.lfxid=sk_lfx.id')->limit('0,20')->select();
        //$dianp=$dianp->field("*,(select wei_nickname from sk_member where open_id=openid) wei_nickname")->where($tj)->order('shijian desc')->join(' left join sk_lfx_img on sk_lfx_img.lfxid=sk_lfx.id')->limit('0,20')->buildsql();
        $lfx_list = $lfx->field("*,(select wei_nickname from sk_member where open_id=openid)as wei_nickname")->where($tj)->order('shijian desc')->join(' left join sk_lfx_img on sk_lfx_img.lfxid=sk_lfx.id')->group("id")->limit('0,20')->select();
        import("ORG.Util.Page"); // 导入分页类
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $this->assign('page', $show); // 赋值分页输出
        if ($tj['shenhe'] == "")
            $this->assign('status', -1);
        else
            $this->assign('status', $tj['shenhe']);
        if ($tj['fenlei'] == "")
            $this->assign('fenlei_sel', -1);
        else
            $this->assign('fenlei_sel', $tj['fenlei']);
        $this->assign('keyword', $keyword);
        $this->assign('items_cate_list', $lfx_list);
        $this->display();
    }

    //添加乐分享
    public function add() {
        if ($_POST) {
            $lfx = M('lfx');
            $lfx_img = M('lfx_img');
            $data = array();
            $data['neirong'] = $_POST["contents"];
            $data['biaoti'] = $_POST['title'];
            $data['shijian'] = time();
            $data['fenlei'] = $_POST['fenlei'];
            $data['shenhe'] = $_POST['status'];
            $data['openid'] = 0;
            $data['zhiding'] = $_POST['zhiding'];
//            $data['orders']=$_POST["orders"];
            $data['show_share'] = addslashes($_POST['showContent']);
            $data['zhiding_time'] = strtotime($_POST["zhiding_time"]);
            if ($data['biaoti'] == '') {
                $this->error('标题不能为空');
            }
            $result = $lfx->add($data);
            //echo $lfx->getLastSql(); die;
            $id = $lfx->getLastInsID();
            if (FALSE == $result) {
                $this->error('添加失败', U('lefen/add'));
            } else {
                if ($_FILES['img1']['name'] == '' && $_FILES['img2']['name'] == '' && $_FILES['img3']['name'] == '') {
                    $this->error('您没有上传图片');
                } else {
                    if ($_FILES['img1']['name'] != '') {
                        $upload_list = $this->_upload($_FILES['img1']);
                        //裁剪----------
                        //$this->caijian($upload_list['0']['savename']);
                        //$s_img = $this->s_imgurl;
                        //---------------结束
                        $arr = array();
                        $arr['imgurl'] = 'data/lefenxiang/m_' . $upload_list['0']['savename']; //原图
                        $arr['s_imgurl'] = 'data/lefenxiang/s_' . $upload_list['0']['savename']; //小图
                        $arr['g_imgurl'] = 'data/lefenxiang/g_' . $upload_list['0']['savename']; //管理员横图
                        $arr['lfxid'] = $id;
                        $arr['shunxu'] = 0;
                        //$lfx_img->bimg = $s_img; //裁剪图
                        if (FALSE == ($lfx_img->add($arr))) {
                            unset($arr);
                            $this->error('图片上传失败', U('lefen/index'));
                        }
                    }
                    if ($_FILES['img2']['name'] != '') {
                        $upload_list = $this->_upload($_FILES['img2']);
                        //裁剪----------
                        //$this->caijian($upload_list['0']['savename']);
                        //$s_img = $this->s_imgurl;
                        //---------------结束
                        $arr = array();
                        $arr['imgurl'] = 'data/lefenxiang/m_' . $upload_list['0']['savename']; //原图
                        $arr['s_imgurl'] = 'data/lefenxiang/s_' . $upload_list['0']['savename']; //小图
                        $arr['g_imgurl'] = 'data/lefenxiang/g_' . $upload_list['0']['savename']; //管理员横图
                        $arr['lfxid'] = $id;
                        $arr['shunxu'] = 1;
                        //$lfx_img->bimg = $s_img; //裁剪图
                        if (FALSE == ($lfx_img->add($arr))) {
                            unset($arr);
                            $this->error('图片上传失败', U('lefen/index'));
                        }
                    }
                    if ($_FILES['img3']['name'] != '') {
                        $upload_list = $this->_upload($_FILES['img3']);
                        //裁剪----------
                        $this->caijian($upload_list['0']['savename']);
                        //$s_img = $this->s_imgurl;
                        //---------------结束
                        $arr = array();
                        $arr['imgurl'] = 'data/lefenxiang/m_' . $upload_list['0']['savename']; //原图
                        $arr['s_imgurl'] = 'data/lefenxiang/s_' . $upload_list['0']['savename']; //小图
                        $arr['g_imgurl'] = 'data/lefenxiang/g_' . $upload_list['0']['savename']; //管理员横图
                        $arr['lfxid'] = $id;
                        $arr['shunxu'] = 2;
                        //$lfx_img->bimg = $s_img; //裁剪图
                        if (FALSE == ($lfx_img->add($arr))) {
                            unset($arr);
                            $this->error('图片上传失败', U('lefen/index'));
                        }
                    }
                }
                $this->success('添加成功', U('lefen/add'));
            }
        }

        $this->display();
    }

    //修改乐分享
    public function edit() {
        if (empty($_POST)) {
            $id = $_GET['id'];
            $lfx = M('lfx');
            $data = $lfx->where("id ='$id'")->find();
            $this->assign('data', $data);
        } else {
            $lfx = M('lfx');
            $data = array();
            $id = $_POST["id"];
            $data['neirong'] = $_POST["contents"];
            $data['biaoti'] = $_POST['title'];
//            $data['shijian'] = time();
            $data['fenlei'] = $_POST['fenlei'];
            $data['shenhe'] = $_POST['status'];
            $data['zhiding'] = $_POST['zhiding'];
            //$data['orders']=$_POST["orders"];
            $data['show_share'] = addslashes($_POST['showContent']);
            $data['zhiding_time'] = strtotime($_POST["zhiding_time"]);
            if ($data['biaoti'] == '') {
                $this->error('标题不能为空');
            }
            $result = $lfx->where("id='$id'")->save($data);
            if (FALSE == $result) {
                $this->error('修改失败', U('lefen/edit', array('id' => $id)));
            } else {
                if ($_FILES['img1']['name'] == '' && $_FILES['img2']['name'] == '' && $_FILES['img3']['name'] == '') {
                    $this->error('您没有上传图片');
                } else {
                    //先删除原来的图片
                    $lfx_img = M('lfx_img');
                    $lfx_img->where("lfxid = '$id'")->delete();

                    if ($_FILES['img1']['name'] != '') {
                        $upload_list = $this->_upload($_FILES['img1']);
                        //裁剪----------
                        //$this->caijian($upload_list['0']['savename']);
                        //$s_img = $this->s_imgurl;
                        //---------------结束
                        $arr = array();
                        $arr['imgurl'] = 'data/lefenxiang/m_' . $upload_list['0']['savename']; //原图
                        $arr['s_imgurl'] = 'data/lefenxiang/s_' . $upload_list['0']['savename']; //小图
                        $arr['g_imgurl'] = 'data/lefenxiang/g_' . $upload_list['0']['savename']; //管理员横图
                        $arr['lfxid'] = $id;
                        $arr['shunxu'] = 0;
                        //$lfx_img->bimg = $s_img; //裁剪图
                        $result = $lfx_img->add($arr);
                        unset($arr);
                        if (FALSE == $result) {
                            $this->error('图片上传失败', U('lefen/index'));
                        }
                    }
                    if ($_FILES['img2']['name'] != '') {
                        $upload_list = $this->_upload($_FILES['img2']);
                        //裁剪----------
                        //$this->caijian($upload_list['0']['savename']);
                        //$s_img = $this->s_imgurl;
                        //---------------结束
                        $arr = array();
                        $arr['imgurl'] = 'data/lefenxiang/m_' . $upload_list['0']['savename']; //原图
                        $arr['s_imgurl'] = 'data/lefenxiang/s_' . $upload_list['0']['savename']; //小图
                        $arr['g_imgurl'] = 'data/lefenxiang/g_' . $upload_list['0']['savename']; //管理员横图
                        $arr['lfxid'] = $id;
                        $arr['shunxu'] = 1;
                        //$lfx_img->bimg = $s_img; //裁剪图
                        if (FALSE == ($lfx_img->add($arr))) {
                            unset($arr);
                            $this->error('图片上传失败', U('lefen/index'));
                        }
                    }
                    if ($_FILES['img3']['name'] != '') {
                        $upload_list = $this->_upload($_FILES['img3']);
                        //裁剪----------
                        $this->caijian($upload_list['0']['savename']);
                        //$s_img = $this->s_imgurl;
                        //---------------结束
                        $arr = array();
                        $arr['imgurl'] = 'data/lefenxiang/m_' . $upload_list['0']['savename']; //原图
                        $arr['s_imgurl'] = 'data/lefenxiang/s_' . $upload_list['0']['savename']; //小图
                        $arr['g_imgurl'] = 'data/lefenxiang/g_' . $upload_list['0']['savename']; //管理员横图
                        $arr['lfxid'] = $id;
                        $arr['shunxu'] = 3;
                        //$lfx_img->bimg = $s_img; //裁剪图
                        if (FALSE == ($lfx_img->add($arr))) {
                            unset($arr);
                            $this->error('图片上传失败', U('lefen/index'));
                        }
                    }
                }
                $this->success('修改成功', U('lefen/index'));
            }
        }
        $this->display();
    }

    // 删除管理员的乐分享
    public function del() {
        if (!empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $lfx = M('lfx');
            $result = $lfx->where("id = '$id'")->delete();
            if (FALSE == $result) {
                $this->error('删除失败', U('lefen/index'));
            } else {
                //先删除原来的图片
                $lfx_img = M('lfx_img');
                $lfx_img = M('lfx_img');
                $lfx_img->where("lfxid = '$id'")->delete();
                $this->error('删除成功', U('lefen/index'));
            }
        } else {
            $this->error('该条记录不存在', U('lefen/index'));
        }
    }

    public function deletes() {

        if (!isset($_POST['lfxid']) || empty($_POST['lfxid'])) {
            $this->error('请选择要删除的数据！');
        }
        $lfx = D('lfx');
        $ids = $_POST['lfxid'];
        if (is_array($ids)) {
            foreach ($ids as $val) {
                $lfx->delete($val);
            }
            $this->success("删除成功");
        }
    }

    function yige_denxiang() {
        $id = isset($_GET['id']) && trim($_GET['id']) ? trim($_GET['id']) : $this->error('请选择要查看的详情链接');
        $whe['id'] = $id;
        $whe['shunxu'] = 0;
        $lfx = M('lfx');
        $lfx = $lfx->where($whe)->join('sk_member on sk_member.open_id=sk_lfx.openid')->join('sk_lfx_img on sk_lfx_img.lfxid=sk_lfx.id')->select();
        $this->assign('lfx', $lfx);

        $lfx = M('lfx_img');
        $lfximg['lfxid'] = $id;
        $lfx = $lfx->where($lfximg)->order('shunxu asc')->select();
        $this->assign('lfxt', $lfx);
        $this->display();
    }

    function shenhe() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        }
        if (isset($_POST['dianpu_shenhe'])) {
            $shenhe = $_POST['dianpu_shenhe'];
        }
        $whe['id'] = $id;
        $she['shenhe'] = $shenhe;
        $lfx = M('lfx');
        $sel_l = $lfx->where($whe)->select();
        if ($shenhe == 1 && $sel_l[0]['jiajifen'] == 0) {
            $she['jiajifen'] = 1;
            $jifen = M("jifen");

            $dangtian = strtotime(date('Y-m-d', time())); //当天 0点时间戳
            $xiatian = $dangtian + 86400; //明天 0点时间戳
            $cou_data['_string'] = "shijian >= '$dangtian' and shijian < '$xiatian' and laiyuan = 0";
            $cou = $jifen->where($cou_data)->count();
            if ($cou < 10) {
                $data['jifen'] = 1;
                $data['openid'] = $sel_l[0]['openid'];
                $data['laiyuan'] = 0;
                $data['shijian'] = time();
                $jifen->add($data);

                $user_jifen = M("member");
                $medata['open_id'] = $sel_l[0]['openid'];
                $user_jifen->where($medata)->setInc('jifen', 1);
            }
        }
        $lfx = $lfx->where($whe)->save($she);
        $this->ajaxReturn($lfx);
    }

    function count() {
        if (!isset($_GET['id']))
            $this->error("非法ID");
        if (isset($_GET['wei_name']) && !empty($_GET['wei_name']))
            $where['wei_nickname'] = array('like', '%' . $this->_get('wei_name') . '%');
        if (isset($_GET['openid']) && !empty($_GET['openid']))
            $where['open_id'] = $_GET['openid'];

        $where['lfx_id'] = $this->_get('id');
        $user = M('lfx_count')->join("sk_member on sk_lfx_count.openid=sk_member.open_id")->where($where)->field("lfx_id,open_id,wei_nickname,sk_lfx_count.count")->select();
        $count = M('lfx_count')->where("lfx_id=$id")->count();
        import("ORG.Util.Page"); // 导入分页类
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('list', $user);
        $this->display();
    }

    public function _upload($imgage, $path = '', $isThumb = true) {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');

        if (empty($savePath)) {
            $upload->savePath = './data/lefenxiang/';
        } else {
            $upload->savePath = $path;
        }

        if ($isThumb === true) {
            $upload->thumb = true;
            $upload->imageClassPath = 'ORG.Util.Image';
            //设置前缀，逗号隔开
            $upload->thumbPrefix = 'm_,s_,g_';
            //设置缩略图最大高度,多规格用，隔开
            $upload->thumbMaxWidth = '10240,227,288';
            $upload->thumbMaxHeight = '10240,234,124';
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

    //----------增加一个裁剪图片-----------//
    function caijian($src_path) {

        //先设置缩放规格
        $dst_w = 300;
        $dst_h = 300;
        //创建原图的实例
        $src_path = 'data/goods/m_' . $src_path;
        $dst_w = 300;
        $dst_h = 300;
        //var_dump($src_path);exit;
        //创建原图的实例
        $src = imagecreatefromjpeg($src_path);
        list($src_w, $src_h) = getimagesize($src_path); // 获取原图尺寸
        $dst_scale = $dst_h / $dst_w; //目标图像长宽比 
        $src_scale = $src_h / $src_w; // 原图长宽比 
        if ($src_scale >= $dst_scale) {
            // 过高 
            $w = intval($src_w);
            $h = intval($dst_scale * $w);
            $x = 0;
            $y = ($src_h - $h) / 3;
        } else {
            // 过宽 
            $h = intval($src_h);
            $w = intval($h / $dst_scale);
            $x = ($src_w - $w) / 2;
            $y = 0;
        }
        // 先缩放比例 
        $scale = $dst_w / $w;
        $target = imagecreatetruecolor($dst_w, $dst_h);
        $final_w = intval($w * $scale);
        $final_h = intval($h * $scale);
        imagecopyresampled($target, $src, 0, 0, 0, 0, $final_w, $final_h, $w, $h);
        // 再剪裁白边，突出商品 
        $source = imagecreatefromjpeg($src_path);
        $croped = imagecreatetruecolor(227, 234);
        imagecopy($croped, $target, 0, 0, 35, 30, 227, 234);

        $s_imgurl = str_replace("m_", "b_", $src_path);
        imagejpeg($croped, $s_imgurl);
        return $s_imgurl;
    }

    //----------结束-----------//
}

?>