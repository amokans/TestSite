$(':text').focusin(function(){
	$(this).css('background-color', #666);
	
});
$(':text').blur(function(){
	$(this).css('background-color', #FFF);
	
});
$(':submit').click(function(){
	$(this).attr('value', 'Wait..');
	$(this).attr('disabled', true);
});