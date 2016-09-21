<?php

namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class GoodsController extends AdminbaseController {

    protected $goods_model;

    function _initialize() {
        parent::_initialize();
        $this->goods_model = M("goods", "sk_");
        $this->sid = I("sid", 0, intval);
    }

    function index() {
        if (isset($_POST['id']) && is_numeric($_POST['id']))
            $formget['g.id'] = I("id", 0, "intval");
        if (isset($_POST['good_name']) && $_POST['good_name'] != '')
            $formget['good_name'] = array("like", "%" . I("good_name") . "%");
        $formget['g.status'] = 1;
        $count = M("goods", "sk_")->alias("g")->join("sk_items_cate c on c.id=cate_id")->where($formget)->count();
        $page = $this->page($count, 20);
        $goodlist = M("goods", "sk_")->alias("g")->field("g.id,good_name,c.name cate_name,simg,guige,price,kucun,hits")->join("sk_items_cate c on c.id=cate_id")->where($formget)->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign("list", $goodlist);
        $this->assign("sid", $this->sid);
        $this->assign("formget", I("post."));
        $this->assign("Page", $page->show("Admin"));
        $this->assign("count", $count);
        $this->display();
    }

    function goodlabel() {
        Vendor("phpqrcode.phpqrcode");
        $id = I("id");
        $open_id = M("erweima", "sk_")->getFieldById($this->sid, "openid");
        $good_name = M("goods", "sk_")->getFieldById($id, "good_name");
        if (empty($good_name)) {
            $this->ajaxReturn(array('status' => 2, 'message' => '不存在'));
        }
        $link = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=$open_id|shangp|$id&connect_redirect=1#wechat_redirect";
        $erweima = \QRcode::png($link, false, "L", 6, 3); //修改了phpqrcode的核心代码使其不输出,返回资源类型
        $font = './statics/ttf/simhei.ttf';
        $fontsize = 30;
        $dst = imagecreatefromjpeg('./statics/images/erweima.jpg');  //创建一块画布
        $dstwidth = imagesx($dst);  // 画布的长度 （x轴坐标）
        $black = imagecolorallocate($dst, 255, 255, 255);  //将背景色设置为白色
        $len = utf8_strlen($good_name); //计算中文字符的长度
        $a = 16;
        $b = 270;
        for ($i = 0; $i <= $len;) {
            $box = imagettfbbox($fontsize, 0, $font, mb_substr($good_name, $i, $a, 'utf8'));
            $box_width = max(abs($box[2] - $box[0]), abs($box[4] - $box[6]));
            $x = ceil(($dstwidth - $box_width) / 2);
            $tempstr = mb_substr($good_name, $i, $a, 'utf8');
            imagettftext($dst, $fontsize, 0, $x, $b, $black, $font, $tempstr);
            if (utf8_strlen($tempstr) == $a) {
                $i += $a;
                $b += 50;
            } else {
                break;
            }
        }
        imagecopyresized($dst, $erweima, 125, 485, 0, 0, 390, 390, 366, 366);
        header("content-type:image/png");
        imagejpeg($dst);
        imagedestroy($dst);
        imagedestroy($erweima);
    }
    
    /**
     * @author weiyh 
     * @function 批量创建台签
     */
    function createlable() {
        $ids = I("ids");
        $time = time();
        Vendor("phpqrcode.phpqrcode");
        $open_id = M("erweima", "sk_")->getFieldById($this->sid, "openid");
        $tagimg = M("tagimg", "sk_");
        foreach ($ids as $k => $v) {
            $id = $v;
            $good_name = M("goods", "sk_")->getFieldById($id, "good_name");
            if (empty($good_name)) {
                $this->ajaxReturn(array('status' => 2, 'message' => '不存在'));
            }
            $link = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=$open_id|shangp|$id&connect_redirect=1#wechat_redirect";
            $erweima = \QRcode::png($link, false, "L", 6, 3); //修改了phpqrcode的核心代码使其不输出,返回资源类型
            $font = './statics/ttf/simhei.ttf';
            $fontsize = 30;
            $dst = imagecreatefromjpeg('./statics/images/erweima.jpg');  //创建一块画布
            $dstwidth = imagesx($dst);  // 画布的长度 （x轴坐标）
            $black = imagecolorallocate($dst, 255, 255, 255);  //将背景色设置为白色
            $len = utf8_strlen($good_name); //计算中文字符的长度
            $a = 16;
            $b = 270;
            for ($i = 0; $i <= $len;) {
                $box = imagettfbbox($fontsize, 0, $font, mb_substr($good_name, $i, $a, 'utf8'));
                $box_width = max(abs($box[2] - $box[0]), abs($box[4] - $box[6]));
                $x = ceil(($dstwidth - $box_width) / 2);
                $tempstr = mb_substr($good_name, $i, $a, 'utf8');
                imagettftext($dst, $fontsize, 0, $x, $b, $black, $font, $tempstr);
                if (utf8_strlen($tempstr) == $a) {
                    $i += $a;
                    $b += 50;
                } else {
                    break;
                }
            }
            imagecopyresized($dst, $erweima, 125, 485, 0, 0, 390, 390, 366, 366);
            //header("content-type:image/png");
            $this->mkFolder("./statics/images/tagImg/");
            $file_name = "./statics/images/tagImg/" . $id . ".jpg";
            imagejpeg($dst, $file_name);

            //把文件名存进数据库
            $data = array();
            $data['openid'] = $open_id;
            $data['tagid'] = $id;
            $data['imgpath'] = $file_name;
            $data['addtime'] = $time;
            $tagimg->add($data);
            imagedestroy($dst);
            imagedestroy($erweima);
        }
        //查询的时间范围
        $start = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time));
        $end = mktime(23, 59, 59, date("m", $time), date("d", $time), date("Y", $time));

        //显示台签
        $map['tagid'] = array('in', $ids);
        $map['openid'] = $open_id;
        $map['addtime'] = array('between', array($start, $end));
        $list = $tagimg->distinct(true)->field("imgpath")->where($map)->select();
        $this->assign("list", $list);
        $this->display();
    }

    /**      
     * 
     * 判断文件是否存在，不存在则创建
     */

    function mkFolder($path) {
        if (!is_readable($path)) {
            is_file($path) or mkdir($path, 0700);
        }
    }

}
