$(function(){   				
		
		// after loading the main people view load the number of favs out of the local database 				
		$('#people').live('pageAnimationEnd', function(event, info){
			if (info.direction == 'out') {
				$(this).removeClass('active');
			} else {
				// get number of favorites
				db.transaction(
					function(transaction) {
						transaction.executeSql(
							'SELECT * FROM favs_people ORDER BY surname;',
							[],
							function(transaction,results) { 
								$(".pfavscount").html('<small id="pfavscount" class="counter">'+results.rows.length+'</small>');
							},
							sqlError
						);
					}
				);
				$('#people-favorites').remove();
			}
		});
		
		// when animating into the people detail see if this item is a favorite or not		
		$('#people-detail').live('pageAnimationStart', function(event, info){
			if (info.direction == 'in') {
				checkPeopleFav();
			}
		});
		
		// draw the list of people favorites, use a hidden li item w/ id of #fav-ppl-clone to clone items	
	  	$('#people-favorites').live('pageAnimationStart', function(event, info){
			if (info.direction == 'in') {
				db.transaction(
					function(transaction) {
						transaction.executeSql(
							'SELECT * FROM favs_people ORDER BY surname;',
							[],
							function(transaction,results) {
								var string_sep = 'A';
								var string_sep_check = false;
								(results.rows.length > 0) ? $('#fav-ppl-info').hide() : $('#fav-ppl-info').show();
								for (var i=0; i < results.rows.length; i++) {
									var row = results.rows.item(i);

									if ((string_sep_check == false) && (row.surname.substr(0,1) == 'A')) {
										// duplicate an entry for a separator
										var newSepRow = $('#fav-ppl-clone').clone();
										newSepRow.removeAttr('id');
										newSepRow.removeAttr('style');
										newSepRow.addClass('sep');
										newSepRow.addClass('fav-ppl-sep');
										newSepRow.appendTo('#fav-ppl-list');
										newSepRow.html(row.surname.substr(0,1));
										string_sep_check = true;
									} else if (string_sep != row.surname.substr(0,1)) {
										// duplicate an entry for a separator
										var newSepRow = $('#fav-ppl-clone').clone();
										newSepRow.removeAttr('id');
										newSepRow.removeAttr('style');
										newSepRow.addClass('sep');
										newSepRow.addClass('fav-ppl-sep');
										newSepRow.appendTo('#fav-ppl-list');
										newSepRow.html(row.surname.substr(0,1));
										string_sep = row.surname.substr(0,1);
									}
									
									// duplicate an entry for a person
									var newFavRow = $('#fav-ppl-clone').clone();
									newFavRow.removeAttr('id');
									newFavRow.removeAttr('style');
									newFavRow.data('entryId',row.id);
									newFavRow.addClass('fav-ppl-entry');
									newFavRow.appendTo('#fav-ppl-list');
									
									newFavRow.html("<a href=\"/people/?username="+row.username+"\"><span class='thin'>"+row.givenname+"</span> "+row.surname+"</a>");
								}
							},
							sqlError
						);
					}
				);
			}
		});
		
		// when unloading the people details delete the div holding the old detail so divs with that ID load properly in future
		// also delete the sessionStorage data so the options can be re-populated cleanly in the future
		$('.people-list').live('pageAnimationEnd', function(event, info){	
			if (info.direction == 'in') {
				$('#people-detail').remove();
				peopleFavSelected = false;
				delete sessionStorage.givenName;
				delete sessionStorage.surname;
				delete sessionStorage.username;
			} else {
				$('.fav-ppl-entry').remove();
				$('.fav-ppl-sep').remove();
			}
		});
		
		// toggle fav graphic on and off when tapped as well as add and remove data from db
		$('#people-favorite-detail').live('tap',function(){ peopleFav(); });
		
});

var peopleFavSelected = false;

// mark or unmark the item as a favorite
function peopleFav() {
	if (peopleFavSelected == true) {
		db.transaction(
			function(transaction) {
				transaction.executeSql(
					'DELETE FROM favs_people WHERE username = ?;', 
					[sessionStorage.username], 
					function() { 
						peopleFavSelected = false; 
						$('#people-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_unselected.png'); 
					},
					sqlError
				);
			}
		);
	} else if (peopleFavSelected == false) {
		db.transaction(
			function(transaction) {
				transaction.executeSql(
					'INSERT INTO favs_people (username, givenname, surname) VALUES (?,?,?);', 
					[sessionStorage.username, sessionStorage.givenName, sessionStorage.surname], 
					function() {
						peopleFavSelected = true; 
						$('#people-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_selected.png'); 
					},
					sqlError
				);
			}
		);
	}
}

// the actual function that checks if there is a favorite for the directory entry
function checkPeopleFav() {
	db.transaction(
		function(transaction) {
			transaction.executeSql(
				'SELECT id FROM favs_people WHERE username = ?;', 
				[sessionStorage.username], 
				function(transaction,results) {
					if (results.rows.length > 0) {
						$('#people-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_selected.png');
						peopleFavSelected = true; 
					}	else {
						$('#people-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_unselected.png'); 
						peopleFavSelected = false;
					}
				},
				sqlError
			);
		}
	);	
}