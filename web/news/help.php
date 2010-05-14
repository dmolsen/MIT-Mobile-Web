<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../../config.gen.inc.php";

$header = $inst_name." Campus News";
$module = "news";

$help = array(
    'Get the latest news items regarding events affecting the '.$inst_name.' community.',
);

require "../page_builder/help.php";

?>
