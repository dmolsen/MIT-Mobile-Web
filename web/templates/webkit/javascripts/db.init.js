/* Creates a local database that modules can use to store data
 * For example: calendar, maps, & people all store data related
 * to favorites in this db. review their init.js files to see
 * how to do this.
 */
var db;
$(document).ready(function(){
	var shortName = inst_name+"Mobi";
	var version = "0.2";
	var displayName = inst_name+" Mobile Web";
	var maxSize = 2058;
	db = openDatabase(shortName, version, displayName, maxSize);
	db.transaction(
		function(transaction) {
			transaction.executeSql(
				'CREATE TABLE IF NOT EXISTS tmp '+
				' (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT);'
			);
		}
	);
});
function sqlError(transaction, error) {
	alert('SQL problem. It was "'+error.message+'". (Code: '+error.code+')');
}