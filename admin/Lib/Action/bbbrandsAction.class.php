<?php
/**
 * Created by PhpStorm.
 * User: zgf
 * Date: 2016/9/1
 * Time: 10:19
 */
class bbbrandsAction extends baseAction
{
    public function index()
    {

        $bbbrand = M('bbbrands','sk_')->select();
        $this->assign('bbbrands',$bbbrand);
        $this->display();
    }
    public function add()
    {
        $bbbrands = M('bbbrands','sk_');
        if ($_POST['brand']){
            $str = explode('--',$_POST['brand']);
            $data['id'] = $str['0'];
            $data['name'] = $str['1'];
            $data['sort'] = $_POST['sort'];

            $count = $bbbrands->where($data)->count();

            if ($count != 0) {
                $this->error('该品牌已存在');
                return;
            }
            if ($_FILES['img']['name'] != '') {
                $upload_list = $this->bb_upload('bbbrands');
                $data['img'] = $upload_list;
            } else {
                $this->error('请上传图片');
                return FALSE;
            }
            // 添加
            if ($bbbrands->add($data)) {
                $this->success("添加成功", U("index"));
            } else {
                $this->error('添加失败');
            }
        }

        //$brands = M('mingp','sk_')->field('id,name')->select();
        $brands = M('mingp','sk_')->query("SELECT name, CONCAT(id,'--',name) AS id_name FROM sk_mingp");
        $this->assign('brands',$brands);
        $this->display();
    }

    public function del()
    {
        if($_GET['id']){
            $res_del = M('bbbrands','sk_')->delete($_GET['id']);
            if($res_del){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败！');
            }
        }
    }

    public function bb_upload($savePath)
    {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 32922000;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg,xls');
        $upload->savePath = 'store/data/'.$savePath.'/';
        $upload->saveRule = uniqid;

        if (!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
        }
        $uploadList='/data/'.$savePath.'/'.$uploadList['0']['savename'];

        return $uploadList;
    }

}
