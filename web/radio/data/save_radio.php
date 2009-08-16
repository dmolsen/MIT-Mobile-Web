<?

require_once "../../../lib/db.php";
require_once "radio.import.inc.php";

$days = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
									
$db = db::$connection;
$stmt_1 = $db->prepare("INSERT INTO RadioShows (name) values (?)");	
						
foreach($shows as $show) {
	if (db::$use_sqlite) {
		$stmt_1->execute(array($show));
	}
	else {
		$stmt_1->bind_param('s', $show);
		$stmt_1->execute();
	}
}

$stmt_2 = $db->prepare("INSERT INTO RadioShowTimes (start,end,day,show) values (?,?,?,?)");	
						
foreach($show_times as $show_time) {
	$start = $show_time[0];
	$end = $show_time[1];
        $shows = $show_time[2];
	$i = 0;
	foreach($shows as $show) {
                if (db::$use_sqlite) {
			$stmt_2->execute(array($start,$end,$days[$i],$show));
		}
		else {
			$stmt_2->bind_param('iisi', $start,$end,$days[$i],$show);
			$stmt_2->execute();
		}
		$i++;
	}
	
}
	
?>
