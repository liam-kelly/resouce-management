<?php

//Start the users session (if not set)
session_start();

//includes
if(!(defined('ABSPATH'))){
    require_once('../path.php');
}
require_once(ABSPATH.'includes/data.php');
require_once(ABSPATH.'includes/config/settings.php');
require_once(ABSPATH.'includes/config/users.php');

//Fetch values to populate fields
$set = new settings;
$settings = $set->fetch();

//make sure the user is logged in and is admin
if(isset($_SESSION['userid'])){
    if($_SESSION['admin'] >= 1){

        //User is admin

        ?>
        <div id="admin_head">
            <h3>Administrative Settings:</h3>
            <ul>
                <li><a href="./?p=admin&amp;a=status">System Status</a></li>
                <li><a href="./?p=admin&amp;a=general">General</a></li>
                <?php if($_SESSION['admin'] >= '2'){ ?>
                    <li><a href="./?p=admin&amp;a=security">Security</a></li>
                <?php } ?>
                <?php if($_SESSION['admin'] >= '2'){ ?>
                    <li><a href="./?p=admin&amp;a=internal">Internal</a></li>
                <?php } ?>
                <li><a href="./?p=admin&amp;a=users">Users</a></li>
                <?php if($settings['debug'] == true && $_SESSION['admin'] >= '2'){ ?>
                    <li><a href="./?p=debug">Debug</a></li>
                <?php } ?>
            </ul>
            <?php
            if($_SESSION["saved"] == "1"){
                echo '<span class="success">Settings saved successfully!</span>';
                unset($_SESSION["saved"]);
            }elseif($_SESSION["saved"] == "0"){
                echo '<span class="error">Settings could not be saved!</span>';
                unset($_SESSION["saved"]);
            }
            ?>
        </div>
        <?php
        if(isset($_SESSION['a'])){

        $admin = $_SESSION['a'];

        }else{

        $admin = '';

        }

        switch($admin){

            CASE "status":
                $page = 'admin/menus/status.php';
                $id = 'status';
            break;

            CASE "general":
                $page = 'admin/menus/main.php';
            break;

            CASE "security":
                $page = 'admin/menus/security.php';
            break;


            CASE "internal":
                $page = 'admin/menus/internal.php';
            break;

            CASE "users":
                $page = 'admin/menus/new_users.php';
            break;

            CASE "debug":
                $page = 'admin/menus/debug.php';
            break;

            DEFAULT:
                //Just show the status page
                $page = 'admin/menus/status.php';
                $id = 'status';
            break;

        }

        if(isset($id)){
            echo '<div id="'.$id.'" class="admin-menu">';
        }
        if(!(empty($page))){
            //show the requested page
            include_once(ABSPATH.$page);
        }
        if(isset($id)){
            echo '</div>';
        }

    }else{

        //User is not admin
        ?><span class="error">You must be an administrator to access this page.</span><?php

    }

}else{

    //User is not logged in
    ?><span class="error">You are not logged in, please login to access this page.</span><?php

}

?>