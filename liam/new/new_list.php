<?php
//Include the data object
include('data.php');

//Force csv download (if applicable)
   $file = "resources.csv";
   if(isset($_REQUEST['csv'])){
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: text/csv"); 
    header("Content-disposition: attachment; filename=\"".$file."\"");
   }else{
?>
<html>
<head>
	<title>Bluetent Resource Management Program</title>
</head>
<body>
<style>
body{
	text-align: left;
}
table{
}
</style>
<?php
   }
   	//preset variables
   	$column = '';
   	$order = '';
   	
   		
	//the base query
	$query = 'SELECT * FROM `test` ORDER BY week_of ASC';
   	
	//Query for a delete
	if(isset($_REQUEST['rm'])){
		
		//Delete query
		$delete = 'DELETE FROM `test` WHERE `index`='.$_REQUEST['rm'];
		
		//Run the query
		$dbc = new db;
		$dbc->connect();
		$project = $dbc->delete($delete);
		$dbc->close();
		
		//redirect to the normal view
		header('Location: ?edit');
		
	}
	
	//connection for projects
	$dbc = new db;
	
	$dbc->connect();
	$project = $dbc->query($query);
	$dbc->close();
	
	//connection for people
	$dbc = new db;
	
	$dbc->connect();
	$result = $dbc->query("SELECT * FROM people ");
	$dbc->close();
	
	//echo out csv if the user is attempting to download it
	if(isset($_REQUEST['csv'])){
		echo $csv;
	}
	
	//fix the $people results to have an index matching that of the database
	$people = array();
	
	foreach($result as $result){
		$people[$result['index']]['index'] = $result['index'];
		$people[$result['index']]['name'] = $result['name'];
		$people[$result['index']]['type'] = $result['type'];
	}

	/*$sort = $project;
	ksort($sort);
	foreach($sort as $key['week_of'] => $val){
		echo '<br /><b>Week of: '.$project['week_of'].'</b><br />';
		?>
		  <table border="1">
  
   <tr>
    <td>Resource</td>
    <td>Manager</td>
    <td>Project id:</td>
    <td>Proirity</td>
    <td>Sunday</td>
    <td>Monday</td>
    <td>Tuesday</td>
    <td>Wednesday</td>
    <td>Thursday</td>
    <td>Friday</td>
    <td>Saturday</td>
    <td>Sales Status</td>
   </tr>
		<?php
	}*/
	$past_week = '';
	$i = '0';
	$sort = $project;
	//echo out the results
	foreach($project as $project)
	{
		//Determine if the week of: label and table header should be spit out
		if(!($past_week == $project['week_of'])){
		echo '<br /><b>Week of: '.$project['week_of'].'</b><br />';
		
		//Spit out the table header
		?>
		<table border="1">
		<tr>
		 <td>Resource</td>
		 <!--<td>Manager</td>-->
		 <td>Project id:</td>
		 <td>Proirity</td>
		 <td>Sunday</td>
		 <td>Monday</td>
		 <td>Tuesday</td>
		 <td>Wednesday</td>
		 <td>Thursday</td>
		 <td>Friday</td>
		 <td>Saturday</td>
		 <td>Sales Status</td>
		</tr>
		<?php
		}

		//make boolean sales_status human readable
		if($project['sales_status'] == '0'){
			$status = "Opportunity";
		}else{
			$status = "Sold";
		}
		
		//make numeric priority human readable
		if($project['priority'] == '0'){
			$priority = "Very High";
		}elseif($project['priority'] == '1'){
			$priority = "High";
		}elseif($project['priority'] == '2'){
			$priority = "Medium";
		}elseif($project['priority'] == '3'){
			$priority = "Low";
		}
		
		//Verify project manager exists
		if(isset($people[$project['manager']])){
			$manager = true;
		}else{
			$manager = false;
		}
		
		//Verify resource exists
		if(isset($people[$project['resource']])){
			$resource = true;
		}else{
			$resource = false;
		}
		
		//unserialize the hours
		$time = unserialize($project['time']);
		
		//table view
		if(!(isset($_REQUEST['csv'])))
		{
			echo '<tr>';
			
			//echo out the resource
			if($resource == true){
				echo '<td>',$people[$project['resource']]['name'],'</td>';
			}else{
				echo '<td><span style="color: red;">[Error]</span></td>';
			}
			
			//echo out the manager(remove?)
			/*if($manager == true){
				echo '<td>',$people[$project['manager']]['name'],'</td>';
			}else{
				echo '<td><span style="color: red;">[Error]</span></td>';
			}*/
			
			//echo out the rest of the table
			echo '<td>',$project['project_id'],'</td>';
			echo '<td>',$priority,'</td>';
			echo '<td>',$time['sunday'],'</td>';
			echo '<td>',$time['monday'],'</td>';
			echo '<td>',$time['tuesday'],'</td>';
			echo '<td>',$time['wednesday'],'</td>';
			echo '<td>',$time['thursday'],'</td>';
			echo '<td>',$time['friday'],'</td>';
			echo '<td>',$time['saturday'],'</td>';
			echo '<td>',$status,'</td>';

			//show delete options if editing is enabled
			if(isset($_REQUEST['edit'])){
				echo '<td><a href="?rm='.$project['index'].'"><img src="./images/x.jpg" height="23" width="32" title="Delete this request"></a></td>';
			}
			
			//Add one to the increment operator
			$j = $i + 1;
			
			//See if another record exists
			if(array_key_exists($j, $sort)){
			
			//If another record exists see if it is of the same week as the current record
			if(!($project['week_of'] == $sort[$j]['week_of'])){
				echo '</tr><table>';
			}
			}else{
				echo '</tr><table>';
			}
			
			//Make the past week equal this week before moving to the next record
			$past_week = $project['week_of'];
			$i++;

		}
	}

?>
<form action="?&" method="get">
	<label><b>Download: </b></label>
	<input type="submit" value="Download as csv" name="csv">
	<label><b>Edit: </b></label>
	<input type="submit" value="<?php if(isset($_REQUEST['edit'])){ echo 'Done'; }else{ echo 'Edit Records'; } ?>" name="<?php if(isset($_REQUEST['edit'])){ echo ''; }else{ echo 'edit'; } ?>">
</form>
</body>
</html>
