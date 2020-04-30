<?php
//Connect to Db
require_once('classes/DB.php');

//makes the login function available
require_once('classes/login.php');

//Step 1. Check if the user is logged In
if(!Login::isLoggedIn())
{
    echo "<a href='login.php'>Log In</a> <br><br>";
    die("Not Logged In!");
    
}

//Ckecks if the confirm button has been clicked
if(isset($_POST['confirm']))
{
    //if it has, then check if all devices is set(by click), log them out of all devices
    if(isset($_POST['alldevices']))
    {
        DB::query('DELETE FROM login_tokens WHERE user_id = :user_id', array(':user_id' => Login::isLoggedIn()));
    }
    else
    {
        //check if cookie is set. if it is, delete it from the login token
        if(isset($_COOKIE['SNID']))
        {
            //Log out of a specific device, and 
            DB::query('DELETE FROM login_tokens WHERE token = ?',array(sha1($_COOKIE['SNID'])));
        }
        setcookie('SNID','1', time()-3600);
        setcookie('SNID_','1', time()-3600);
    }
}
else
{

}//End of Step 1.


?>

<h1>Logout of your Account</h1>
<p>Are you sure you'd like to logout?</p>
<form action="logout.php" method="post">
    <input type="checkbox" name="alldevices" value="">Logout of All Devices <br>
    <input type="submit" name="confirm" value="Confirm">
</form>