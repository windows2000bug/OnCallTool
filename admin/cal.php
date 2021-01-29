<?php

include ($_SERVER['DOCUMENT_ROOT'] .'/func.php');
$db=dbCon();

function userColsTemp($atid,$db) {
	if ( $atid == "users" ) {
		$query2="SELECT name,uid,u_tid FROM Users";
	} elseif ( $atid == "teams" ) {
		$query2="SELECT DISTINCT c.team_name,a.u_tid FROM Users a INNER JOIN Teams c ON a.u_tid = c.parent OR a.u_tid = c.tid AND c.tid not in (select parent from Teams Where parent<>0) 
			ORDER BY c.team_name";

	} else {
		$query2="SELECT name,uid,u_tid FROM Users where u_tid=$atid";
	}
	#$query2="SELECT name,uid,u_tid FROM Users $moo";
        $result2 = mysqli_query($db,$query2);
        while ($line2 = mysqli_fetch_array($result2, MYSQL_NUM)) {
		$select="";
		if ( $line2[2] !== NULL ) {
			$s="|";
			$select.="<option value=\"$line2[1] $s $line2[2]\">$line2[0]</option>";
		} else {
			$select.="<option value=\"$line2[1]\">$line2[0]</option>";
		}
#                if ( $col_value == $line2[0] ) {
 #               	$select.="<option selected=\"selected\" value=\"$line2[1]$s$line2[2]\">$line2[0]</option>";
  #              } else {
#                        $select.="<option value=\"$line2[1] $s $line2[2]\">$line2[0]</option>";
   #              }
                 #if ( $y == 0 ) {
                 #	$newselect.="<option value=\"$line2[1]$s$line2[2]\">$line2[0]</option>";
                 #       $newselect.="$select";
                 #}
                echo "$select";
       }
           #      $y=1;

}

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/header.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/body.html');

?>

<b>Search:</b>
<form action="/mod.php" method="post">
<table border=1 >
	<tr bgcolor="55aa44">
		<td><b>Teams<font color=red>*</font></b></td>
		<td><b>Users</b></td>
                <td><b>Date &#8805; </b></td>
                <td><b>Search</b></td>
	</tr>
        <tr bgcolor="dddddd" >
		<td>
                        <select name="teams">
                                <option selected value="" >&lt;Team&gt;</option>
                                <?php
                                        userColsTemp("teams",$db)

                                ?>
                        </select>
                </td>
                <td>
                        <select name="field">
                                <option selected value="" >&lt;User&gt;</option>
                                <?php
                                        userColsTemp("users",$db)
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

                <td colspan=2 align="center">
                        <INPUT TYPE="hidden" NAME="type" VALUE=2>
                        <INPUT TYPE="hidden" NAME="func" VALUE=4>
                        <Button TYPE="Submit" Value="Search">Search</button>
                </td>
        </tr>
</table>

</form>

<?php
$chkName=($_GET["name"]);
$chkDate=($_GET["date"]);

$chkQuery=($_GET["Query"]);

if ( $chkQuery != NULL ) {
	$team="WHERE tid=$chkQuery";
} else {
	echo "<br><b>Please pick a team, it is a required field!</b><br>";

	// Closing connection
	dbclose();
	include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');
	exit;

}

$y=0;

#$query0="SELECT tid,team_name FROM Teams WHERE tid in (SELECT DISTINCT u_tid FROM Users)";
$query0="SELECT tid,team_name FROM Teams $team";
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
	if ( $chkName != NULL ) {
        	$name="AND a.uid=$chkName";
	}
	if ( $chkDate != NULL ) {
		$myDate="\"$chkDate\" <= b.date_end";
	} else {
		$myDate="current_timestamp <= b.date_end";
	}

	$query="SELECT b.cid,a.uid,a.name,b.date_start,b.date_end,b.prim,c.team_name FROM Users a, Cal b, Teams c WHERE a.u_tid=c.tid AND a.uid=b.c_uid AND a.u_tid=$mytid $name AND $myDate ORDER BY b.date_start, b.prim desc";

	$result = mysqli_query($db,$query);
	echo "<br><br><b>Team Calendar: $myteam</b><br>";
	?>


<table border=1>
        <tr bgcolor=55aa44>
                <td><b>User Name</b></td>
                <td><b>Start Call</b></td>
                <td><b>End Call</b></td>
                <td><b>Call</b></td>
                <td><b>Update</b></td>
                <td><b>Delete</b></td>

        </tr>

