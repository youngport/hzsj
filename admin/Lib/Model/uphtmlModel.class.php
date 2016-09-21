<?php
class uphtmlModel extends Model{
	function uphtml_f($content){
	    if (is_array($content))	{
	        foreach ($content as $key=>$value){
	            $content[$key] = addslashes($value);
	        }
	    }else{
	        $content=addslashes($content);
	    }
	    return htmlspecialchars_decode($content);
	}
}