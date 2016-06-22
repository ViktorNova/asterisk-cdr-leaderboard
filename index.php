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

// Select header data from database
$query = "SELECT lastapp, disposition, count(disposition) FROM $db_name.$db_table_name where DATE(calldate) = CURDATE() group by lastapp, disposition";

//Prepare Line header array
foreach ($sth as $row) {
	foreach($header as $hlastappkey=>$hlastappval){
		foreach($hlastappval as $hdispkey=>$hdispval){
			foreach($hdispval as $hdurkey=>$hdurpval){
				$data[$row['src']][$hlastappkey][$hdispkey]['Total Calls'] = 0 ;
				$data[$row['src']][$hlastappkey][$hdispkey]['Avg Dur'] = 0 ;
				$Grdata[$hlastappkey][$hdispkey]['Grand Total Calls'] = 0 ;
			}
		}
	}
}

// COLUMN 1
// Select data from database and add to array

$query = "SELECT * FROM $db_name.$db_table_name where DATE(calldate) = CURDATE() and CHAR_LENGTH(src) < 5 and src between '400' and '500' order by src, lastapp, disposition";
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
	
	$data[$row['src']]['Total Calls'] += 1 ;
	$datacal[$row['src']]['Total Dur'] += $row['duration'] ;
	$data[$row['src']]['AvgDur'] = date('i:s', $datacal[$row['src']]['Total Dur'] / $data[$row['src']]['Total Calls']);
			
	$Grdata[$row['lastapp']][$row['disposition']]['Grand Total Calls'] += 1 ;
	
//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls'] += 1 ;
//	$datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] += $row['duration'] ;
//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Avg Dur'] = date('i:s', $datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] / $data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls']);
}
//die;
//<link rel="shortcut icon" href="templates/images/favicon.ico" />
?>

<head>
	<title>Today's Call Records</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<link rel="stylesheet" href="style/screen.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="style/displayBoard.css" type="text/css" media="screen" />
	<link rel="shortcut icon" href="templates/images/favicon.ico" />
</head>
<body>

				<h1 style="text-align: left; display: block; background: #333; color: #fff; padding-left: .25em;">Today's Call Records</h1>
				<table border=0>
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
						<th class="total">Total Calls</th>
						<th class="length">Avg Length</th>
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
						<td>'.$srckey.'</td><td align="right">'.$srcval['Total Calls'].'</td><td align="right">'.$srcval['AvgDur'].'</td>';
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
						<th align="right">Total Calls: <?php echo $Grdata['Grand Total Calls'];?></th>
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

<div id="column2">

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

// Select header data from database
$query = "SELECT lastapp, disposition, count(disposition) FROM $db_name.$db_table_name where DATE(calldate) = CURDATE() group by lastapp, disposition";

//Prepare Line header array
foreach ($sth as $row) {
	foreach($header as $hlastappkey=>$hlastappval){
		foreach($hlastappval as $hdispkey=>$hdispval){
			foreach($hdispval as $hdurkey=>$hdurpval){
				$data[$row['src']][$hlastappkey][$hdispkey]['Total Calls'] = 0 ;
				$data[$row['src']][$hlastappkey][$hdispkey]['Avg Dur'] = 0 ;
				$Grdata[$hlastappkey][$hdispkey]['Grand Total Calls'] = 0 ;
			}
		}
	}
}

// COLUMN 1
// Select data from database and add to array

$query = "SELECT * FROM $db_name.$db_table_name where DATE(calldate) = CURDATE() and CHAR_LENGTH(src) < 5 and src between '400' and '500' order by src, lastapp, disposition";
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
	
	$data[$row['src']]['Total Calls'] += 1 ;
	$datacal[$row['src']]['Total Dur'] += $row['duration'] ;
	$data[$row['src']]['AvgDur'] = date('i:s', $datacal[$row['src']]['Total Dur'] / $data[$row['src']]['Total Calls']);
			
	$Grdata[$row['lastapp']][$row['disposition']]['Grand Total Calls'] += 1 ;
	
