<?php
include_once './classes/DB.php';
include_once './classes/login.php'; 
include_once './classes/leadership.php'; 



$username = "";
$isFollowing = false;

if($_GET['username'])
{
    if(DB::query("SELECT username FROM users WHERE username = ?",array($_GET['username'])))
    {
        //This gets the username from the users table
            $username = DB::query("SELECT username FROM users WHERE username = ?",array($_GET['username']))[0]['username'];

        //This gets the user id from the users table
            $user_id = DB::query("SELECT id FROM users WHERE username = ?",array( $_GET['username']))[0]['id'];

        //This gets the follower iD
            $follower_id = Login::isLoggedIn();

        //This follows a user if the follow button is clicked
        if(isset($_POST['follow']))  
        {
          Leader::follow($user_id,$follower_id);
        }
        elseif(isset($_POST['unfollow']))
        {
          Leader::Unfollow($user_id,$follower_id);
        }

        //And when the follow button is clicked, check if user B is alreasy following user A
        if(DB::query("SELECT follower_id FROM followers WHERE user_id = ?",array($user_id)))
        {
            
           //echo "Already Following"; 
           $isFollowing = true; 
        } 
    }
    else
    {
        die("No Such User in our Database");
    }
}


?>

<h1><?php echo $username; ?>'s Profile</h1>
<form action="profile.php?username=<?php echo $username; ?>" method="post">
    <?php
        if($isFollowing)
        {
        echo '<input type="submit" name="unfollow" value="Unfollow">';
        }
        else
        {
            echo '<input type="submit" name="follow" value="Follow">';
        }
    ?>
</form>