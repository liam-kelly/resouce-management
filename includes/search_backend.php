<?php
/**
 * Name:       Resource Management Application Search
 * Programmer: Liam Kelly
 * Date:       8/6/13
 */

//Includes
if(!(defined('ABSPATH'))){
    require_once('../path.php');
}
require_once(ABSPATH.'includes/data.php');

$dbc = new db;
$dbc->connect();
$search = $dbc->sanitize($_REQUEST['q']);

$query = "SELECT * FROM `projects` WHERE `title` LIKE '%".$search."%'  OR `project_id` LIKE '%".$search."%'  OR `description` LIKE '%".$search."%'  ";
$results = $dbc->query($query);

$query_p = "SELECT * FROM `people` WHERE `firstname` LIKE '%".$search."%'  OR `lastname` LIKE '%".$search."%'  OR `email` LIKE '%".$search."%' LIMIT 0,12";
$people = $dbc->query($query_p);

echo '<br />';



echo '<div id="people">';
echo '<b>People:</b><br /><br />';

if(!(empty($people))){

   foreach($people as $person){

       echo '<a href="?p=week&w='.$person['index'].'">'.$person['firstname'].' '.$person['lastname'].'</a><br />';

   }

}else{

    echo 'No people found';

}

echo '</div>';


echo '<div id="projects">';
echo '<br /><b>Projects: </b><br /><br />';

if(empty($results)){


   echo 'No projects found';

}else{

    foreach($results as $result){

        echo '<a href="?p=project&id='.$result['project_id'].'">'.$result['project_id'].' '.$result['title'].'</a><br />';
        echo $result['description'].'<br /><br />';

    }

}

echo '</div>';

//Close the Database connection
$dbc->close();