<?php
namespace Common\Model;
use Common\Model\CommonModel;
class MessageModel extends CommonModel{
	protected $_auto=array(
		array('add_time','time',1,'function'),
		array ('update_time','time',2,'function')
	);
	protected $_validate=array(
		array('title','require',"标题不能为空或已存在",1,"unique"),
		array('excerpt','require',"摘要不能为空",1),
		array('content','require',"内容不能为空",1)
	);
}