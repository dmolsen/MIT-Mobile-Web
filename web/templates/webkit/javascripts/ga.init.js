$(function(){  
	
	$('#jqt').ajaxComplete(function(){
		add_ga(); // add google analytics on each page load
	});
	
	$(document).ready(function() {
	  	add_ga(); // add google analytics to the first page
	});
	
	// record the event
	function record_ga_event(category, action, label) {
		try {
			_gaq.push(['_trackEvent', category, action, label]);	  
		}catch(err){}
	}
	
	// record the pageview
	function record_ga_pageview(link) {
		try {
			_gaq.push(['_trackPageview', link]);	  
		}catch(err){}
	}
	
	// add the appropriate google analytics code to each link
	function add_ga() {
		$('a').each(function(){
			if (!$(this).hasClass('ga')) {
				var href = $(this).attr('href');
				var exp_http  = /http(s|):\/\/(.[^\/]+)/;
				var exp_tel   = /tel:(.[^\/]+)/;
				var exp_email = /mailto:(.[^\/]+)/;
				if (matches = exp_http.exec(href)) {	
					$(this).click(function(){
						var exp_http2 = /http(s|):\/\/(.[^\/]+)/; // have to duplicate for the click action
						var lmatches = exp_http2.exec($(this).attr('href'));
						var domain = lmatches[2];
						record_ga_event("Outbound Traffic", domain, $(this).attr('href'));
					});
					$(this).addClass('ga');
				} else if (matches = exp_tel.exec(href)) {
					$(this).click(function(){
						var number = $(this).attr('href').replace('tel:','');
						var link = hist[0];
						if (link.backHref == '') {
							linkHref = '/home/'
						} else {
							linkHref = link.backHref;
						}
						record_ga_event("Outbound Calls", number, linkHref);
					});
					$(this).addClass('ga');
				} else if (matches = exp_email.exec(href)) {
					$(this).click(function(){
						var email = $(this).attr('href').replace('mailto:','');
						var link = hist[0];
						if (link.backHref == '') {
							linkHref = '/home/'
						} else {
							linkHref = link.backHref;
						}
						record_ga_event("Outbound Emails", email, linkHref);
					});
					$(this).addClass('ga');
				} else {
					if ($(this).attr('href') == '#') {
						if ($(this).hasClass('submit')) {
							var action = $(this).parents().map(function() {
								if (this.tagName == 'FORM') {
									return this.action;
								}
							}).get().join('');
							record_ga_pageview(action);
						} else {
							$(this).click(function(){
								var link = hist[1]; // because of timing with how jqtouch handles the history vs. this click you don't want the first entry, you need second entry
								if (link.backHref == '') {
									linkHref = '/home/'
								} else {
									linkHref = link.backHref;
								}
								record_ga_pageview(linkHref);
							});
						}	
						$(this).addClass('ga');
					} else {		
						$(this).click(function(){record_ga_pageview($(this).attr('href'))});
						$(this).addClass('ga');
					}
				}
			}
		});
	}
});