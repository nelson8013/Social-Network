<?php
include_once './classes/DB.php';
include_once './classes/login.php';
$tokenIsValid = false;
if (Login::isLoggedIn()) 
{
  //Check if the change-password button is clicked
  if ($_SERVER['REQUEST_METHOD'] == 'POST') 
  {
    //Grab the input from the fields
    $oldpassword = $_POST['oldpassword'];
    $newpassword = $_POST['newpassword'];
    $newpasswordrepeat = $_POST['newpasswordrepeat'];

    $userid = Login::isLoggedIn();

    //check if the value of the oldpassword is the same as the one on the db
    if (password_verify($oldpassword, DB::query('SELECT password FROM users WHERE id = ?', array($userid))[0]['password']))
     {
      //check if the new password is the same with the repeat password
      if ($newpassword == $newpasswordrepeat) 
      {
        //our validation will only be for the new password only and not the repeat password
        if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) 
        {
          //Query to update the users password in the users table
          DB::query("UPDATE users SET password = ? WHERE id = ?", array(password_hash($newpassword, PASSWORD_BCRYPT), $userid));
          echo "Password Changed Successfully";
        } 
        else //if the password legnth is not up to 6 chars
        {
          echo "Password Length should be between 6 and 6o chars Long";
        }
      } 
      else //if the passwords from the two fields do not match
      {
        echo "Passwords don't match";
      }
    } 
    else 
    {
      echo "Incorrect Old Password";
    }
  }
} 
else //If the User is not logged In
{
  //Check if a GET param has been passed in the URL, which is a token
  //If the user isn't logged in, we'll check for the token passed to the page

  //Check if the token is set
  if (isset($_GET['token'])) 
  {
    $token = $_GET['token'];

    //Create a query to check if the token is valid
    if (DB::query("SELECT user_id FROM password_tokens WHERE token = ?", array(sha1($token)))) 
    {
        $user_id = DB::query("SELECT user_id FROM password_tokens WHERE token = ?", array(sha1($token)))[0]['user_id'];

        $tokenIsValid = true;

      //Once we know that the token is valid, we'll allow the user to change their password
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Grab the input from the fields
        $newpassword = $_POST['newpassword'];
        $newpasswordrepeat = $_POST['newpasswordrepeat'];

        //check if the new password is the same with the repeat password
        if ($newpassword == $newpasswordrepeat) 
        {
          //our validation will only be for the new password only and not the repeat password
          if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) 
          {
            //Query to update the users password in the users table
            DB::query("UPDATE users SET password = ? WHERE id = ?", array(password_hash($newpassword, PASSWORD_BCRYPT), $user_id));
            echo "Password Changed Successfully";

            //Once we change the password, we want to delete the token we just created
            DB::query("DELETE FROM password_tokens WHERE user_id = ?",array($user_id));
          } 
          else 
          {
            echo "Password Length should be between 6 and 6o chars Long";
          }
        } 
        else 
        {
          echo "Passwords don't match";
        }
      }
    } else {
      die('Token Invalid');
    }
  } else {
    echo "You're Not logged In";
  }
}
?>

<h1>Change Your Password</h1>
<form action="<?php if(!$tokenIsValid){ echo "change-password.php"; }else{  echo "change-password.php?token=$token"; } ?>" method="POST">
  <?php
  if(!$tokenIsValid)
  {
    echo '<input type="password" name="oldpassword" value="" placeholder="Old Password Here"><p />';
  }
  ?>

  <input type="password" name="newpassword" value="" placeholder="New Password Here"><p />
  <input type="password" name="newpasswordrepeat" value="" placeholder="Repeat New Password Here"><p />

  <input type="submit" name="changepassword" value="Change Password">
  <a href="login.php">Login</a><br>
  <a href="logout.php">Logout</a><br>

</form>