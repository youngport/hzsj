<?php
class itemsModel extends RelationModel
{
    protected $_link = array(
        'items_cate' => array(
            'mapping_type'  => BELONGS_TO,
            'class_name'    => 'items_cate',
            'foreign_key'   => 'cid',
        ),
        'items_site' => array(
            'mapping_type'  => BELONGS_TO,
            'class_name'    => 'items_site',
            'foreign_key'   => 'sid',
        ),

    );
}