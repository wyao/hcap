var $j = jQuery.noConflict()

jQuery(document).ready(function(){
    $j("#dubai").mouseover(function(){
	$j('.landmark img').css('opacity','0.3')
	$j('.img-info').css('display','none')
	$j("#dubai_i").css("display","inline")
	$j(this).css('opacity','1.0')
    });
    $j("#mumbai").mouseover(function(){
	$j('.landmark img').css('opacity','0.3')
	$j('.img-info').css('display','none')
	$j("#mumbai_i").css("display","inline")
	$j(this).css('opacity','1.0')
    });
    $j("#seoul").mouseover(function(){
	$j('.landmark img').css('opacity','0.3')
	$j('.img-info').css('display','none')
	$j("#seoul_i").css("display","inline")
	$j(this).css('opacity','1.0')
    });
    $j("#istanbul").mouseover(function(){
	$j('.landmark img').css('opacity','0.3')
	$j('.img-info').css('display','none')
	$j("#istanbul_i").css("display","inline")
	$j(this).css('opacity','1.0')
    });
    $j("#kong").mouseover(function(){
	$j('.landmark img').css('opacity','0.3')
	$j('.img-info').css('display','none')
	$j("#kong_i").css("display","inline")
	$j(this).css('opacity','1.0')
    });
    $j("#tokyo").mouseover(function(){
	$j('.landmark img').css('opacity','0.3')
	$j('.img-info').css('display','none')
	$j("#tokyo_i").css("display","inline")
	$j(this).css('opacity','1.0')
    });
	$j("#harvard").mouseover(function(){
	$j('.landmark img').css('opacity','0.3')
	$j('.img-info').css('display','none')
	$j("#harvard_i").css("display","inline")
	$j(this).css('opacity','1.0')
    });
});