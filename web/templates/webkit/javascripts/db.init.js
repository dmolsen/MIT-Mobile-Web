var db;
$(document).ready(function(){
	var shortName = inst_name+"Mobi";
	var version = "0.1";
	var displayName = inst_name+" Mobile Web";
	var maxSize = 2058;
	db = openDatabase(shortName, version, displayName, maxSize);
	db.transaction(
		function(transaction) {
			transaction.executeSql(
				'CREATE TABLE IF NOT EXISTS favs_calendar ' +
				' (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, google_id TEXT NOT NULL, date DATE NOT NULL, date_compare INTEGER NOT NULL, title TEXT NOT NULL,' +
				' time TEXT NULL, location TEXT NULL, description TEXT NULL, contact_name TEXT NULL, contact_phone TEXT NULL, ' +
				' contact_email TEXT NULL, event_link TEXT NULL);'
			);
			transaction.executeSql(
				'CREATE TABLE IF NOT EXISTS favs_people '+
				' (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL, givenname TEXT NOT NULL, surname TEXT NOT NULL);'
			);
			transaction.executeSql(
				'CREATE TABLE IF NOT EXISTS favs_map '+
				' (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, map_id NUMERIC NOT NULL, name TEXT NOT NULL, longitude TEXT NOT NULL, latitude TEXT NOT NULL);'
			);
		}
	);
});
function sqlError(transaction, error) {
	alert('SQL problem. It was "'+error.message+'". (Code: '+error.code+')');
}