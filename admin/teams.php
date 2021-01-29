<?php

// Connecting, selecting database
include ($_SERVER['DOCUMENT_ROOT'] .'/func.php');
$db=dbCon();

function userColsTemp($atid,$mytid,$db) {
	$z=0;
	$y=0;
        if ( $atid == "parent" ) {
                $query2="SELECT team_name,tid,parent FROM Teams ORDER BY team_name";
		$query1="select tid,team_name from Teams where tid in (select parent from Teams WHERE tid=$mytid)";
        } else {
                $query2="SELECT team_name,tid,parent FROM Teams ORDER BY team_name";
        }
        
	$result2 = mysqli_query($db,$query2);
        while ($line2 = mysqli_fetch_array($result2, MYSQL_NUM)) {
                $select="";
		if ( $mytid == $line2[1] ) { 
	                if ( $line2[2] == 0 ) {
				if ( $mytid != $line2[1] ) {
					$select.="<option value=$line2[1]>$line2[0]</option>";
				}
                	} else {
				#If the id is in the DB, 
				//$select.="<option selected value=$line2[1]>$line2[0]</option>";
				if ( $mytid == $line2[1] ) {
					$result1= mysqli_query($db,$query1);
					$res= mysqli_fetch_row($result1);
					$select.="<option selected value=$res[0]>$res[1]</option>";				
					$z=1;
				}
                	}
		} else {
			$select.="<option value=$line2[1]>$line2[0]</option>";
		}
                if ( $y == 0 ) {
                	$newselect.="<option value=$line2[1]>$line2[0]</option>";
                }
                echo "$select";
	}
        $y=1;
	if ( $z == 0 ) {
		$sel="selected";
	}
		echo "<option $sel value=0 ></option>";
		

}

// Performing SQL query
$query = 'SELECT tid,team_name,parent FROM Teams ORDER BY team_name';
$result = mysqli_query($db,$query);

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/header.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/body.html');

?>

<b>Add & Modify Teams:</b>

<table border=1 >
        <tr bgcolor="55aa44">
                <td><b>Team Name</b></td>
		<td><b>Parent</b></td>
		<td><b>Update</b></td>
		<td><b>Delete</b></td>
	</tr>

<?php
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    $x=0;
    foreach ($line as $col_value) {
	echo "<form action=\"/mod.php\" method=\"post\">";
	if ( $x == 0 ) {
		echo "<INPUT TYPE=\"hidden\" NAME=\"tid\" VALUE=\"$col_value\">";
		$mytid="$col_value";
	} elseif ( $x == 1 ) {
		echo "<td><INPUT TYPE=\"Text\" NAME=\"field\" size=50 VALUE=\"$col_value\"</td>";
	} elseif ( $x == 2 ) {
		?>
		<td>
                <select name="parent">
                        <?php
                        	userColsTemp("parent",$mytid,$db)
                       	?>
            	</select>
		</td>
		<INPUT TYPE="hidden" NAME="type" VALUE=3>
		<td><button type="submit" name="func" VALUE=2>Update</button></td>
		<td><button type="submit" NAME="func" VALUE=0 onclick="return confirm('Are you sure you want to delete this team, if it is not Populated?');">Delete</button></td>
		</form>
		

<?php
#	echo "\t\t<td><a href='mod.php?type=grp&func=0&field=$col_value'>Delete Group</a></td>\n";
	}
	$x++;
	
    }
    echo "\t</tr>\n";
}
#<INPUT TYPE="Text" NAME="field">
?>
	<form action="/mod.php" method="post">
	<tr bgcolor="dddddd">
		<td align="center" valign="bottom">
			<INPUT TYPE="Text" NAME="field" size=50 Placeholder="<Team Name>">
		</td>
		<td align="center" valign="bottom">
	                <select name="parent">
        	                <?php
                                	userColsTemp("all",$mytid,$db)
                	        ?>
	                </select>

                </td>

		<td colspan=2 align="center" valign="middle">
        	        <INPUT TYPE="hidden" NAME="type" VALUE=3>
	                <INPUT TYPE="hidden" NAME="func" VALUE=1>
			<button type="submit">Create Team</button>
		</td>
	</tr>
</form>
<?php

if ( $col_value === NULL ) {
    echo "There are no entries in the DB";
}

?>
</table>
<br><br>
Note: Teams that are populated can't be deleted!
<?php

// Closing connection
dbclose();

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');
?>
