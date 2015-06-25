/**
 * Tools 0.1 pre
 * 创建人：龙昊宏
 * 名称：
 * 功能：
 * 
 *	创建日期：2014.7.12		
 *	修改日期：2014.7.12
 * 
 *
 * Copyright Software 
 * 
 * 
 */
(function($){
	

	var setSize=function(){
		var height=$(window).height()-$('.sectionHeader-A1').outerHeight();
		$('.sectionWrap-A3').height(height);
		$('.sectionWrap-A2').height(height);
	};
	$(function(){
		setSize();
		$(window).resize(function() {
		  setSize();
		});
	});


	
})($);