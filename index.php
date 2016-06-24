<?php

require_once 'include/config.inc.php';
require_once 'include/functions.inc.php';

// Connect to Database 
try {
	$dbh = new PDO("$db_type:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_pass, $db_options);
}
catch (PDOException $e) {
		echo "\nPDO::errorInfo():\n";
		print $e->getMessage();
		die;
}

// COLUMN 1
// Select data from database and add to array

$query = "SELECT * FROM $db_name.$db_table_name where DATE(calldate) = CURDATE() and CHAR_LENGTH(src) < 5 and src between '200' and '299' order by src, lastapp, disposition";

try {
	$sth = $dbh->query($query);
}
catch (PDOException $e) {
	print $e->getMessage();
	die;
}
if (!$sth) {
	echo "\nPDO::errorInfo():\n";
	print_r($dbh->errorInfo());
	die;
}

//COLUMNS DISPLAYED
//add to array
//$idx=0;
foreach ($sth as $row) {
	$Grdata['Grand Total Calls'] += 1 ;
	
	$data[$row['src']]['src'] =$row['src'] ;
	$data[$row['src']]['Total Calls'] += 1 ;
	$datacal[$row['src']]['Total Dur'] += $row['duration'] ;

//	Total time on the phone	
	$data[$row['src']]['Talk Time'] = date('i:s', $datacal[$row['src']]['Total Dur'] / 1);

//	Unused columns (uncomment to add them back)
//	Average Duration	
//	data[$row['src']]['AvgDur'] = date('i:s', $datacal[$row['src']]['Total Dur'] / $data[$row['src']]['Total Calls']);


//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls'] += 1 ;
//	$datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] += $row['duration'] ;
//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Avg Dur'] = date('i:s', $datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] / $data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls']);

//	Total at bottom
	$Grdata[$row['lastapp']][$row['disposition']]['Grand Total Calls'] += 1 ;

}


// Sort the data with Total Calls descending
// Add $data as the last parameter, to sort by the common key
foreach ($data as $key => $row) {
	$volume[$key]  = $row['Total Calls'];
}
array_multisort($volume, SORT_DESC, SORT_NUMERIC, $data);


?>

<head>
	<title>Today's Call Records</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<link rel="stylesheet" href="style/screen.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="style/displayBoard.css" type="text/css" media="screen" />
	<link rel="shortcut icon" href="templates/images/favicon.ico" />
	<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
</head>
<body>

<h1 class="pageHeader">Today's Call Records</h1>

<!-- COLUMN 1 -->
<table class="callboard" id="callboard_1">

<?php
foreach($header as $hlastappkey=>$hlastappval){
	$colcnt=0;
	foreach($hlastappval as $hdispkey=>$hdispval){
		$colcnt +=1;
		$bothead .='<th>'.$hdispkey.'</th>';
		foreach($hdispval as $hdurkey=>$hdurpval){
			$colcnt +=1;
			$bothead .='<th>'.$hdurkey.'</th>';
		}
	}
	$tophead .='<th colspan='.$colcnt.' class="headers">'.$hlastappkey.' </th>';
}
?>
					<!--<tr>
						<th>Extension</th>
						<th>Total</th>
						<th>Avg</th>
						<?php //echo $tophead;?>
					</tr> 
					-->
					<tr class="tableHeader">
						<th class="extention">Extension</th>
						<th class="total">Calls</th>
						<th class="length">Talk Time</th>
						<?php //echo $bothead;?>
					</tr>
	

<?php				
foreach($data as $srckey=>$srcval){
	if($changebkgr){
	  	$changebkgr= '';
	}else{
		$changebkgr= 'class="oddRows"';
	}
	  
	echo '<tr '.$changebkgr.'>
		<td>'.$srcval['src'].'</td><td align="right">'.$srcval['Total Calls'].'</td>
		<td align="right">'.$srcval['Talk Time'].'</td>';
	foreach($srcval as $appkey=>$appval){
	 foreach($appval as $dispkey=>$dispval){
	  foreach($dispval as $totkey=>$totpval){
	   echo '<td align="right">'.$totpval.'</td>';
		  }
		 }
		}		 
	  echo '</tr>';
}
?>
		<tr>					
		<th>&nbsp;</th>
		<th align="right">Total: <?php echo $Grdata['Grand Total Calls'];?></th>
<?php
	foreach($Grdata as $appkey=>$appval){
		foreach($appval as $dispkey=>$dispval){
			foreach($dispval as $totkey=>$totpval){
//				echo '<th align="right">&nbsp;</th>';
//				echo '<th align="right">'.$totpval.'</th>';
			}
		}
	}

?>
	</tr>
	</table>
	</td>
	</tr>
	</table>
<!--
END COLUMN 1 --------------------------------------------------------------------

COLUMN 2     ------------------------------------------------------------------ -->

<?php 
unset($data);   //clears the data from the last table
unset($Grdata); //clears the Total Calls from the last table

// Select data from database and add to array
$query = "SELECT * FROM $db_name.$db_table_name where DATE(calldate) = CURDATE() and CHAR_LENGTH(src) < 5 and src between '300' and '399' order by src, lastapp, disposition";
//$query = "SELECT * FROM $db_name.$db_table_name where DATE(calldate) = '2016-04-26' and CHAR_LENGTH(src) < 5 and src between '300' and '399' order by src, lastapp, disposition";

try {
	$sth = $dbh->query($query);
}
catch (PDOException $e) {
	print $e->getMessage();
	die;
}
if (!$sth) {
	echo "\nPDO::errorInfo():\n";
	print_r($dbh->errorInfo());
	die;
}

//add to array
foreach ($sth as $row) {
	$Grdata['Grand Total Calls'] += 1 ;
	
	$data[$row['src']]['src'] =$row['src'] ;
	$data[$row['src']]['Total Calls'] += 1 ;
	$datacal[$row['src']]['Total Dur'] += $row['duration'] ;
	
//	Total time on the phone	
	$data[$row['src']]['Talk Time'] = date('i:s', $datacal[$row['src']]['Total Dur'] / 1);

//	Average Duration		
	$Grdata[$row['lastapp']][$row['disposition']]['Grand Total Calls'] += 1 ;
}

// Sort the data with Total Calls descending
// Add $data as the last parameter, to sort by the common key
foreach ($data as $key => $row) {
	$volume[$key]  = $row['Total Calls'];
}
array_multisort($volume, SORT_DESC, SORT_NUMERIC, $data);
?>

<!-- COLUMN 2 -->
<table class="callboard" id="callboard_2">

<?php
foreach($header as $hlastappkey=>$hlastappval){
	$colcnt=0;
	foreach($hlastappval as $hdispkey=>$hdispval){
		$colcnt +=1;
		$bothead .='<th>'.$hdispkey.'</th>';
		foreach($hdispval as $hdurkey=>$hdurpval){
			$colcnt +=1;
			$bothead .='<th>'.$hdurkey.'</th>';
		}
	}
	$tophead .='<th colspan='.$colcnt.' class="headers">'.$hlastappkey.' </th>';
}
?>
					<!--<tr>
						<th>Extension</th>
						<th>Total</th>
						<th>Avg</th>
						<?php //echo $tophead;?>
					</tr> 
					-->
					<tr class="tableHeader">
						<th class="extention">Extension</th>
						<th class="total">Calls</th>
						<th class="length">Talk Time</th>
						<?php //echo $bothead;?>
					</tr>
	
<?php				
foreach($data as $srckey=>$srcval){
			if($changebkgr){
			  	$changebkgr= '';
			}else{
				$changebkgr= 'class="oddRows"';
			}
			  
			  echo '<tr '.$changebkgr.'>
						<td>'.$srcval['src'].'</td><td align="right">'.$srcval['Total Calls'].'</td><td align="right">'.$srcval['Talk Time'].'</td>';
	foreach($srcval as $appkey=>$appval){
		foreach($appval as $dispkey=>$dispval){
			foreach($dispval as $totkey=>$totpval){
				  echo '<td align="right">'.$totpval.'</td>';
			}
		}
	}		 
			  echo '</tr>';
}
?>
					<tr>					
						<th>&nbsp;</th>
						<th align="right">Total: <?php echo $Grdata['Grand Total Calls'];?></th>
<?php
	foreach($Grdata as $appkey=>$appval){
		foreach($appval as $dispkey=>$dispval){
			foreach($dispval as $totkey=>$totpval){
//				echo '<th align="right">&nbsp;</th>';
//				echo '<th align="right">'.$totpval.'</th>';
			}
		}
	}

?>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<!------------------------------------------- -->


</body>
</html>

<?php
//foreach($data as $k=>$v){
//	foreach($v  as $l=>$w){
//	foreach($w  as $q=>$y){
//			foreach($y  as $z=>$a){
//				echo $k . " - " . $l . " - " . $q . " - " . $z . " - " . $a ."<br>";
//			}
//		}
//	}
//}
?>
