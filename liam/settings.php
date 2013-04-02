<?php

/*
	Name: Settings
	Description: Allows the user to change settings for the resource tracker
	Programmer: Liam Kelly
	Last Modified: 02/26/13	
*/

//include the data object
include_once('data.php');

//settings fetch function
function fetch($id){
	
	$dbc = new db;						//set up object
	$dbc->connect();					//connect using defaults
	$id = $dbc->sanitize($id);				//sanitize the inputs
	$query = "SELECT * FROM settings WHERE id='$id'";	//define the query
	$results = $dbc->query($query);				//run the query
	$dbc->close();						//close the database connection

	return unserialize($results[0]['value']);


}

//settings creation function

function create($name, $value, $comments){
	
	$dbc = new db;						//set up object
	$dbc->connect();					//connect using defaults

	//sanitize the inputs
	$name 	  = $dbc->sanitize($name);
	$value 	  = $dbc->sanitize($value);
	$comments = $dbc->sanitize($comments);

	//define the query
	$query = "INSERT INTO `resources`.`settings` (`id`, `name`, `value`, `comments`) VALUES (NULL, '".$name."', '".$value."', '".$comments."')";
	echo $query;
	$result = $dbc->insert($query);				//run the query
	$dbc->close();						//close the database connection

	return $result;

}

//Settings update function
function update($id, $name, $value, $comments){
	
	//setup database connect
	$dbc = new db;
	$dbc->connect();
	
	//sanitize the inputs
	$name 	  = $dbc->sanitize($name);
	$value 	  = $dbc->sanitize($value);
	$comments = $dbc->sanitize($comments);
	$id	  = $dbc->sanitize($id);
	
	//define the query
	$dbc->insert("UPDATE settings SET name='".$name."' WHERE id='".$id."'");
	$dbc->insert("UPDATE settings SET value='".$value."' WHERE id='".$id."'");
	$dbc->insert("UPDATE settings SET comments='".$comments."' WHERE id='".$id."'");
	
	//close the database connection
	$dbc->close();
	
	return;

}

?>

<!--
$query = "UPDATE settings SET name='".$name."' and value='".$value."' and comments='".$comments."' WHERE id='".$id."' ";
	echo $query;
-->