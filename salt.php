<?php
/**
 * Name: Salt Generator
 * Programmer: Liam Kelly
 * Date: 7/8/13
 */

//includes
include_once('path.php');


//salt unhashed
$salt_raw = 'This is a salt.';

//hash the salt
$salt = '60b448a4b93f07d724baecc1975b00e4b822efa4f6cb997ae0ec92f9f3580e981fe1d7f56f356d16f1451565fcf39929b0c157206fc9522cdc0caefc7b1945d2';//hash('SHA512', $salt_raw);

//echo the salt
echo 'Salt: '.$salt.'</br>';

//salt and hash a password
$password_raw = '';


echo 'Password: '.hash('SHA512', $password_raw.$salt).'<br />';


