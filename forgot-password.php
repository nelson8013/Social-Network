<?php
include_once './classes/DB.php';

//Check if the form hass been submitted
if(isset($_POST['resetpassword']))
{
            $cstrong = true; //cryptographically secure
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong)); //bin2hex (generates some random bytes)
            $email = $_POST['email'];
            $user_id = DB::query("SELECT id FROM users WHERE email = ?",array($email))[0]['id'];
            DB::query("INSERT INTO password_tokens(token,user_id) VALUES(?,?)",array(sha1($token),$user_id));
            echo "Email Sent";
            //This is to mock the send mail() fnc in php
}


?>
<h1>Forgot Password</h1>
    <form action="" method="POST">
        <input type="text" name="email" value="" placeholder="Email..."><p />
        <input type="submit" name="resetpassword" value="Reset Password">
    </form>