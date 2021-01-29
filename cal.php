<?php
// Connecting, selecting database
include ($_SERVER['DOCUMENT_ROOT'] .'/func.php');
$db=dbCon();

function userColsTemp($atid,$db) {
	
	if ( $atid == "users" ) {
		$query2="SELECT name,uid,u_tid FROM Users";
	} elseif ( $atid == "teams" ) {
		#$query2="SELECT team_name,tid FROM Teams";
		$query2="SELECT DISTINCT c.team_name,a.u_tid FROM Users a INNER JOIN Teams c ON a.u_tid = c.parent OR a.u_tid = c.tid AND c.tid not in (
			select parent from Teams Where parent<>0) INNER JOIN Cal b ON a.uid = b.c_uid ORDER BY c.team_name";
	} else {
		$query2="SELECT name,uid,u_tid FROM Users where u_tid=$atid";
	}
	#$query2="SELECT name,uid,u_tid FROM Users $moo";
        $result2 = mysqli_query($db, $query2);
        while ($line2 = mysqli_fetch_array($result2, MYSQL_NUM)) {
		$select="";
		if ( $line2[2] !== NULL ) {
			$s="|";
		}
                if ( $col_value == $line2[0] ) {
                	$select.="<option selected=\"selected\" value=\"$line2[1]$s$line2[2]\">$line2[0]</option>";
                } else {
                        $select.="<option value=\"$line2[1]$s$line2[2]\">$line2[0]</option>";
                 }
                 if ( $y == 0 ) {
                 	$newselect.="<option value=\"$line2[1]$s$line2[2]\">$line2[0]</option>";
                        #$newselect.="$select";
                 }
                 echo "$select";
                 }
                 $y=1;

}


include ($_SERVER['DOCUMENT_ROOT'] .'/templates/header.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/body.html');

?>


<b>Search:</b>
<form action="mod.php" method="post">
<table border=1>
	<tr bgcolor="55aa44"> 
		<td><b>Name</b></td>
		<td><b>Teams</b></td>
                <td><b>Date</b></td>
                <td><b>Search</b></td>
	</tr>
        <tr bgcolor="dddddd" >
                <td>
			<select name="field">
                                <option selected value="" >&lt;User&gt;</option>
				<?php
#					userCols("users")
					userColsTemp("users",$db)
				?>
			</select>
		</td>
		<td>
                        <select name="teams">
                                <option selected value="" >&lt;Team&gt;</option>
                                <?php
#                                        userCols("teams")
					userColsTemp("teams",$db)

                                ?>
                        </select>
                </td>
                <td>	


		        <input type="text" name="date" value="">
		        <script type="text/javascript">
                                $(function(){
                                        $('*[name=date]').appendDtpicker({
                                                "autodateOnStart": false,
						"closeOnSelected": true
                                        });
                                });

			</script>
		</td>

                <td align="center">
                        <INPUT TYPE="hidden" NAME="type" VALUE=2>
                        <INPUT TYPE="hidden" NAME="func" VALUE=3>
                        <Button TYPE="Submit" Value="Search">Search</button>
                </td>
        </tr>
</table>

</form>

<?php
$chkQuery=($_GET["Query"]);

if ( $chkQuery != NULL ) {
	?>
		
		<br><br>
			<B>Search Results:</b>
			<table border=1 cellpadding=3 width=50% >
			<tr bgcolor="55aa44">
		                <td><b>User Name</b></td>
	                	<td><b>Start Call</b></td>
	        	        <td><b>End Call</b></td>
        	        	<td><b>Call</b></td>
				<td><b>Team</b></td>
			</tr>

	<?php
	$result9 = mysqli_query($db,$chkQuery);
	while ($line9 = mysqli_fetch_array($result9, MYSQL_ASSOC)) {
		echo "</tr>";	
		$m=0;
		foreach ($line9 as $col_value9) {
			if ( $m == 3 ) {
				if ($col_value9 == 1 ) {
					echo "<td>Primary</td>";
				} else {
					echo "<td>Backup</td>";
				}
			} else {
				if ( $m ==0 ) {
					echo "<td width=20%>$col_value9</td>";
				} else {
					echo "<td>$col_value9</td>";
				}
			}
			$m++;
		}
		echo "</tr>";
	}
		if ( $m == 0 ) {
			echo "<tr><td colspan=4 align=center>No Entries Found</td></tr>";
		}
	echo "</table><br><br>";

}
$y=0;

