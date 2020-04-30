<?php
include_once './classes/DB.php';
include_once './classes/login.php';


//check if a user is logged in
if(Login::isLoggedIn())
{
    echo "Logged In!";
    echo Login::isLoggedIn();
}
else
{
    echo "Not Logged In!";
}

?>