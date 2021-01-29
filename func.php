<?php

function dbCon() {
	// db connection config vars
	require_once('config.php');
	$user = DBUSER;
	$pass = DBPWD;
	$dbName = DBNAME;
	$dbHost = DBHOST;

	//Connect to Databasea
	static $db= null;
	$db = new mysqli($dbHost,$user,$pass,$dbName);
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	return $db;
}

function dbPrep($db,$query,$x,$prep) {
	//Function to eliminate SQL injections.	
	$statement = $db->prepare("$query");
	if ( $x !== 0 ) {
		$statement->bind_param("$x", $prep);
	}


	$statement->execute();

	$statement->bind_result($one);
	while($statement->fetch()){
	    echo $one.'<br />';
	}
	
	$statement->free_result();
}

function dbROorig($query) {
	//Use only for non-user set/built SQL to avoid injection!
	$db=dbCon();
	if(!$statement = $db->query($query)){
 		   die('There was an error running the query [' . $db->error . ']');
	}
	
	
	while($row = $statement->fetch_assoc()){
		return  array ($row);
# 		echo $row['team_name'] . '<br />';
	}
	
	$statement->free_result();
}

function dbRO($query) {
        //Use only for non-user set/built SQL to avoid injection!
        $db=dbCon();
        if(!$statement = $db->query($query)){
                   die('There was an error running the query [' . $db->error . ']');
        }

        while($row = $statement->fetch_assoc()){
		$x=0;
		echo "<tr>";
		foreach ($row as $col_value) {
	        	if ( $x == 0 ) {
        		        if ( $col_value == 1 ) {
                        		$color="cccccc";
		                        echo "<td bgcolor=$color ><font color=\"red\">Primary</font></td>";
                		} else {
		                        $color="ffffff";
                		        echo "<td bgcolor=$color><font color=\"black\">Backup</font></td>";
                		}
		        } else {
                		if ( $x == $count ) {
		                        echo "<td bgcolor=$color width=70%> <font size=2>$col_value</font> </td>";
                		} else {
		                        echo "\t\t<td bgcolor=$color>$col_value </td>\n";
                		}
		        }
		        $x++;
		    }
		echo "</tr>";
        }

        $statement->free_result();
}

function userCols($query, $atid) {
        $db=dbCon();
        if(!$statement = $db->query($query)){
                   die('There was an error running the query [' . $db->error . ']');
        }


	while($row = mysqli_fetch_array($statement, MYSQLI_NUM)) {
                $select="";
                if ( $row[2] !== NULL ) {
                        $s="|";
                }
                if ( $col_value == $row[0] ) {
                        $select.="<option selected=\"selected\" value=\"$row[1]$s$row[2]\">$row[0]</option>";
                } else {
                        $select.="<option value=\"$row[1]$s$row[2]\">$row[0]</option>";
                 }
                 if ( $y == 0 ) {
                        $newselect.="<option value=\"$row[1]$s$row[2]\">$row[0]</option>";
                        #$newselect.="$select";
                 }
                 echo "$select";
	}
	$y=1;
	$statement->free_result();

}
/*
function userColsTemp($atid) {
	$db=dbCon();
        if(!$statement = $db->query($query)){
                   die('There was an error running the query [' . $db->error . ']');
        }
	echo "$atid";
        if ( $atid == "users" ) {
                $query2="SELECT name,uid,u_tid FROM Users";
        } elseif ( $atid == "teams" ) {
                $query2="SELECT DISTINCT c.team_name,a.u_tid FROM Users a INNER JOIN Teams c ON a.u_tid = c.parent OR a.u_tid = c.tid AND c.tid not in (select parent from Teams Where parent<>0)
                        ORDER BY c.team_name";

        } else {
                $query2="SELECT name,uid,u_tid FROM Users where u_tid=$atid";
        }
        #$query2="SELECT name,uid,u_tid FROM Users $moo";
        $result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
        while ($line2 = mysql_fetch_array($result2, MYSQL_NUM)) {
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
*/
function genRO($query,$titles,$type,$name) {
        //Use only for non-user set/built SQL to avoid injection!
	echo "<b>$name</b><br><br>";
	echo "<table border=1 width=65%>";

        $db=dbCon();
        if(!$statement = $db->query($query)){
                   die('There was an error running the query [' . $db->error . ']');
        }
	echo '<tr bgcolor="55aa44">';
	$a="0";
	$x=0;
	foreach ($titles as $display) {
		echo "<td><b>$display</b></td>";
	}
	echo "</tr>";
        while($row = $statement->fetch_assoc()){
                echo "<tr>";
		$x=0;
	      	if ( $a == 0 ) {
                	$color="cccccc";
	                $a=1;
        	} else {
                	$color="ffffff";
	                $a=0;
        	}
                foreach ($row as $col_value) {
			def($type,$col_value,$x,$color);
			$x++;
                    }
                echo "</tr>";
        }
	echo "</table>";
        $statement->free_result();
}

function genRW($query,$titles,$type,$name,$input) {
        //Function to prevent sql injections
        echo "<br><b>$name</b>";
        echo "<table border=1 width=65%>";

        $db=dbCon();
        echo '<tr bgcolor="55aa44">';
        $a="0";
        $x=0;
        foreach ($titles as $display) {
                echo "<td><b>$display</b></td>";
        }
        echo "</tr>";
	$statement = $db->prepare($query);
	$statement->bind_param('i', $input);
	$statement->execute();
	$result=$statement->get_result();
	
	if($result->num_rows === 0) exit('No rows');
        while($row = $result->fetch_assoc()){
                echo "<tr>";
                $x=0;
                if ( $a == 0 ) {
                        $color="cccccc";
                        $a=1;
                } else {
                        $color="ffffff";
                        $a=0;
                }
                foreach ($row as $col_value) {
                        def($type,$col_value,$x,$color);
                        $x++;
                    }
                echo "</tr>";
        }
        echo "</table>";
        $statement->free_result();
}

