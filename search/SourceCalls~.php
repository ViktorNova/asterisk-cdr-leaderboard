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
//$query = "SELECT lastapp, disposition, count(disposition) FROM $db_name.$db_table_name where DATE(calldate) = '2013-04-19' group by lastapp, disposition";
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
//Prepare header data from database
foreach ($sth as $row) {
	$header[$row['lastapp']][$row['disposition']]['Avg Dur']=1;
	//$header[$row['lastapp']][$row['disposition']]['AvgDur']=1;
}

$query = "SELECT src FROM asteriskcdrdb.cdr where DATE(calldate) = CURDATE() group by src order by src";
//$query = "SELECT src FROM asterisk.cdr where DATE(calldate) = '2013-04-19' group by src order by src";

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
//Prepare Line header array
foreach ($sth as $row) {
	//$data[$row['src']][$header[$row['lastapp']][$row['disposition']]['Avg Dur']]['Total Calls']=0;
	//$data[$row['src']][$header[$row['lastapp']][$row['disposition']]['Avg Dur']]=0;
	
	foreach($header as $hlastappkey=>$hlastappval){
		foreach($hlastappval as $hdispkey=>$hdispval){
			foreach($hdispval as $hdurkey=>$hdurpval){
				//$data[$row['src']][$hlastappkey][$hdispkey]['Avg Dur']['Total Calls']='';
				
				$data[$row['src']][$hlastappkey][$hdispkey]['Total Calls'] = 0 ;
				$data[$row['src']][$hlastappkey][$hdispkey]['Avg Dur'] = 0 ;
				
				$Grdata[$hlastappkey][$hdispkey]['Grand Total Calls'] = 0 ;
			}
		}
	}
}

// Select data from database and add to array

$query = "SELECT * FROM $db_name.$db_table_name where DATE(calldate) = CURDATE() order by src, lastapp, disposition";
//$query = "SELECT * FROM $db_name.$db_table_name where DATE(calldate) = '2013-04-19' order by src, lastapp, disposition";
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
	
	$data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls'] += 1 ;
	$datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] += $row['duration'] ;
	$data[$row['src']][$row['lastapp']][$row['disposition']]['Avg Dur'] = date('i:s', $datacal[$row['src']][$row['lastapp']][$row['disposition']]['Total Dur'] / $data[$row['src']][$row['lastapp']][$row['disposition']]['Total Calls']);
}
//die;
//<link rel="shortcut icon" href="templates/images/favicon.ico" />
?>

<head>
	<title>Wholesale Direct Leads Call Detail Records</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<link rel="stylesheet" href="style/screen.css" type="text/css" media="screen" />
	<link rel="shortcut icon" href="/lazertron/templates/images/WholesalesDirectLeads.bmp" />
</head>
<body>

	<table id="header" border=1>
		<tr>
			<td id="header_logo" align="center">
				<img style="relative; top:0px; float:center;" src="templates/images/WholesalesDirectLeads.png" border=0 id="logo">
			</td>
		</tr>
		<tr>
			<td>
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
	$tophead .='<th colspan='.$colcnt.' style="background-color: #F87431;">'.$hlastappkey.' </th>';
}
?>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<?php echo $tophead;?>
					</tr>
					<tr>
						<th>Source</th>
						<th>Total Calls</th>
						<th>Avg Dur</th>
						<?php echo $bothead;?>
					</tr>
	
<?php				
foreach($data as $srckey=>$srcval){
			if($changebkgr){
			  	$changebkgr= '';
			}else{
				$changebkgr= 'style="background-color: #F87431;"';
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
						<th align="right"><?php echo $Grdata['Grand Total Calls'];?></th>
<?php
	foreach($Grdata as $appkey=>$appval){
		foreach($appval as $dispkey=>$dispval){
			foreach($dispval as $totkey=>$totpval){
				echo '<th align="right">&nbsp;</th>';
				echo '<th align="right">'.$totpval.'</th>';
			}
		}
	}

?>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>

<?php
//foreach($data as $k=>$v){
//	foreach($v  as $l=>$w){
//		foreach($w  as $q=>$y){
//			foreach($y  as $z=>$a){
//				echo $k . " - " . $l . " - " . $q . " - " . $z . " - " . $a ."<br>";
//			}
//		}
//	}
//}





?>
