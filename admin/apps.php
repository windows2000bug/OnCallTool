<?php

// db connection config vars
include ($_SERVER['DOCUMENT_ROOT'] .'/func.php');

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/header-index.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/body.html');

$returns="c.team_name,a.u_tid,c.tid";
$count=substr_count($returns, ",")+1;
$query="SELECT DISTINCT $returns FROM Users a INNER JOIN Teams c ON a.u_tid = c.parent OR a.u_tid = c.tid AND c.tid not in (select parent from Teams Where parent<>0) INNER JOIN Cal b ON a.uid = b.c_uid ORDER BY c.team_name";

#userCols($query,"teams");

//Creates Fields
$name="Applications";
$type="4";
$titles=array("Application Name","Owner","Criticality","Modify","Delete");
$returns="c.tid,d.app_id,d.app_name,c.team_name,d.crit";
$query="SELECT $returns FROM Teams  c, Apps d WHERE d.a_tid=c.tid";
genFields($query,$titles,$type,$name);

?>
<form action="/mod.php" method="post">
        <tr bgcolor="dddddd">
                <td>
                        <INPUT TYPE="Text" NAME="app" size=50 Placeholder="<Team Name>">
                </td>
                <td>
                        <select name="owner">
                                <?php
					$query="Select team_name,tid FROM Teams";
                                        userCols($query,$mytid);
                                ?>
                        </select>
                </td>
		<td>
                        <select name="crit">
				<option value="1" selected="">24x7</option>
				<option value="0">Next Business Day</option>
                        </select>
                </td>


                <td colspan=2 align="center">
                        <INPUT TYPE="hidden" NAME="type" VALUE=4>
                        <INPUT TYPE="hidden" NAME="func" VALUE=1>
                        <button type="submit">Create Team</button>
                </td>
        </tr>
</form>
</table>
<?php

//close connection
dbClose();

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');

?>
