<?php

// Connecting, selecting database
include ($_SERVER['DOCUMENT_ROOT'] .'/func.php');
$db=dbCon();
 
$field=($_POST["field"]);
$type=($_POST["type"]);
$func=($_POST["func"]);
$prefield=($_POST["prefield"]);
$uid=($_POST["uid"]);
$tid=($_POST["tid"]);
print $_SERVER['HTTP_REFERER']."<br>";
//echo  $_SESSION['previous_location'];

if ( $func == "Update" ) {
	$func=2;
} elseif ( $func == "Delete" ) {
	$func=0;
} elseif ( $func == "Add") {
	echo "moo";
} elseif ( $func == "Search") {
	$func=3;
}
echo "$uid,$tid,$type,$func,$field---";
if ( $type == 1 ) {
	$stype="Users";
	if ( $func == 2 ) {
		$field=($_POST["field1"]);
		$phone1=($_POST["field2"]);
		$phone2=($_POST["field3"]);
		$phone3=($_POST["field4"]);
	} else {
                $phone1=($_POST["phone1"]);
                $phone2=($_POST["phone2"]);
		$phone3=($_POST["phone3"]);
	}

	$order=($_POST["order"]);

	$entryType="uid";
	$insertField="name,phone1,phone2,phone3,u_tid";
	$insertValue="\"$field\",\"$phone1\",\"$phone2\",\"$phone3\",$tid";
	
	$updateFilter="$entryType=$uid";
	$entryOut="name=\"$field\",phone1=\"$phone1\",phone2=\"$phone2\",phone3=\"$phone3\",u_tid=$tid,u_order=$order";

} elseif ( $type == 2) {
        $stype="Cal";

	$result_explode = explode('|', $uid);
	$uid=$result_explode[0];
        $tid=$result_explode[1];
	$call=($_POST["Call"]);

        if ( $func == 2 ) {
                $startDate=($_POST["field3"]);
                $endDate=($_POST["field4"]);
        } else {
                #$call=($_POST["call"]);
                $startDate=($_POST["start"]);
		$endDate=($_POST["end"]);
        }
	$cid=($_POST["cid"]);
	#$query="select tid from Users a, Teams c where a.u_tid=c.tid";

	$start=date("Y-m-d H:i:s", strtotime($startDate));	
	$end=date("Y-m-d H:i:s", strtotime($endDate));

        $insertField="c_uid,c_tid,date_start,date_end,prim";
        $insertValue="$uid,$tid,\"$start\",\"$end\",\"$call\"";
	$entryOut="c_uid=$uid,date_start=\"$start\",date_end=\"$end\",prim=\"$call\"";
	$updateFilter="cid=$cid";
	$query1=$tid;
} elseif ( $type == 3) {
        $stype="Teams";
	$parent=($_POST["parent"]);
        $insertField="team_name,parent";
        $insertValue="\"$field\",$parent";

	$entryOut="team_name=\"$field\",parent=\"$parent\"";
	#$entryValue="\"$field\"";

	#$entryType="tid";
	#$entryValue="\"$tid\"";
	$updateFilter="tid=$tid";
	#$entryOut="$entryType=$entryValue";
} elseif ( $type == 4) {
        $app=($_POST["app"]);
        $owner=($_POST["owner"]);
        $crit=($_POST["crit"]);
        $app_id=($_POST["app_id"]);

        $stype="Apps";
        $entryOut="app_name=\"$app\",a_tid=$owner,crit=$crit";
        $updateFilter="app_id=\"$app_id\"";

        $insertField="app_name,a_tid,crit";
        $insertValue="\"$app\",$owner,$crit";
} else {
        echo "Error: Confused by Type - $type";
        die;
}

