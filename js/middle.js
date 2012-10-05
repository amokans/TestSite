$(document).ready(function(){	
	function move_div(){
	window_width = $(window).width();
	window_height = $(window).height();
	
	obj_width = $('#login').width();
	obj_height = $('#login').height();
	
	obj2_width = $('#login:before').width();
	obj2_height = $('#login:before').height();
	

	
	$('#login').css('top', (window_height / 2) - (obj_height / 2)).css('left', (window_width / 2) - (obj_width / 2));
	
	}
	
	move_div();
	
	$(window).resize(function(){
	move_div();		
	});
});
