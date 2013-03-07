<html>

 <head>
 
 	<title>Bluetent Resource Management: Monthly View</title>
 	
 	<style>
 	body{
 		text-align: center;
 	}
 	table{
 		margin-left: auto;
 		margin-right: auto;
 		border-collapse:collapse;
 	}    
 	</style>
 	
 </head>
 
 <body>

<table border="1">
<?php
//Settings

	//Define Settings Variables
	
	//Colors
	$colors = array(array('color' => 'green', 'low' => '1', 'high' => '15'),
			array('color' => 'yellow', 'low' => '16', 'high' => '30'),
			array('color' => 'orange', 'low' => '31', 'high' => '39'),
			array('color' => 'red', 'low' => '40', 'high' => ''),
			);
	//var_dump($colors);
	
	$color_enable = true;





//Define variables
$hours = '';
$show = '12';

//build a list of weeks

//get the current or last sunday
if(date( "w", date("U")) == '0'){
	$current = date('Y-m-d');
}else{
	$current = date('Y-m-d', strtotime('Last Sunday', time()));
}

$weeks = array();
$weeks[1] = $current;
for($i = 2; $i <= $show; $i++){
	$weeks[$i] = $current = date('Y-m-d',strtotime($current) + (24*3600*7));
}
$count = count($weeks);

//include the data object
include('data.php');

//Connect to the database
$dbc = new db;
$dbc->connect();
	
//Fetch the list of resources
$people = $dbc->query('SELECT * FROM people');	//Get the table of people

//create the table
$i = 0;

//Start building the table
foreach($people as $people)
{
	//Build the table
	$table[$people['index']]['id'] = $people['index'];
	$table[$people['index']]['name'] = $people['name'];
	
	for($i = $count; $i >= 1; $i--){
		
		$table[$people['index']][$i] = '0';
		
	}
	
	//Get the list of projects for each person
	$project = $dbc->query("SELECT * FROM test WHERE resource='".$people['index']."' ");


	//Make sure the person actually has projects
	if(!(empty($project))){
			
		foreach($project as $project){
			
			for($i = $count; $i >= 1; $i--){
				
				if($project['week_of'] == $weeks[$i]){
					
					//Process the hours
					
					//unserialize the hours array
					$time = unserialize($project['time']);
		
					//add everything up
					$hours = $hours + $time['sunday']
							+ $time['monday']
							+ $time['tuesday']
							+ $time['wednesday']
							+ $time['thursday']
							+ $time['friday']
							+ $time['saturday'];
								
					$table[$people['index']][$i] = $hours; 
						
					//empty the hours variable
					$hours = '';
					
				}					
					
			}
				
		}
		
		
	}
	
}
//close the database connection
$dbc->close();

//Echo out the table header
echo "\t".'<tr class="header">'."\r\n\r\n";
echo "\t\t".'<td>Resource: </td>'."\r\n";
foreach($weeks as $weeks){
	echo "\t\t".'<td>'.$weeks.'</td>'."\r\n";
}
echo "\t".'</tr>'."\r\n\r\n";

//echo out each of the rows in the table
foreach($table as $table){
	
	echo "\t".'<tr>'."\r\n";
	echo "\t\t".'<td>'.$table['name'].'</td>'."\r\n";
	
	for($i = 1; $i <= $count; $i++){
		echo "\t\t".'<td>';
		
		if($color_enable == true){
			
		   if($table[$i] == 0) { echo '<span style="background-color: #fff; width: 100%; height: 100%; display: block;">'.$table[$i].'</span>'; }
		   if($table[$i] <= $colors[0]['high'] && $table[$i] >= $colors[0]['low']) { echo '<span style="background-color: '.$colors[0]['color'].'; width: 100%; height: 100%; display: block;">'.$table[$i].'</span>'; }
		   if($table[$i] <= $colors[1]['high'] && $table[$i] >= $colors[2]['low']) { echo '<span style="background-color: '.$colors[1]['color'].'; width: 100%; height: 100%; display: block;">'.$table[$i].'</span>'; }
		   if($table[$i] <= $colors[2]['high'] && $table[$i] >= $colors[2]['low']) { echo '<span style="background-color: '.$colors[2]['color'].'; width: 100%; height: 100%; display: block;">'.$table[$i].'</span>'; }
		   if($table[$i] >= $colors[3]['low']) { echo '<span style="background-color: '.$colors[3]['color'].'; width: 100%; height: 100%; display: block;">'.$table[$i].'</span>'; }
		//if($table[$i] > $colors[3]['high']){ echo '<span style="color: red;">[Error 2]</span>'; } //.$table[$i]; }
		
		}else{
			echo $table[$i];
		}
		echo '</td>'."\r\n";
	}  
	
	echo "\t".'</tr>'."\r\n\r\n";
	
}

	
?>
</table>

  </body>

</html>