$query="";
if ( $func == 0 ) {
	$sfunc="Deleting";
	if ( $type == 1 ) {
		$query="DELETE FROM $stype WHERE uid=$uid";
	} elseif ( $type == 2 ) {
		$query="DELETE FROM $stype WHERE cid=$cid";
	} elseif ( $type == 3) {
		$query="DELETE FROM $stype WHERE $updateFilter AND tid NOT in (select u_tid from Users)";
        } elseif ( $type == 4 ) {
                $query="DELETE FROM $stype WHERE app_id=$app_id";
	} 
	echo "$query";
	#$query="select tid from Teams WHERE team_name like \"%$field%\" AND tid NOT in (select u_tid from Users)";
	$confused="Cannot remove $field, Users are still a part of the group";
} elseif ( $func == 1) {
	$sfunc="Adding";
	$query="INSERT INTO $stype ($insertField) VALUES ($insertValue)";	
	echo "$query";
#	$query1=1;
} elseif ( $func == 2) {
	$sfunc="Modifing";
	$query="Update $stype SET $entryOut WHERE $updateFilter";
	echo "$query";
} elseif ( $func == 3) {
	$sfunc="Search";
	$mydate=($_POST["date"]);
	$teams=($_POST["teams"]);
	$landing=($_POST["landing"]);

	$exploded = explode('|',$field);
	$userID = $exploded[0];
	$teamID = $exploded[1];

	$sDate=date("Y-m-d H:i:s", strtotime($mydate));
	$z=0;
        if ( $teams != NULL ) {
                $teamSearch="a.u_tid=$teams";
                $z++;
        }

	if ( $userID != NULL ) {
		$nameSearch="a.uid=$userID";
		if ( $z == 1 ) {
			$option1="AND";
		}
		$z++;
	}

	if ( $sDate != "1970-01-01 00:00:00"  AND $sDate != NULL ){
		$dateSearch="(b.date_start < \"$sDate\" AND \"$sDate\" < b.date_end)";
		if ( $z == 2 or $z == 1) {
			$option2="AND";
		}
		$z++;
	}

	if ( $nameSearch == NULL AND $sDate == "1970-01-01 00:00:00" AND $teams == NULL) {
				
	} else {
		$query1="select a.name,b.date_start,b.date_end,b.prim,c.team_name FROM Users a, Cal b, Teams c WHERE a.uid=b.c_uid AND a.u_tid=c.tid AND b.date_start > (NOW() - INTERVAL 2 MONTH) AND $teamSearch $option1 $nameSearch $option2 $dateSearch ORDER BY b.date_start DESC";
	}
	if ( $landing == 1 ) {
		$exploded = explode('|',$teams);
	        $userID = $exploded[0];
        	$teamID = $exploded[1];
		$query1="$teamID";
	}
	
	echo "$query1";
} elseif ( $func == 4) {
        $sfunc="Search";
        $mydate=($_POST["date"]);
        $teams=($_POST["teams"]);
        $landing=($_POST["landing"]);

        $exploded = explode('|',$field);
        $userID = $exploded[0];
        $teamID = $exploded[1];

        $sDate=date("Y-m-d H:i:s", strtotime($mydate));
        $z=0;
        if ( $teams != NULL ) {
                $teamSearch="a.u_tid=$teams";
                $z++;
        }

        if ( $userID != NULL ) {
                $nameSearch="a.uid=$userID";
                if ( $z == 1 ) {
                        $option1="AND";
                }
                $z++;
        }

        if ( $sDate != "1970-01-01 00:00:00"  AND $sDate != NULL ){
                $dateSearch="(b.date_start < \"$sDate\" AND \"$sDate\" < b.date_end)";
                if ( $z == 2 or $z == 1) {
                        $option2="AND";
                }
                $z++;
        }

        if ( $nameSearch == NULL AND $sDate == "1970-01-01 00:00:00" AND $teams == NULL) {

        } else {
                #$query1="select a.name,b.date_start,b.date_end,b.prim,c.team_name FROM Users a, Cal b, Teams c WHERE a.uid=b.c_uid AND a.u_tid=c.tid AND $teamSearch $option1 $nameSearch $option2 $dateSearch";
        }
	$query1="$teams";
	$other1="&name=$userID&date=$mydate";

       # if ( $landing == 1 ) {
       #         $exploded = explode('|',$teams);
       #         $userID = $exploded[0];
        #        $teamID = $exploded[1];
         #       $query1="$teamID";
        #}
        echo "$query1";
#	header("Location: ".$redirect.'?Query='.urlencode($query1));
#	header("Location: ".$redirect.$query1);


} else {
	echo "Error: Confused by Func - $func";
	die;
}


#$result = mysql_query($query) or die('Query failed: ' . mysql_error());
echo "<b>";

mysqli_query($db, $query);

if ( mysqli_affected_rows($db) == 0 ) {
        echo "$confused";
} else {
        echo "\t\t$sfunc $field field in $stype.\n";
}


?>
</b><br>
<br>

<?php
// Free resultset
#mysql_free_result($result);

// Closing connection
mysqli_close($db);

$bits = explode('?',$_SERVER['HTTP_REFERER']);
$redirect = $bits[0];
if ( $bits[0] === NULL ) {
	echo "?----".$_SERVER['HTTP_REFERER']."----?";
} else {
	echo "crap-$bits[0] -- $bits[1]";
}
 
header("Location: ".$redirect.'?Query='.urlencode($query1).$other1);

include ($_SERVER['DOCUMENT_ROOT'] .'/templates/header.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/body.html');
include ($_SERVER['DOCUMENT_ROOT'] .'/templates/footer.html');

?>

