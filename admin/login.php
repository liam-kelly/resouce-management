<?php
/*
 * Name: Admin Login
 * Programmer: Liam Kelly
 * Date: 5/9/13
 */

//start the session
session_start();

//includes
require_once('../path.php'); //to set the ABSPATH constant
require_once(ABSPATH.'includes/data.php');
require_once(ABSPATH.'includes/config/settings.php');
require_once(ABSPATH.'includes/config/users.php');

//fetch the salt
$set = new settings;
$settings = $set->fetch();
$salt = $settings['salt'];

//Setup the users class
$users = new users;



if(isset($_REQUEST['auth_code'])){

    //Verify the authentication code
    if(strtoupper($_REQUEST['auth_code']) == strtoupper($_SESSION['auth_code'])){

        //The user has entered a good code proceed to normal login
        unset($_SESSION['auth_code']);
        if(isset($_SESSION['badcode'])){
            unset($_SESSION['badcode']);
        }

        //Look up the user (again)
        $results = $users->select($_SESSION['ref']);

        $_SESSION['userid'] = $results[0]['index'];
        $_SESSION['name'] = $results[0]['firstname'];
        $_SESSION['admin'] = $results[0]['admin'];
        $_SESSION['security_class'] = $results[0]['security_class'];
        $_SESSION['colorization'] = $results[0]['colorization'];
        $_SESSION['beta'] = '';

        unset($_SESSION['ref']);

        header('location: ../?p=home');

    }else{

        //The code is bad
        $_SESSION['badcode'] = true;
        header('location: ../?p=login');


    }



}elseif(isset($_REQUEST['username']) && isset($_REQUEST['password']))
{

    error_reporting(E_STRICT);

    //Make sure both fields are NOT empty
    if(!(empty($_REQUEST['username'])) && !(empty($_REQUEST['password']))){

        $results = $users->login($_REQUEST['username'], $_REQUEST['password']);

        if(!($results == FALSE)){

            //Make sure user is not locked indefinitely
            if($results[0]['lock_start'] > '0000-00-00' && $results[0]['lock_end'] == '0000-00-00'){

                //Check to see if the lock has started or not
                if(!($results[0]['lock_start'] > date('Y-m-d', time()))){

                    //Block user (lock has started)
                    header('location: ../?p=banned');

                    //For logging purposes
                    $banned = true;

                }


            }

            //Logging
            if($settings['logging'] == true){

                $ip = $_SESSION['REMOTE_ADDR'];

            }

            //Deal with Two factor authentication
            if($settings['IIstep'] == true){


                //Good login
                $_SESSION['ref'] = $results[0]['index'];

                header('location: ../includes/twofactor/twofactor.php');

            }else{

                //Good login
                $_SESSION['userid'] = $results[0]['index'];
                $_SESSION['name'] = $results[0]['firstname'];
                $_SESSION['admin'] = $results[0]['admin'];
                $_SESSION['security_class'] = $results[0]['security_class'];
                $_SESSION['colorization'] = $results[0]['colorization'];

                //Redirect to home
                header('location: ../?p=home');

            }




            //In case of time out
            if(isset($_SESSION['timeout'])){
                unset($_SESSION['timeout']);
                unset($_SESSION['timestamp']);
            }


        }else{

            //Bad login
            header('location: ../?p=badlogin');

        }

    }else{

        //One or more fields is empty
        header('location: ../?p=badlogin');

    }

}
else
{

    //Both fields do not exist
    header('location: ../?p=badlogin');

}