function genFields($query,$titles,$type,$name) {
        //Use only for non-user set/built SQL to avoid injection!
        echo "<b>$name</b>";
        echo "<table border=1 width=65%>";

        $db=dbCon();
        if(!$statement = $db->query($query)){
                   die('There was an error running the query [' . $db->error . ']');
        }
        echo '<tr bgcolor="55aa44">';
        $a="0";
        $x=0;
        foreach ($titles as $display) {
                echo "<td><b>$display</b></td>";
        }
        echo "</tr>";
        while($row = $statement->fetch_assoc()){
                echo "<tr>";
                $x=0;
                if ( $a == 0 ) {
                        $color="cccccc";
                        $a=1;
                } else {
                        $color="ffffff";
                        $a=0;
                }
                foreach ($row as $col_value) {
                        if ( $x == 0 ) {
                                $id=$col_value;
                        }
                        def($type,$col_value,$x,$color,$id);
                        $x++;
                }

                echo "</tr>";
        }
        $statement->free_result();
}

function def($type,$col_value,$x,$color,$id) {
	//If Apps Page Display
	if ( $type == 1 ) {
		if ( $x == 2 ) {
			if ( $col_value == 1 ) {
				echo "<td bgcolor=$color> 24x7 </td>";
			} else {
				echo "<td bgcolor=$color> Next Business Day </td>";
			}
		} else {
			echo "<td bgcolor=$color> $col_value </td>";
		}
	} elseif ($type == 2) {
		if ( $col_value == 1 ) {
			$call="<td bgcolor=$color ><font color=\"red\">Primary</font></td>";
		 } else {
			$call="<td bgcolor=$color><font color=\"black\">Backup</font></td>";
		}
		if ( $x == 0 ) {
			echo "$call";	
		} else {
	                if ( $x == 6 ) {
	                        echo "<td bgcolor=$color width=70%> <font size=2>$col_value</font> </td>";
        	        } else {
                	        echo "<td bgcolor=$color>$col_value </td>";
	                }
                }
	} elseif ( $type == 3 ) {
		 if ( $x == 3 ) {
                	if ( $col_value == 1 ) {
	                       echo "<td bgcolor=$color >Primary</td>";
        	        } else {
                 	       echo "<td bgcolor=$color>Backup</td>";
	                }
		} elseif ( $x == 4) {
			//do nothing
		} else {
                        echo "<td bgcolor=$color> $col_value </td>";
                }
	 } elseif ( $type == 4 ) {
                if ( $x == 0 ) {
                        echo "<form action=\"/mod.php\" method=\"post\">";
                } elseif ( $x == 1 ) {
                        echo "<INPUT TYPE=\"hidden\" NAME=\"app_id\" VALUE=$col_value>";
                } elseif ( $x == 2 ) {
                        echo "<td bgcolor=$color><input type=Text name=app value=$col_value size=50></td>";
                } elseif ( $x == 3 ) {
                        echo "<td bgcolor=$color><select name=\"owner\">";
                        $query="SELECT tid,team_name from Teams";
                        getOpts($query,$id);
                        echo "</select></td>";
                } elseif ( $x == 4 ) {
                        echo "<td bgcolor=$color><select name=\"crit\">";
                        $selected1="";
                        $selected2="";
                        if ( $col_value == 1 ) {
                                $selected1="Selected";
                        } else {
                                $selected2="selected";
                        }
                        echo "<option value=1 $selected1>24x7</option>";
                        echo "<option value=0 $selected2>Next Business Day</option>";
                        echo "</select></td>";
                        echo '<INPUT TYPE="hidden" NAME="type" VALUE=4>';

                        echo '<td><button type="submit" name="func" VALUE=2>Update</button></td>';
                        echo '<td><button type="submit" NAME="func" VALUE=0>Delete</button></td>';
                        echo "</form>";
                }

	} else {
		echo "<td bgcolor=$color> $col_value </td>";
	}

}

function getOpts($query,$id) {
        //Use only for non-user set/built SQL to avoid injection!
        $db=dbCon();
        if(!$statement = $db->query($query)){
                   die('There was an error running the query [' . $db->error . ']');
        }


        while($row = $statement->fetch_assoc()){
#                return  array ($row);
                if ( $id === $row['tid'] ) {
                        $selected='Selected';
                } else {
                        $selected='';
                }
               echo '<option value=' . $row['tid']. " $selected>" . $row['team_name']. '</option>';
        }

        $statement->free_result();

}

function build($db) {
	$query="SELECT b.prim,c.team_name,a.name, a.phone1, a.phone2 FROM Users a INNER JOIN Teams c ON a.u_tid = c.parent OR a.u_tid = c.tid AND c.tid not in (select parent from Teams Where parent<>0)
		INNER JOIN Cal b ON a.uid = b.c_uid AND  (b.date_start < current_timestamp AND current_timestamp < b.date_end) ORDER BY c.team_name, b.prim desc";
	
	$x=0;
	$y='$one,$two,$three,$four,$five';
	$prep="";
	dbPrep($dbcon,$query,$x,$y,$prep);
	

}



function dbClose() {
	//Close Database
	$db=dbCon();
	$db->close();
}

?>
