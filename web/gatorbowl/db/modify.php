<?

require_once "../../../lib/db.php";

$db = db::$connection;
$stmt = $db->prepare("ALTER TABLE PageViews ADD gatorbowl NUMERIC");
$stmt->execute();

?>