<?php
	while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	    echo "<tr>";
	    $x=0;
	    foreach ($line as $col_value) {
		echo "<form action=\"/mod.php\" method=\"post\">";
		if ( $x > 2 ) {
			if ( $x == 5 ) {
				echo "<td><select name=\"Call\">";
				if ( $col_value == 1 ) {
                        	        echo "<option selected=\"selected\" value=1>Primary</option>";
	                        	echo "<option value=0>Backup</option>";
				} else {
        	                        echo "<option value=1>Primary</option>";
                	                echo "<option selected=\"selected\" value=0>Backup</option>";
				}
	                        echo "</select></td>";
			} elseif ( $x == 6 ) {
			
			} else {
				$mydate= date("Y-m-d\TH:i:s", strtotime($col_value));
				if ( $x == 4 ) {	
					echo "<td><INPUT TYPE=\"datetime-local\" NAME=\"field$x\" min=\"$min\" VALUE=\"$mydate\"> </td>";
				} else {
					//echo "<script text/javascript>document.getElementById(\"field4\").min=\"$mydate\"</script>";
					$min="$mydate";
					echo "<td><INPUT TYPE=\"datetime-local\" NAME=\"field$x\" VALUE=\"$mydate\"></td>";
				}
                                ?>
                                <script type="text/javascript">
                                        if (navigator.userAgent.search("MSIE") & gt; = 0) {
						var chkdate = document.getElementById("field<?php echo "$x"; ?>").value
						if (document.getElementById("field<?php echo "$x"; ?>").value == "") {
							alert("Please enter the Date..!!")
							hh.date_slot.focus();
							return false;
						}
						else if (!chkdate.match(/^(0[1-9]|1[012])[\-\/.](?:(0[1-9]|[12][0-9]|3[01])[\- \/.](19|20)[0-9]{2})$/)) {
							alert('date format is wrong');
							hh.date_slot.focus();
							return false;
						}
                                        }
                                </script>
                                <?php


			}
		} else {
			if ( $x == 0 ) {
                	        echo "<INPUT TYPE=\"hidden\" NAME=\"cid\" VALUE=\"$col_value\">";
			} elseif ( $x == 1 ) {
				echo "<INPUT TYPE=\"hidden\" NAME=\"uid\" VALUE=\"$col_value\">";
                	} else {
	
				$query1="SELECT name,uid,u_tid FROM Users where u_tid=$mytid";
				$result1 = mysqli_query($db,$query1);
				echo "<td><select name=\"uid\" >";
				while ($line1 = mysqli_fetch_array($result1, MYSQL_NUM)) {
	
					$select="";
					if ( $col_value == $line1[0] ) {
						$select.="<option selected=\"selected\" value=\"$line1[1]|$line1[2]\">$line1[0]</option>";
					} else {
						$select.="<option value=\"$line1[1]|$line1[2]\">$line1[0]</option>";
					}
					if ( $y == 0 ) {
						$newselect.="<option value=\"$line1[1]|$line1[2]\">$line1[0]</option>";
						#$newselect.="$select";
					}
					echo "$select";
				}
				echo "</select></td>";
				$y=1;
			}
		}
		
		$x++;
    	}
?>
        <INPUT TYPE="hidden" NAME="type" VALUE=2>
        <td><button type="submit" name="func" VALUE=2>Update</button></td>
        <td><button type="submit" NAME="func" VALUE=0>Delete</button></td>
        </form>


<?php

    echo "\t</tr>\n";
	
}
#if ( $y == 0 ) {
#	userCols($mytid);
#} 

#echo "</table>";
#<option selected="selected" value="" color=#999;>&lt;Add Call&gt;</option>

?>
<form action="/mod.php" method="post">

        <tr bgcolor="dddddd" >
                <td>
                        <select name="uid">
				<option selected value="" >&lt;Add Call&gt;</option>
                                <?php echo userColsTemp($mytid,$db) ;?>
                        </select>
                </td>
                <td>
			<input type="text" name="start" value="">
                        <script type="text/javascript">
                                $(function(){
                                        $('*[name=start]').appendDtpicker({
                                                "autodateOnStart": false,
                                                "closeOnSelected": true
                                        });
                                });
                        //<INPUT TYPE="datetime-local" NAME="start">
                        </script>
		</td>
                <td>	
			<input type="text" name="end" value="">
                        <script type="text/javascript">
                                $(function(){
                                        $('*[name=end]').appendDtpicker({
                                                "autodateOnStart": false,
                                                "closeOnSelected": true
                                        });
                                });
			//<INPUT TYPE="datetime-local" NAME="end">
                        </script>

		</td>
                <td>
                        <select name="Call">
                                <option value=1>Primary</option>
                                <option value=0>Backup</option>
                        </select>
                </td>
		<td colspan=2 align="center">
			<INPUT TYPE="hidden" NAME="type" VALUE=2>
			<INPUT TYPE="hidden" NAME="func" VALUE=1>
			<Button type="Submit" value="Add">Add</button>
		</td>
	</tr>
</table>

</form>

<?php
$newselect="";
$y=0;
}
if ( $col_value === NULL ) {
    echo "There are no entries in the DB";
}
?>
<br><br>

<?php

// Closing connection
dbclose();
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');
?>
