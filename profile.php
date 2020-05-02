<?php
include_once './classes/DB.php';
include_once './classes/login.php'; 
include_once './classes/leadership.php'; 



$username = "";
$isFollowing = false;
$verified = false;

if($_GET['username'])
{
    if(DB::query("SELECT username FROM users WHERE username = ?",array($_GET['username'])))
    {
        //This gets the username from the users table
            $username = DB::query("SELECT username FROM users WHERE username = ?",array($_GET['username']))[0]['username'];

        //This gets the user id from the users table
            $user_id = DB::query("SELECT id FROM users WHERE username = ?",array( $_GET['username']))[0]['id'];

        //This gets the verified column
          $verified = DB::query("SELECT verified FROM users WHERE username = ?",array($_GET['username']))[0]['verified'];

        //This gets the follower iD
            $follower_id = Login::isLoggedIn();

        //Insert Post Logic
          if(isset($_POST['post']))
          {
            $postbody = $_POST['postbody'];
            $user_id = Login::isLoggedIn();

            DB::query("INSERT INTO posts(body,posted_at,user_id,likes) VALUES(?,NOW(),?,0",array($postbody,$user_id));
          }


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

<h1><?php echo $username; ?>'s Profile <?php if($verified){ echo ' - Verified'; } ?></h1>
<form action="profile.php?username=<?php echo $username; ?>" method="post">
    <?php
      if($user_id != $follower_id)
      {
          if($isFollowing)
          {
          echo '<input type="submit" name="unfollow" value="Unfollow">';
          }
          else
          {
              echo '<input type="submit" name="follow" value="Follow">';
          }

      }
    ?>
</form>

<form action="profile.php?username=<?php echo $username; ?>">
      <textarea name="postbody" cols="80" rows="8">

      </textarea><br><br>
      <input type="submit" name="post" value="Submit Post">
</form>