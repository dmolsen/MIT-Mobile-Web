// when loading a map detail page muck w/ some classes so the map will scroll & intialize the map
// .live is used so that the bindings always fires even if these IDs aren't part of the DOM yet

var map_loaded = false; // hack for google maps v3
var map_moved = false;

$(function(){   				
  $('#map-detail').live('pageAnimationStart', function(event, info){	
	if (info.direction == 'in') {
		var map = map_initialize(); // map_initialize() is in /map/templates/iphone/detail-gmap.html
		$('body').bind('turn', function(event, info){
			if (info.orientation == "landscape") {
				var width = 520; var height = 285;
				$('#map_canvas').css("width",width+"px");
				$('#map_canvas').css("height",height+"px");
				$('#map-overflow').css("width",(width-40)+"px");
				$('#map-overflow').css("height",(height-10)+"px");
				if (map_moved == false) { map.panBy(-80,40); }
			} else {
				var width = 360; var height = 435;
				$('#map_canvas').css("width",width+"px");
				$('#map_canvas').css("height",height+"px");
				$('#map-overflow').css("width",(width-40)+"px");
				$('#map-overflow').css("height",(height-10)+"px");
				if (map_moved == false) { map.panBy(80,-40); }
			}
		});
		checkMapFav();
	}
  });

  // when unloading the map delete the div holding the old detail map so it won't flash & new maps will load					
  $('.clear-map-detail').live('pageAnimationStart', function(event, info){	
		if (info.direction == 'in') {
			$('#map-detail').remove();
			$('#map-detail').unbind();
			$('body').unbind('turn');
			map_moved = false;
			
			// map fav stuff
			var mapFavSelected = false;
			delete sessionStorage.mid;
			delete sessionStorage.mname
			delete sessionStorage.mlongitude;
			delete sessionStorage.mlatitude;
		}
	});
	
    // when unloading the map delete the div holding the old detail map so it won't flash & new maps will load					
    $('.clear-fav-list').live('pageAnimationEnd', function(event, info){	
		if (info.direction == 'out') {
			$('.fav-map-entry').remove();
			$('.fav-map-sep').remove();
		}
	});
	
	// after loading the main calendar view load the number of events for today via ajax & grab the number of favs
	// out of the local database 				
	$('#map').live('pageAnimationEnd', function(event, info){
		if (info.direction == 'out') {
			$(this).removeClass('active');
		} else {
			// get number of favorites
			db.transaction(
				function(transaction) {
					transaction.executeSql(
						'SELECT * FROM favs_map ORDER BY name;',
						[],
						function(transaction,results) { 
							$(".mfavscount").html('<small id="mfavscount" class="counter">'+results.rows.length+'</small>');
						},
						sqlError
					);
				}
			);
			$('#map-favorites').remove();
		}
	});

	// draw the list of calendar favorites, use a hidden li item w/ id of #fav-cal-clone to clone items	
  	$('#map-favorites').live('pageAnimationStart', function(event, info){
		if (info.direction == 'in') {
			db.transaction(
				function(transaction) {
					transaction.executeSql(
						'SELECT * FROM favs_map ORDER BY name;',
						[],
						function(transaction,results) {
							var string_sep = 'A';
							var string_sep_check = false;
							(results.rows.length > 0) ? $('#fav-map-info').hide() : $('#fav-map-info').show();
							for (var i=0; i < results.rows.length; i++) {
								var row = results.rows.item(i);
								
								if ((string_sep_check == false) && (row.name.substr(0,1) == 'A')) {
									// duplicate an entry for a separator
									var newSepRow = $('#fav-map-clone').clone();
									newSepRow.removeAttr('id');
									newSepRow.removeAttr('style');
									newSepRow.addClass('sep');
									newSepRow.addClass('fav-map-sep');
									newSepRow.appendTo('#fav-map-list');
									newSepRow.html(row.name.substr(0,1));
									string_sep_check = true;
								} else if (string_sep != row.name.substr(0,1)) {
									// duplicate an entry for a separator
									var newSepRow = $('#fav-map-clone').clone();
									newSepRow.removeAttr('id');
									newSepRow.removeAttr('style');
									newSepRow.addClass('sep');
									newSepRow.addClass('fav-map-sep');
									newSepRow.appendTo('#fav-map-list');
									newSepRow.html(row.name.substr(0,1));
									string_sep = row.name.substr(0,1);
								}
								
								// duplicate an entry for a person
								var newFavRow = $('#fav-map-clone').clone();
								newFavRow.removeAttr('id');
								newFavRow.removeAttr('style');
								newFavRow.data('entryId',row.id);
								newFavRow.addClass('fav-map-entry');
								newFavRow.appendTo('#fav-map-list');
								
								newFavRow.html("<a href=\"/map/detail.php?loc="+row.map_id+"&lat="+row.latitude+"&long="+row.longitude+"&maptype=roadmap\">"+row.name+"</a>");
							}
						},
						sqlError
					);
				}
			);
		}
	});

	// on moving out of the view showing the map favorites clean up the DOM. not really sure why...
	//$('#map-favorites').live('pageAnimationEnd', function(event, info){
	//	if (info.direction == 'out') {
	//		$('.fav-map-entry').remove();
	//	}
	//});

	// toggle fav graphic on and off as well as add and remove data from db
	$('#map-favorite-detail').live('tap',function(){ mapFav(); });

});

var mapFavSelected = false;

// mark or unmark the item as a favorite
function mapFav() {
	if (mapFavSelected == true) {
		db.transaction(
			function(transaction) {
				transaction.executeSql(
					'DELETE FROM favs_map WHERE map_id = ?;', 
					[sessionStorage.mid], 
					function() { 
						mapFavSelected = false; 
						$('#map-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_unselected.png'); 
					},
					sqlError
				);
			}
		);
	} else if (mapFavSelected == false) {
		db.transaction(
			function(transaction) {
				transaction.executeSql(
					'INSERT INTO favs_map (map_id, name, latitude, longitude) VALUES (?,?,?,?);', 
					[sessionStorage.mid, sessionStorage.mname, sessionStorage.mlatitude, sessionStorage.mlongitude], 
					function() {
						mapFavSelected = true; 
						$('#map-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_selected.png'); 
					},
					sqlError
				);
			}
		);
	}
}

// the actual function that checks if there is a favorite for the map item
function checkMapFav() {
	db.transaction(
		function(transaction) {
			transaction.executeSql(
				'SELECT id FROM favs_map WHERE map_id = ?;', 
				[sessionStorage.mid], 
				function(transaction,results) {
					if (results.rows.length > 0) {
						$('#map-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_selected.png');
						mapFavSelected = true; 
					}	else {
						$('#map-favorite-detail-img').attr('src','/themes/'+theme+'/webkit/images/favorite_unselected.png'); 
						mapFavSelected = false;
					}
				},
				sqlError
			);
		}
	);	
}