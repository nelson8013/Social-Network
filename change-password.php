<?php
include_once './classes/DB.php';
include_once './classes/login.php';

if(Login::isLoggedIn())
{
    //Check if the change-password button is clicked
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        //Grab the input from the fields
        $oldpassword = $_POST['oldpassword'];
        $userid = Login::isLoggedIn();

        //check if the value of the oldpassword is the same as the one on the db
        if(password_verify($oldpassword,DB::query('SELECT password FROM users WHERE id = ?',array($userid ))[0]['password']))
            {
                
            }
            else
            {
                echo "Incorrect Old Password";
            }
    }
}
else
{
    echo "You're Not logged In";
}
?>

<h1>Change Your Password</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <input type="password" name="oldpassword" value="" placeholder="Old Password Here"><p />
    <input type="password" name="newpassword" value="" placeholder="New Password Here"><p />
    <input type="password" name="newpasswordrepeat" value="" placeholder="Repeat New Password Here"><p />
    <input type="submit" name="changepassword" value="Change Password">
    <a type="submit" href="login.php">Login</a><br>
    
</form>