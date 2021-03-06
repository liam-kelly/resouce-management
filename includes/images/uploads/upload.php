<?php
/**
 * Name:       resouce-management     
 * Programmer: liam
 * Date:       7/31/13
 */
//Start the user's session
if(!(isset($_SESSION))){
    session_start();
}

//Includes
require_once('../../../path.php');
require_once(ABSPATH.'includes/config/users.php');

//Define the user to deal with
if(isset($_SESSION['user_lookup'])){
    $userid = $_SESSION['user_lookup'];
}else{
    $userid = $_SESSION['userid'];
}




if(isset($_REQUEST['delete'])){

    //Delete the users profile picture instead if requested
    $users = new users;
    $users->select($userid);
    $path = ABSPATH.'includes/images/uploads/'.$users->profile_pic;
    echo 'Deleting: '.$path;
    unlink($path);
    $users->change('profile_pic', '');
    $users->update();
    header('location: ../../../?p=edit_pic');

}else{

    if(isset($_FILES)){
var_dump($_FILES);
        if($_FILES["file"]["size"] <= 8388608){

            if(!(file_exists($_FILES["file"]["name"]))){

                switch($_FILES["file"]["type"]){

                    case "image/png":
                        goto a;
                    break;

                    case "image/jpeg":
                        goto a;
                    break;

                    case "image/bmp":
                        goto a;
                    break;

                    case "image/gif":
                        goto a;
                    break;

                    default:
                        header('location: ../../../?p=edit_pic&wrong_type');
                        goto b;
                    break;

                }

                a:
                //No file exist by this name (yet) so save the upload
                move_uploaded_file($_FILES["file"]["tmp_name"], './'.$_FILES["file"]['name']);

                //This will allow us to delete the file later
                chmod('./'.$_FILES["file"]['name'], 777);

                //Change the users profile picture to this pic
                $users = new users;
                $users->select($userid);

                $users->change('profile_pic', $_FILES["file"]['name']);

                $users->update();

                //echo
                echo 'Upload success';

            }else{

                //There is already a file by this name
                echo "A file already exists by this name on the server";
                header('location: ../../../?p=edit_pic&wrong_type');
            }

        }else{

            //File is to big
            echo 'The file is to big, please upload a file less than 8 mb.';
            header('location: ../../../?p=edit_pic&wrong_type');

        }

    }

    b:

    header('location: ../../../?p=edit_pic');

}


