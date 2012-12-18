<table border="1">
<tr>
	<td><b>Index:</b></td>
	<td><b>Project_ID:</b></td>
	<td><b>Manager:</b></td>
	<td><b>Start_Date:</b></td>
	<td><b>End_Date:</b></td>
	<td><b>Time:</b></td>
	<td><b>Resource:</b></td>
	<td><b>Sales_Status:</b></td>
</tr>
<?php
	include('data.php');
	
	$dbc = new db;
	
	$dbc->connect();
	$result = $dbc->query('SELECT * FROM projects');
	$dbc->close();
	$csv = "Index:,Project_id:,Manager:,Start_Date:,End_Date:,Time:,Resource:,Sales_Status:\r\n";
	foreach($result as $result)
	{

		if($result['sales_status'] == '0'){
			$status = "Opportunity";
		}else{
			$status = "Sold";
		}

		//table view
		echo '<tr>';
		echo '<td>',$result['index'],'</td>';
		echo '<td>',$result['project_id'],'</td>';
		echo '<td>',$result['manager'],'</td>';
		echo '<td>',$result['start_date'],'</td>';
		echo '<td>',$result['end_date'],'</td>';
		echo '<td>',$result['time'],'</td>';
		echo '<td>',$result['resource'],'</td>';
		echo '<td>',$status,'</td>';
		echo '</tr>';

		//csv export
		$csv = $csv.implode(',',$result)."\r\n";

	}

	//csv export
	
	//preg_replace('/,0,/', 'Opportunity', $csv);
	$fp = fopen("data.csv", "w+");
	fwrite($fp, $csv);
	fclose($fp);

?>
<table>
<a href="data.csv">Download csv</a>
