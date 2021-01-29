<?php
// Connecting, selecting database
include ($_SERVER['DOCUMENT_ROOT'] .'/func.php');
$db=dbCon();

// Performing SQL query
$query = 'SELECT a.uid,a.name,a.phone1,a.phone2,a.phone3,c.team_name,a.u_order FROM Users a, Teams c WHERE a.u_tid=c.tid ORDER BY c.team_name, a.u_order';
$result = mysqli_query($db,$query);

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/header.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/body.html');

?>
<b>Add & Modify Users:</b>
<table border=1 cellpadding=4 bgcolor=dddddd>
        <tr bgcolor="55aa44">
                <td><b>User Name</b></td>
		<td><b>Primay Phone</b></td>
		<td><b>Secondary Phone</b></td>
		<td><b>Tertiary Phone</b></td>
		<td><b>Team</b></td>
		<td><b>Order</b></td>
		<td><b>Update</b></td>
		<td><b>Delete</b></td>

	</tr>

<?php
$y=0;
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    $x=0;
    foreach ($line as $col_value) {
	echo "<form action=\"/mod.php\" method=\"post\">";
	if ( $x < 5 ) {
		if ( $x == 0 ) {
			echo "<INPUT TYPE=\"hidden\" NAME=\"uid\" VALUE=\"$col_value\">";
		} else {
			echo "<td><INPUT TYPE=\"Text\" NAME=\"field$x\" VALUE=\"$col_value\"> </td>";
			echo "<INPUT TYPE=\"hidden\" NAME=\"prefield$x\" VALUE=\"$col_value\">";
		}
	} else {
		if ( $x == 5 ) {
			$query1="SELECT tid,team_name FROM Teams";
			$mydrop="tid";
		} if ( $x == 6 ) {
			$query1="SELECT u_tid,u_order FROM Users WHERE u_tid=$tid";
			$mydrop="order";
		}
		$result1 = mysqli_query($db,$query1);
		echo "<td><select name=\"$mydrop\" >";
		$z=1;
		$a=0;
		$b=0;
		$c=0;
		while ($line1 = mysqli_fetch_array($result1, MYSQL_NUM)) {
			$num_rows= mysqli_num_rows($result1);
			$add=$num_rows+1;
			$select="";
				if ( $x == 6) {
					if ( $col_value === NULL ) {
						$line1[0]=$z;
                                                $line1[1]=$z;
						if ( $b == 0 ) {
							$select.="<option selected=\"selected\" value=NULL>Not Scheduled</option>";
							$b=1;
						}
							$select.="<option value=$line1[0]>$z</option>";
						$z++;
					} else {
						if ( $a == 0 ) {
							$select.="<option value=NULL>Not Scheduled</option>";
							$a=1;
						}
						if ( $col_value == $z ) {
							$select.="<option selected=\"selected\" value=$z>$z</option>";
						} else {
							$select.="<option value=$z>$z</option>";
						}
						$z++;
					}
				} elseif (( $col_value == $line1[1] ) && ($x == 5)) {
					$select.="<option selected=\"selected\" value=$line1[0]>$line1[1]</option>";
					$tid=$line1[0];
				} elseif ( $x == 5 ) {
					if ( $x == 7 ) {				
						$line1[0]=$z;
						$line1[1]=$z;
						$z++;
					}
					$select.="<option value=$line1[0]>$line1[1]</option>";
				}
				if ( $y == 0 ) {
					#$newselect.="$select";
					$newselect.="<option value=$line1[0]>$line1[1]</option>";
				}
			echo "$select";
		}
		echo "</select></td>";
		$y=1;
		$z=1;
		$b=0;
		$c=0;
	}
	
	$x++;
    }
?>
        <INPUT TYPE="hidden" NAME="type" VALUE=1>
        <td><button type="submit" NAME="func" VALUE="2"> Update </button></td>
        <td><button type="submit" NAME="func" VALUE="0" onclick="return confirm('Are you sure you want to delete this user?');" >Delete</button></td>
        </form>


<?php

    echo "\t</tr>\n";
}

if ( $col_value === NULL ) {
    echo "There are no entries in the DB";
}

?>
<form action="/mod.php" method="post">

</tr>
        <tr bgcolor="dddddd">
                <td>
                        <INPUT TYPE="Text" NAME="field" placeholder="<Full Name>">
                </td>
                <td><INPUT TYPE="Text" NAME="phone1" placeholder="<585-555-1234>"></td>
                <td><INPUT TYPE="Text" NAME="phone2" placeholder="<585-555-5678>"></td>
		<td><INPUT TYPE="Text" NAME="phone3" placeholder="<585-555-5678>"></td>
                <td>
                        <select name="tid">
				<option selected="selected" value=1>&lt;Select Team&gt;</option>
                                <?php echo "$newselect"; ?>
                        </select>
                </td>
		<td>
			<select name="diabled">
				<option value=NULL>Not Scheduled</option>
			</select>
		</td> 


<INPUT TYPE="hidden" NAME="type" VALUE=1>
<INPUT TYPE="hidden" NAME="func" VALUE=1>
<td colspan=2 align="center"><button type="submit">Add User</button></td>
</tr>
</form>

</table>
<br><br><br>


<?php

// Closing connection
dbclose();

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');
?>
