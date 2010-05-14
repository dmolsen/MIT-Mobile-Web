$(function(){   				
	  	
		// set-up some standard calendar date stuff
		var d = new Date();
		var m = d.getMonth()+1;
		var j = d.getDate();
		var y = d.getFullYear();
		if (m < 10) { m = "0" + m; }
		if (j < 10) { j = "0" + j; }
		
		// after loading the main calendar view load the number of events for today via ajax & grab the number of favs
		// out of the local database 				
		$('#calendar').live('pageAnimationEnd', function(event, info){
			if (info.direction == 'out') {
				$(this).removeClass('active');
			} else {
				var time = new Date();
				var url = "/calendar/day.php?time="+(Math.round(time.getTime()/1000))+"&type=events&countonly=true";
				$.get(url, function(data){
				   $(".todayscount").html('<small id="todayscount" class="counter">'+data+'</small>');
				});
				db.transaction(
					function(transaction) {
						transaction.executeSql(
							'SELECT * FROM favs_calendar WHERE date_compare >= '+y+m+j+' ORDER BY date_compare;',
							[],
							function(transaction,results) { 
								$(".favscount").html('<small id="favscount" class="counter">'+results.rows.length+'</small>');
							},
							sqlError
						);
					}
				);
				$('#calendar-favorites').remove();
			}
		});
		
		// when animating into the calendar detail see if this item is a favorite or not		
		$('#calendar-detail').live('pageAnimationStart', function(event, info){
			if (info.direction == 'in') {
				checkCalendarFav();
			}
		});
		
		// draw the list of calendar favorites, use a hidden li item w/ id of #fav-cal-clone to clone items	
	  	$('#calendar-favorites').live('pageAnimationStart', function(event, info){
			if (info.direction == 'in') {
				db.transaction(
					function(transaction) {
						transaction.executeSql(
							'SELECT * FROM favs_calendar WHERE date_compare >= '+y+m+j+' ORDER BY date_compare;',
							[],
							function(transaction,results) {
								(results.rows.length > 0) ? $('#fav-cal-info').hide() : $('#fav-cal-info').show();
								for (var i=0; i < results.rows.length; i++) {
									var row = results.rows.item(i);
									var newFavRow = $('#fav-cal-clone').clone();
									newFavRow.removeAttr('id');
									newFavRow.removeAttr('style');
									newFavRow.data('entryId',row.id);
									newFavRow.addClass('fav-cal-entry');
									newFavRow.appendTo('#cal-fav-list');
									if (row['location'] != '') {
										var location = row.location+"<br />";
									} else {
										var location = '';
									}
									var months = new Array("Jan.","Feb.","Mar.","Apr.","May","Jun.","Jul.","Aug.","Sep.","Nov.","Dec.");
									var weekdays = new Array("Sun.","Mon.","Tue.","Wed.","Thu.","Fri.","Sat.");
									var time = new Date(row.date);
									time = weekdays[time.getDay()]+", "+months[time.getMonth()]+" "+time.getDate()+", "+time.getFullYear();
									newFavRow.html("<a href=\"/calendar/detail.php?cal=all&id="+row.google_id+"\" class=\"noellipsis\">"+row.title+"<p class=\"smallprint\" style=\"margin-top: 5px\">"+location+" "+time+" "+row.time.replace(" ","")+"</p></a>");
								}
							},
							sqlError
						);
					}
				);
			}
		});
		
		// on moving out of the view showing the calendar favorites clean up the DOM. not really sure why...
		$('#calendar-favorites').live('pageAnimationEnd', function(event, info){
			if (info.direction == 'out') {
				$('.fav-cal-entry').remove();
			}
		});
		
		// when unloading the calendar details delete the div holding the old detail so divs with that ID load properly in future
		// also delete the sessionStorage data so the options can be re-populated cleanly in the future
		$('.calendar-list').live('pageAnimationEnd', function(event, info){	
			if (info.direction == 'in') {
				$('#calendar-detail').remove();
				calendarFavSelected = false;
				delete sessionStorage.etitle;
				delete sessionStorage.egid;
				delete sessionStorage.elocation;
				delete sessionStorage.edesc;
				delete sessionStorage.ecname;
				delete sessionStorage.ecphone;
				delete sessionStorage.ecemail;
				delete sessionStorage.etime;
				delete sessionStorage.edate;
				delete sessionStorage.edatecompare;
			}
		});
		
		// toggle fav graphic on and off as well as add and remove data from db
		$('#calendar-favorite-detail').live('tap',function(){ calendarFav(); });
		
});

var calendarFavSelected = false;

// mark or unmark the item as a favorite
function calendarFav() {
	if (calendarFavSelected == true) {
		db.transaction(
			function(transaction) {
				transaction.executeSql(
					'DELETE FROM favs_calendar WHERE google_id = ?;', 
					[sessionStorage.egid], 
					function() { 
						calendarFavSelected = false; 
						$('#calendar-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_unselected.png'); 
					},
					sqlError
				);
			}
		);
	} else if (calendarFavSelected == false) {
		db.transaction(
			function(transaction) {
				transaction.executeSql(
					'INSERT INTO favs_calendar (google_id, date, date_compare, title, time, location, description, contact_name, contact_phone, contact_email) VALUES (?,?,?,?,?,?,?,?,?,?);', 
					[sessionStorage.egid, sessionStorage.edate, sessionStorage.edatecompare, sessionStorage.etitle, sessionStorage.etime, sessionStorage.elocation, sessionStorage.edesc, sessionStorage.ecname, sessionStorage.ecphone, sessionStorage.ecemail], 
					function() {
						calendarFavSelected = true; 
						$('#calendar-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_selected.png'); 
					},
					sqlError
				);
			}
		);
	}
}

// the actual function that checks if there is a favorite for the calendar item
function checkCalendarFav() {
	db.transaction(
		function(transaction) {
			transaction.executeSql(
				'SELECT id FROM favs_calendar WHERE google_id = ?;', 
				[sessionStorage.egid], 
				function(transaction,results) {
					if (results.rows.length > 0) {
						$('#calendar-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_selected.png');
						calendarFavSelected = true; 
					}	else {
						$('#calendar-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_unselected.png'); 
						calendarFavSelected = false;
					}
				},
				sqlError
			);
		}
	);	
}