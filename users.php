<?php

// db connection config vars
include ($_SERVER['DOCUMENT_ROOT'] .'/func.php');

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/header-index.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/body.html');


echo "<br><br>";
//user lookup
$name="Users:";
$type="0";
$titles=array("User Name","Primary Phone","Secondary Phone","Tertiary Phone","Team");
$returns="a.name,a.phone1,a.phone2,a.phone3,c.team_name";
$query="SELECT Distinct $returns FROM Users a INNER JOIN Teams c ON a.u_tid = c.parent OR a.u_tid = c.tid AND c.tid not in (select parent from Teams Where parent<>0)
	INNER JOIN Cal b ON a.uid = b.c_uid ORDER BY c.team_name, b.prim desc";

genRO($query,$titles,$type,$name);


//close connection
dbClose();

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');

?>
