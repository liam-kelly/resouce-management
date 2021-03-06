<?php
/**
 * Name:       Reset code generator
 * Programmer: Liam Kelly
 * Date:       7/15/13
 */

//includes
//require_once('../path.php');
require_once(ABSPATH.'includes/config/settings.php');
require_once(ABSPATH.'includes/data.php');
require_once(ABSPATH.'includes/config/users.php');


function create_reset_code(){

    //The following based off of http://stackoverflow.com/questions/853813/how-to-create-a-random-string-using-php

    //Create the empty rest code
    $reset_code = '';

    //Length of the reset code
    $length = '10';

    //Number of possible characters for the reset code
    $num_chars = '62';

    //Well use the entire alphabate in lower and upper case
    $valid_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    //The above should give us 1e+62 possible comibinations

    for($i = 0; $i < $length; $i++ ){

        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $reset_code .= $random_char;

    }
    return $reset_code;

}

function create_auth_code(){

    //The following based off of http://stackoverflow.com/questions/853813/how-to-create-a-random-string-using-php

    //Create the empty rest code
    $reset_code = '';

    //Length of the reset code
    $length = '6';

    //Number of possible characters for the reset code
    $num_chars = '34';

    //Well use the entire alphabet in upper case and digits 1-9 (excludes 0 and o)
    $valid_chars = '123456789abcdefghijklmnpqrstuvwxyz';

    for($i = 0; $i < $length; $i++ ){

        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $reset_code .= $random_char;

    }
    return $reset_code;

}


function insert_reset_code($userid){

        a:

        //Generate Reset Code
        $reset_code = create_reset_code();

        //Make sure the reset code does not exist already in the database
        $dbc = new db;
        $dbc->connect();
        $results = $dbc->query('SELECT * FROM people WHERE reset_code = \''.$reset_code.'\'');
        $dbc->close();

        var_dump($results);

        if(!(count($results) == '0')){
            //goto a;
        }


    //Set user's reset code
    $users = new users;
    $users->select($userid);
    $users->change('reset_code', $reset_code);
    $users->update();
    return $reset_code;
}

