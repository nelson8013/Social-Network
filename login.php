<?php
require_once('classes/DB.php');
require_once('classes/login.php');

//Step 1. Check if form has been submitted
if(isset($_POST['login']))
{
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  //Step 2 . To log in, we need to check if the username exists in DB by querying the DB
  if(DB::query('SELECT id FROM users WHERE username = ?', array($username)))
  {
    $user_id = DB::query('SELECT id FROM users WHERE username = ?', array($username))[0]['id'];
    //Step 3. Check if the password provided matches the hashed version in our DB
    if(password_verify($password, DB::query('SELECT password FROM users WHERE username = ?', array($username))[0]['password']))
    {
      echo "Welcome $username";
      Login::refreshTokens($user_id);
    }
    else
    {
      echo "Incorrect Password";
    }//End of Step 3 and password match check
  }
  else
  {
    echo "User is not Registered"; 
  }//End of Step 2. and checking if the username exist in DB
}//End of Step 1.
?>

<h1>Login to your Account</h1>
<form action="login.php" method="post">
<input type="text" name="username" value="" placeholder="Username Here"><p />
<input type="password" name="password" value="" placeholder="Password Here"><p />
<input type="submit" name="login" value="Login">
<a href="create-account.php" >Create Account</a>
<a href="logout.php" >Logout</a>
<a type="submit" href="change-password.php">Change Password</a>
<a type="submit" href="forgot-password.php">Forgot Password</a>

</form>