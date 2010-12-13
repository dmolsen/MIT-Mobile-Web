$(function(){  

	// simple function to force focus on title of the newly loaded page
	$('#jqt').bind('pageAnimationEnd', function(event, info){
		if (info.direction == "in") {
			$("div.current div.toolbar h1").focus();
		}
	});

});