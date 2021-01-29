<?php

// db connection config vars
include ('func.php');

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/header-index.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/body.html');

echo "<br><br>";
//Application lookup
$name="Applications";
$type="1";
$titles=array("Application Name","Owner","Criticality");
$returns="d.app_name,c.team_name,d.crit";
$query="SELECT $returns FROM Teams  c, Apps d WHERE d.a_tid=c.tid";

genRO($query,$titles,$type,$name);

//close connection
dbClose();

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');

?>
