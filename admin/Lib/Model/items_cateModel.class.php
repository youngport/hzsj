<?php
class items_cateModel extends RelationModel
{

	function get_list($id=0){
		$items_cate_mod=D('items_cate');
		
		$list=array();
		$res=$items_cate_mod->where('pid='.$id)->select();
		foreach($res as $key=>$val){
			$val['level']=0;
			$list[]=$val;
			//二级分类
			$arr=$items_cate_mod
			->where('pid='.$val['id'])
			->select();
			//三级分类
			foreach($arr as $k2=>$v2){
				$v2['level']=1;
				$v2['cls']="sub_".$val['id'];
				$list[]=$v2;

				$res3=$arr[$k2]['items']=$items_cate_mod->where('pid='.$v2['id'])
				->select();
				foreach($res3 as $k3=>$v3){
					$v3['level']=2;
					$v3['cls']="sub_".$val['id']." sub_".$v2['id'];
					$list[]=$v3;
				}
			}
			$res[$key]['items']=$arr;
		}
		return array('list'=>$res,'sort_list'=>$list);
	}
    //删除商品后更新总数
    function upCateNum($array){
        $itemsId['id'] = array("in",implode(',',$array));
        $itemsCate = M('items')->field('cid')->where($itemsId)->select();
        $items_cate_mod = D('items_cate');
        foreach($itemsCate as $k){
            $map['id'] = $k['cid'];
            $items_cate_mod->where($map)->setDec('item_nums','1');
        }
    }
 //只获取俩及数据
    function get_top2_list(){
    	$items_cate_mod = D('items_cate');
        $lists = $items_cate_mod->field('id,name,pid')->order('ordid ASC')->select();       
        $items_cate_list = array();
        foreach( $lists as $val ){
         if ($val['pid']==0) {
                $items_cate_list['parent'][$val['id']] = $val;
         }else {
                $items_cate_list['sub'][$val['pid']][] = $val;
         }
       }
       return $items_cate_list;
    }



}