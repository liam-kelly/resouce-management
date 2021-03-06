<?php

//includes
require_once('../path.php');
require_once(ABSPATH.'includes/data.php');
require_once(ABSPATH.'includes/config/settings.php');

//Fetch settings
$set = new settings;
$settings = $set->fetch();

//Get the project list
$dbc = new db;
$dbc->connect();
$q = $dbc->sanitize($_GET['q']);
$projects = $dbc->query("SELECT * FROM projects WHERE project_id LIKE '%".$q."%' LIMIT 0,3");

//Stuff to send with every request
echo '<br />';
echo '<label>Suggestions: </label>';
echo '<br />';
echo '<span class="info">';
if(!(empty($projects))){

    $i = 1;
    foreach($projects as $project){

        echo $project['project_id']." ".$project['title'];

        if(count($projects) > $i){
            echo '<br />';
        }
        $i++;
    }

}else{

    echo 'Could not find project. Click <a href="./?p=project">here</a> to add a project.';

    //Easter eggs
    if($q == 'logo'){

        echo '<br /><img src="/includes/images/logo.gif" />';

    }


}
echo '</span>';