//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls'] += 1 ;
//	$datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] += $row['duration'] ;
//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Avg Dur'] = date('i:s', $datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] / $data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls']);
}
//die;
//<link rel="shortcut icon" href="templates/images/favicon.ico" />
?>

<head>
	<title>Today's Call Records</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<link rel="stylesheet" href="style/screen.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="style/displayBoard.css" type="text/css" media="screen" />
	<link rel="shortcut icon" href="templates/images/favicon.ico" />
</head>
<body>

				<h1 style="text-align: left; display: block; background: #333; color: #fff; padding-left: .25em;">Today's Call Records</h1>
				<table border=0>
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
						<th class="total">Total Calls</th>
						<th class="length">Avg Length</th>
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
						<td>'.$srckey.'</td><td align="right">'.$srcval['Total Calls'].'</td><td align="right">'.$srcval['AvgDur'].'</td>';
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
						<th align="right">Total Calls: <?php echo $Grdata['Grand Total Calls'];?></th>
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
unset($data); //clears the data from the last table

// Select data from database and add to array
$query = "SELECT * FROM $db_name.$db_table_name where DATE(calldate) = CURDATE() and CHAR_LENGTH(src) < 5 and src between '1000' and '2000' order by src, lastapp, disposition";
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
	
	$data[$row['src']]['Total Calls'] += 1 ;
	$datacal[$row['src']]['Total Dur'] += $row['duration'] ;
	$data[$row['src']]['AvgDur'] = date('i:s', $datacal[$row['src']]['Total Dur'] / $data[$row['src']]['Total Calls']);
			
	$Grdata[$row['lastapp']][$row['disposition']]['Grand Total Calls'] += 1 ;
	
//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls'] += 1 ;
//	$datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] += $row['duration'] ;
//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Avg Dur'] = date('i:s', $datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] / $data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls']);
}
//die;
//<link rel="shortcut icon" href="templates/images/favicon.ico" />
?>

<table border=0>
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
						<th class="total">Total Calls</th>
						<th class="length">Avg Length</th>
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
						<td>'.$srckey.'</td><td align="right">'.$srcval['Total Calls'].'</td><td align="right">'.$srcval['AvgDur'].'</td>';
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
						<th align="right">Total Calls: <?php echo $Grdata['Grand Total Calls'];?></th>
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
<







<!------------------------------------------- -->

<img style="float: right;margin-top: 1em;" src="templates/images/SmartChoicePayments.png" id="logo">
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
unset($data); //clears the data from the last table

// Select data from database and add to array
$query = "SELECT * FROM $db_name.$db_table_name where DATE(calldate) = CURDATE() and CHAR_LENGTH(src) < 5 and src between '1000' and '2000' order by src, lastapp, disposition";
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
	
	$data[$row['src']]['Total Calls'] += 1 ;
	$datacal[$row['src']]['Total Dur'] += $row['duration'] ;
	$data[$row['src']]['AvgDur'] = date('i:s', $datacal[$row['src']]['Total Dur'] / $data[$row['src']]['Total Calls']);
			
	$Grdata[$row['lastapp']][$row['disposition']]['Grand Total Calls'] += 1 ;
	
//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls'] += 1 ;
//	$datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] += $row['duration'] ;
//	$data[$row['src']][$row['lastapp']][$row['disposition']]['Avg Dur'] = date('i:s', $datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] / $data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls']);
}
//die;
//<link rel="shortcut icon" href="templates/images/favicon.ico" />
?>

<table border=0>
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
						<th class="total">Total Calls</th>
						<th class="length">Avg Length</th>
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
						<td>'.$srckey.'</td><td align="right">'.$srcval['Total Calls'].'</td><td align="right">'.$srcval['AvgDur'].'</td>';
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
						<th align="right">Total Calls: <?php echo $Grdata['Grand Total Calls'];?></th>
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
<







<!------------------------------------------- -->

<img style="float: right;margin-top: 1em;" src="templates/images/SmartChoicePayments.png" id="logo">
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
