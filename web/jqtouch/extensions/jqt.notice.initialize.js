$(function(){  
	$('div#jqt .notice').hideNotice();
	$('.shownotice').click(function(){
		$('div#jqt .notice').makeNotice();
	});
	$('.hidenotice').click(function(){
	$('div#jqt .notice').hideNotice();
		$(this).removeClass('active');
		return false;
	});
});