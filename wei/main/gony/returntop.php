<div class="dingbu" id="dingbu"><img src="<?php echo $cxtPath ?>/wei/img/runt_top.png" /></div>
<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/backtop.css" />
<script type="text/javascript">
$(function(){
	$('#dingbu').click(function(){
		$("html, body").animate({
			scrollTop: 0
		}, 120);
	});
	$(window).bind("scroll",function() {
        var d = $(document).scrollTop(),
        e = $(window).height();
        0 < d ? $('#dingbu').css("bottom", "50px") : $('#dingbu').css("bottom", "-190px");
	});
});
</script>