var allow_set={'login':/[^\w|\s|_]/g,'en':/[^\w|\s]/g,'number':/[^0-9]/g,'no_ch':/[\u4e00-\u9fa5|\s]/g,
			   'ch':/[^\u4e00-\u9fa5|\s]/g,'phone':/[^0-9|-]/g,'no_space':/\s/g,'en_no_space':/[^\w]/g,
			   'ch_no_space':/[^\u4e00-\u9fa5]/g,'float':/[^0-9|.]/g,'url':/\w|\//};
$(function(){
	set_input_allow();
});
function set_input_allow(){
	$('[data-allow]').each(function(k,tag){
		$(this).unbind('keyup',input_allow_func).bind('keyup',input_allow_func);
	})
}
var input_allow_func=function(){
	var self=$(this);
	var set=self.attr( 'data-allow' );
	var preg=allow_set[set];
	var val=$(this).val().replace(preg,'');
	$(this).val( val );	
}
