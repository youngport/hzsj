<tagLib name="wego" />
<include file="public:header" />
<script type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/jquery.imagePreview.js"></script>
<div class="pad-10" >
    <form name="searchform" action="" method="get" >
    <table width="100%" cellspacing="0" class="search-form">
        <tbody>
            <tr>
            <td>
            <div class="explain-col">
            	上架时间：
                <input type="text" name="time_start" id="time_start"  value="{$time_start}" width="130px"
                       onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',startDate:'1980-05-01',alwaysUseStartDate:true})"
                        placeholder="选择上架起始时间">

                -  <input type="text" name="time_end" id="time_end"  value="{$time_end}" width="130px"
                          onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',startDate:'1980-05-01',alwaysUseStartDate:true})"
                           placeholder="选择上架截止时间">
            	&nbsp;商品分类：
                <select name="cate_id">
            	<option value="0">--请选择分类--</option>
				<volist name="cate_list" id="val">
                <option value="{$val.id}" level="{$val.level}" <if condition="$cate_id eq $val['id']"> selected="selected" </if>>
                    {:str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$val['level'])}
                    {:trim($val['name'])}
                </option>
            	</volist>                
              	</select>
				 &nbsp;
                <select name="status">
            	<option value="-1">-请选择-</option>
                <option value="1" <if condition="$status eq 1">selected="selected"</if>>已上架</option>
                <option value="0" <if condition="$status eq 0">selected="selected"</if>>已下架</option>
                </select>
                &nbsp;关键字 :
                <input name="keyword" type="text" class="input-text" size="25" value="{$keyword}" />
                <input type="hidden" name="m" value="goods" />
                <input type="submit" name="search" class="button" value="搜索" />
        	</div>
            </td>
            </tr>
        </tbody>
    </table>
    </form>
    <form id="myform" name="myform" action="{:u('goods/delete')}" method="post" onsubmit="return check();">
    <div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="30">ID</th>
                <th width=15><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>                
                <th width="40">&nbsp;</th>
                <th  width="60">商品编号</th>
                <th  width="60">产品规格</th>
                <th    align="left"> 商品名称 </th>
                <th width=60>库存</th>
                <th width=60>所属分类</th>
                <th  width="60">单价(元)</th>
                <th width=150><a href="{:U('goods/index',array('time_start'=>$time_start,'time_end'=>$time_end,'cate_id'=>$cate_id,'keyword'=>$keyword,'order'=>'add_time','sort'=>$sort))}" class="blue <if condition="$order eq 'add_time'">order_sort_<if condition="$sort eq 'desc'">1<else/>0</if></if>">上架时间</a></th>
				<th width=40><a href="{:U('goods/index',array('time_start'=>$time_start,'time_end'=>$time_end,'cate_id'=>$cate_id,'keyword'=>$keyword,'order'=>'hits','sort'=>$sort))}" class="blue <if condition="$order eq 'hits'">order_sort_<if condition="$sort eq 'desc'">1<else/>0</if></if>">人气</a></th>
                <th width="30">排序</th>			
                <th width=60>上架状态</th>
                <th width=30>操作</th>
            </tr>
        </thead>
    	<tbody>
        <volist name="items_list" id="val" >
        <tr>
        	<td align="center">{$val.id}</td>
            <td align="center"><input type="checkbox" value="{$val.id}" name="id[]"></td>           
            <td align="right"><img src="{$val.simg}" width="35" width="35" class="preview" bimg="{$val.img}"></td>
            <td align="center">{$val.pcode}</td>
            <td align="center">{$val.guige}</td>
            <td align="left"><a href="{:U('goods/edit', array('id'=>$val['id']))}">{$val.good_name}</a></td>
            <td align="center">{$val.items_cate.kucun}</td>
            <td align="center">{$val.items_cate.name}</td>
            <td align="center"><em style="color:green;">{$val.price}</em></td>
            <td align="center">{$val.add_time|date="Y-m-d H:i:s",###}</td>
            <td align="center"><em style="color:green;">{$val.hits}</em></td>
            <td>
            <input type="text" class="input-text-c input-text" value="{$val.sort_order}" size="4" name="listorders[{$val.id}]" id="sort_{$val.id}" onblur="sort({$val.id},'sort_order',this.value)" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')">
            </td>
             <td align="center" onclick="status({$val.id},'status')" id="status_{$val.id}"><img src="__ROOT__/statics/images/status_{$val.status}.gif" /></td>
            <td align="center"><a class="blue" href="{:U('goods/edit',array('id'=>$val['id']))}">编辑</a></td>
        </volist>
    	</tbody>
    </table>

    <div class="btn">
    	<label for="check_box" style="float:left;">全选/取消</label>
    	<input type="submit" class="button" name="dosubmit" value="{$Think.lang.delete}" onclick="return confirm('{$Think.lang.sure_delete}')" style="float:left;margin:0 10px 0 10px;"/>
        
    	<div id="pages">{$page}</div>
    </div>

    </div>
    </form>
</div>
<script language="javascript">
$(function(){
	$(".preview").preview();
});

var lang_cate_name = "商品名称";
function check(){
	if($("#myform").attr('action') == '{:u("goods/delete")}') {
		var ids='';
		$("input[name='id[]']:checked").each(function(i, n){
			ids += $(n).val() + ',';
		});

		if(ids=='') {
			window.top.art.dialog({content:lang_please_select+lang_cate_name,lock:true,width:'200',height:'50',time:1.5},function(){});
			return false;
		}
	}
	return true;
}
function status(id,type){
    $.get("{:u('goods/status')}", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif')
	});
}
//排序方法
function sort(id,type,num){
    
    $.get("{:u('goods/sort')}", { id: id, type: type,num:num }, function(jsondata){
        
		$("#"+type+"_"+id+" ").attr('value', jsondata.data);
	},'json'); 
}
</script>
</body>
</html>
