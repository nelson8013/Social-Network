<?php
include_once './classes/DB.php';

//Check if the form hass been submitted
if(isset($_POST['resetpassword']))
{
            $cstrong = true; //cryptographically secure
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong)); //bin2hex (generates some random bytes)
            $email = $_POST['email']; //Email from the user

            //user_id is stored in the psw tokens tbl and we need to get it from the users table
            $user_id = DB::query("SELECT id FROM users WHERE email = ?",array($email))[0]['id'];

            //Query The Db and insert the token into the tokens table
            DB::query("INSERT INTO password_tokens(token,user_id) VALUES(?,?)",array(sha1($token),$user_id));
            
            echo "Email Sent";
            echo '<br>';
            echo $token;
            // 
}


?>
<h1>Forgot Password</h1>
    <form action="" method="POST">
        <input type="text" name="email" value="" placeholder="Email..."><p />
        <input type="submit" name="resetpassword" value="Reset Password">
        <a href="change-password.php"></a>
    </form>