$query0="SELECT tid,team_name FROM Teams WHERE tid in (SELECT DISTINCT u_tid FROM Users)";
$result0 = mysqli_query($db,$query0);
$z=0;
while ($line0 = mysqli_fetch_array($result0, MYSQL_ASSOC)) {
	foreach ($line0 as $col_value0) {
		if ( $z == 1 ) {
			$myteam="$col_value0";
			$z=0;
			$y=0;
			
		} else {
			$mytid=$col_value0;
			$z=1;
		}
	}	
	
#	$query="SELECT b.cid,a.uid,a.name,b.date_start,b.date_end,b.prim,c.team_name FROM Users a, Cal b, Teams c WHERE a.u_tid=c.tid AND a.uid=b.c_uid AND a.u_tid=$mytid AND current_timestamp < b.date_end ORDER BY b.date_start, b.prim desc";
	$query="SELECT b.cid,a.uid,a.name,b.date_start,b.date_end,b.prim,c.team_name FROM Users a, Cal b, Teams c WHERE a.u_tid=c.tid AND a.uid=b.c_uid AND a.u_tid=$mytid AND current_timestamp <= b.date_end ORDER BY b.date_start, b.prim desc";

	$result = mysqli_query($db,$query);
	echo "<br><br><b>Team Calendar: $myteam</b><br>";
	?>


<table border=1 width=50%>
        <tr bgcolor=55aa44>
                <td><b>User Name</b></td>
                <td><b>Start Call</b></td>
                <td><b>End Call</b></td>
                <td><b>Call</b></td>

        </tr>

<?php
	while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	    echo "<tr>";
	    $x=0;
	    foreach ($line as $col_value) {
		if ( $x > 2 ) {
			if ( $x == 5 ) {
				echo "<td>";
				if ( $col_value == 1 ) {
                        	        echo "Primary";
				} else {
                	                echo "Backup";
				}
	                        echo "</td>";
			} elseif ( $x == 6 ) {
			
			} else {
				$mydate= date("Y-m-d H:i:s", strtotime($col_value));
				if ( $x == 4 ) {	
					echo "<td> $mydate </td>";
#					echo "<td><INPUT TYPE=\"datetime-local\" disabled NAME=\"field$x\" min=\"$min\" VALUE=\"$mydate\"> </td>";
				} else {
					//echo "<script text/javascript>document.getElementById(\"field4\").min=\"$mydate\"</script>";
					$min="$mydate";
					echo "<td> $mydate </td>";
#					echo "<td><INPUT TYPE=\"datetime-local\" disabled NAME=\"field$x\" VALUE=\"$mydate\"></td>";
				}


			}
		} else {
			if ( $x == 0 ) {
#                	        echo "<INPUT TYPE=\"hidden\" NAME=\"cid\" VALUE=\"$col_value\">";
			} elseif ( $x == 1 ) {
#				echo "<INPUT TYPE=\"hidden\" NAME=\"uid\" VALUE=\"$col_value\">";
                	} else {
	
				$query1="SELECT name FROM Users where u_tid=$mytid";
				$result1 = mysqli_query($db,$query1);
				echo "<td width=20%>";
				while ($line1 = mysqli_fetch_array($result1, MYSQL_NUM)) {
	
					$select="";
					if ( $col_value == $line1[0] ) {
						$select.="$line1[0]";
					} else {
#						$select.="<option value=\"$line1[1]|$line1[2]\">$line1[0]</option>";
					}
					if ( $y == 0 ) {
#						$newselect.="<option value=\"$line1[1]|$line1[2]\">$line1[0]</option>";
						#$newselect.="$select";
					}
					echo "$select";
				}
				echo "</td>";
				$y=1;
			}
		}
		
		$x++;
    	}
?>


<?php

    echo "</tr>";
	
}
#if ( $y == 0 ) {
#	userCols($mytid);
#} 

echo "</table>";
$newselect="";
$y=0;
}
if ( $col_value === NULL ) {
    echo "There are no entries in the DB";
}
?>
<br><br>

<?php
// Free resultset
#mysqli_free_result($result);

// Closing connection
#mysqli_close($db);

dbclose();
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');

?>

