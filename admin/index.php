<?php

// db connection config vars
include ($_SERVER['DOCUMENT_ROOT'] .'/func.php');

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/header-index.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/body.html');

?>

<form action="/mod.php" method="post">
	<table border=1 >
	        <tr bgcolor="55aa44">
	                <td colspan=6 align="center"><b>Search Tool</b></td>
	        </tr>
	        <tr bgcolor="dddddd" >
	                <td>
                	        <select name="teams">
        	                        <option selected value="" >&lt;Team&gt;</option>
<?php

$returns="c.team_name,a.u_tid,c.tid";						
$count=substr_count($returns, ",")+1;
$query="SELECT DISTINCT $returns FROM Users a INNER JOIN Teams c ON a.u_tid = c.parent OR a.u_tid = c.tid AND c.tid not in (select parent from Teams Where parent<>0) INNER JOIN Cal b ON a.uid = b.c_uid ORDER BY c.team_name";

userCols($query,"teams");

?>
	      	                </select>
	                </td>
	                <td colspan=2 align="center">
                        	<INPUT TYPE="hidden" NAME="type" VALUE=2>
                	        <INPUT TYPE="hidden" NAME="func" VALUE=3>
        	                <INPUT TYPE="hidden" NAME="landing" VALUE=1>
	                        <Button TYPE="Submit" Value="Search">Search</button>
        	        </td>
</form>
			<td width="20%" bgcolor="55aa44"></td>
<form action="/mod.php" method="post">
                        <td>
                                <select name="teams">
                                        <option selected value="" >&lt;Application&gt;</option>
<?php


$returns="d.app_name,d.a_tid,c.tid";
$count=substr_count($returns, ",")+1;
$query="SELECT DISTINCT $returns FROM Teams c, Apps d WHERE c.tid=d.a_tid";
userCols($query,"Apps");

?>
                                </select>
                        </td>

                        <td colspan=2 align="center">
                                <INPUT TYPE="hidden" NAME="type" VALUE=2>
                                <INPUT TYPE="hidden" NAME="func" VALUE=3>
                                <INPUT TYPE="hidden" NAME="landing" VALUE=1>
                                <Button TYPE="Submit" Value="Search">Search</button>
                        </td>
                </tr>
        </table>
</form>

<?php

$chkQuery=($_GET["Query"]);

if ( $chkQuery != NULL ) {
        $input="$chkQuery";
        $name="Search Results:";
        $type="2";
        $titles=array("Call","Team","Name","Primary Phone","Secondary Phone","Tertiary Phone");
        $returns="b.prim,c.team_name,a.name,a.phone1,a.phone2,a.phone3";
        $query="SELECT $returns FROM Users a INNER JOIN Teams c ON a.u_tid = c.parent OR a.u_tid = c.tid AND c.tid not in
                (select parent from Teams Where parent<>0) INNER JOIN Cal b ON a.uid = b.c_uid AND
                (b.date_start < current_timestamp AND current_timestamp < b.date_end) AND c.tid=? ORDER BY c.team_name, b.prim desc;";
        genRW($query,$titles,$type,$name,$input);

}

echo "<br><br>";
//Current On call
$name="Currently On Call:";
$type="2";
$titles=array("Call","Team","Name","Primary Phone","Secondary Phone","Tertiary Phone");
$returns="b.prim,c.team_name,a.name,a.phone1,a.phone2,a.phone3";
$query="SELECT $returns FROM Users a INNER JOIN Teams c ON a.u_tid = c.parent OR a.u_tid = c.tid AND c.tid not in (select parent from Teams Where parent<>0)
        INNER JOIN Cal b ON a.uid = b.c_uid AND  (b.date_start < current_timestamp AND current_timestamp < b.date_end) ORDER BY c.team_name, b.prim desc";
genRO($query,$titles,$type,$name);

//close connection
dbClose();

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');

?>
