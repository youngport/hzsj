<?php
class ErweimaModel extends RelationModel
{
	protected $_validate=array(
        array('mac','','该透明柜MAC地址已存在',2,'unique'),
        array('rmac','','该路由器MAC地址已存在',2,'unique')
    );
}