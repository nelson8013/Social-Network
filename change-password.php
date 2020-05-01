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
        $newpassword = $_POST['newpassword'];
        $newpasswordrepeat = $_POST['newpasswordrepeat'];

        $userid = Login::isLoggedIn();

        //check if the value of the oldpassword is the same as the one on the db
        if(password_verify($oldpassword,DB::query('SELECT password FROM users WHERE id = ?',array($userid ))[0]['password']))
            {
                //check if the new password is the same with the repeat password
                if($newpassword == $newpasswordrepeat)
                {
                    //our validation will only be for the new password only and not the repeat password
                    if(strlen($newpassword) >=6 && strlen($newpassword)<=60)
                    {
                        //Query to update the users password in the users table
                        DB::query("UPDATE users SET password = ? WHERE id = ?", array(password_hash($newpassword,PASSWORD_BCRYPT),$userid));
                        echo "Password Changed Successfully";
                    }
                    else
                    {

                    }
                }
                else
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
else
{
    //check for the token passed to the page
    echo "You're Not logged In";
}
?>

<h1>Change Your Password</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <input type="password" name="oldpassword" value="" placeholder="Old Password Here"><p />
    <input type="password" name="newpassword" value="" placeholder="New Password Here"><p />
    <input type="password" name="newpasswordrepeat" value="" placeholder="Repeat New Password Here"><p />
    <input type="submit" name="changepassword" value="Change Password">
    <a href="login.php">Login</a><br>
    <a href="logout.php">Logout</a><br>
    
</form>