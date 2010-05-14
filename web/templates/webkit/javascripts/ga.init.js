$(function(){  
	$('#jqt').ajaxComplete(function(){
		add_ga(); // add google analytics on each page load
	});
	
	$(document).ready(function() {
	  	add_ga(); // add google analytics to the first page
	});
	
	function add_ga() {
		$('a').each(function(){
			if (!$(this).hasClass('ga')) {
				if ($(this).attr('data-ga')) {
					//$(this).click(function(){console.log('had data ga click 2');});
					$(this).click(function(){pageTracker._trackPageview($(this).attr('data-ga'))});
					$(this).addClass('ga');
				} else {
					if ($(this).attr('href') == '#') {
						//$(this).click(function(){console.log('back button 2');});
						$(this).click(function(){pageTracker._trackPageview('/'+$(this).attr('href')/+'/')});
						$(this).addClass('ga');		
					} else {	
						//$(this).click(function(){console.log('internal link 2');});
						$(this).click(function(){pageTracker._trackPageview($(this).attr('href'))});
						$(this).addClass('ga');
					}
				}
			}
		});
	}
});