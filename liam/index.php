<?php
	include('data.php');
	
	$dbc = new db;
	$dbc->connect();
	$result = $dbc->query('SELECT * FROM people');
	  

?>
<html>

 <head>
 
  <title>Bluetent Resource Management</title>
  
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  <script src="./js/test.js"></script>
   
  <script>
  $(function() {                                
      $( "#start_date" ).datepicker();
  });
  $(function() {
      $( "#end_date" ).datepicker();
  });
  function test(){
      document.getElementById("end_date").style.display='block'; 
      document.getElementById("test").style.display='block'; 
      document.getElementById("days").style.display='none'; 
      document.getElementById("multi").style.display='none'; 
  }
  </script>

 <head>
 
 <body>
 
  <form action="verifiy.php" method="get" onsubmit="return validate()" name="form">
  
   <fieldset>
   
    <legend>Resource Request Form:</legend>
	
    <span id="error"><label for="sales_status">Sales Status: </label>
	
	 <select name="sales_status">
	  <option value="">Select One:</option>
	  <option value="1">Sold</option>
	  <option value="0">Opportunity</option>
	 </select><b id="error2"></b></span>
	 <br />
	 
    <label for="manager">Project Manager: </label> 
	
	<select name="manager" id="manager">
	  <option value="">Select One:</option>
	  <?php
		foreach($result as $result){
			//print_r($result);
			if($result['type'] == '0' or $result['type'] == '1'){
		 	 echo '<option value="',$result['index'],'">',$result['name'],'</option>';
		 	}
		}
	  ?>
	</select>
	<br />
	
    <span id="error"><label for="project_id">Project ID: </label>
	
	<input type="text" name="project_id" /><b id="error2"></b></span>
	<br />
	
    <label for="resource">Desired Resource: </label>
	
	<select name="resource" id="resource">
	  <option value="">Select One:</option>
	  <?php
	  	$result = $dbc->query('SELECT * FROM people');
	  	foreach($result as $result){
	  		if($result['type'] == '0' or $result['type'] >= '2'){
	  			echo '<option value="',$result['index'],'">',$result['name'],'</option>';
	  		}
		}
		$dbc->close();
	  ?>
	</select>
	<br />
	
    <label for="time">Time Requested: </label>
	
    <select name="time">
	  <option value="">Select One:</option>
	  <option value="00:30">.5</option>
	  <option value="01:00">1</option>
	  <option value="01:30">1.5</option>
	  <option value="02:00">2</option>
	  <option value="02:30">2.5</option>
	  <option value="03:00">3</option>
	  <option value="03:30">3.5</option>
	  <option value="04:00">4</option>
	  <option value="04:30">4.5</option>
	  <option value="05:00">5</option>
	  <option value="05:30">5.5</option>
	  <option value="06:00">6</option>
	  <option value="06:30">6.5</option>
	  <option value="07:00">7</option>
	  <option value="07:30">7.5</option>
	  <option value="08:00">8</option>
	  <option value="08:30">8.5</option>
	  <option value="09:00">9</option>
	  <option value="09:30">9.5</option>
	  <option value="10:00">10</option>
	  <option value="10:30">10.5</option>
	  <option value="11:00">11</option>
	  <option value="11:30">11.5</option>
	  <option value="12:00">12</option>
	  <option value="12:30">12.5</option>
	  <option value="13:00">13</option>
	  <option value="13:30">13.5</option>
	  <option value="14:00">14</option>
	  <option value="14:30">14.5</option>
	  <option value="15:00">15</option>
	  <option value="15:30">15.5</option>
	  <option value="16:00">16</option>
	  <option value="16:30">16.5</option>
	  <option value="17:00">17</option>
	  <option value="17:30">17.5</option>
	  <option value="18:00">18</option>
	  <option value="18:30">18.5</option>
	  <option value="19:00">19</option>
	  <option value="19:30">19.5</option>
	  <option value="20:00">20</option>
	  <option value="20:30">20.5</option>
	</select>
	<br />
	
	<p>
	<label>Start Date:</label><br /><input type="text" id="start_date" name="start_date" /> <label id="days">More than one day?</label> <input type="checkbox" id="multi" onclick="test()"/><br />
  	<label id="test" style="display: none;">End Date:</label><input type="text" id="end_date" name="end_date"  style="display: none;" /><br />
	</p>

	<input type="submit" value="Request" />
	
	<?php
	//Echo out errors for improper data:
	
	//Sales_Status issues
	if(isset($_REQUEST['bool'])){
		echo '<span style="color: red">You must input a valid Sales Status.</span>';
	}
	
	//Project Manager issues
	if(isset($_REQUEST['manager'])){
		echo '<span style="color: red">You must input a valid Manager.</span>';
	}
	
	//Project_id issues
	if(isset($_REQUEST['projectid'])){
		echo '<span style="color: red">You must input a valid Project ID.</span>';
	}
	
	//Project Resource Issues
	if(isset($_REQUEST['resource'])){
		echo '<span style="color: red">You must input a valid Resource.</span>';
	}
	
	//Time issues
	if(isset($_REQUEST['time'])){
		echo '<span style="color: red">You must input a valid Time.</span>';
	}
	
	//No Start Date
	if(isset($_REQUEST['nodate'])){
		echo '<span style="color: red">You must input a Start Date.</span>';
	}
	
	
	?>
	
   </fieldset>
   <a href="list.php">See current resource usage</a>
   
  </form>
  
 </body>

</html>
