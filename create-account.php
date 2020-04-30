<?php
require_once('classes/DB.php');

//Step1. Check if the button has been clicked and grab the form field values
if(isset($_POST['createaccount'])) 
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email    = $_POST['email'];

    //Step 2. Check if username already exists in Db
    if(!DB::query('SELECT username FROM users WHERE username = ?', array( $username)) ) //Step 2.
    {
        //Step 3. Checks the length of the username chars
        if(strlen($username) >=3 && strlen($username) <=32)
        {
            //Step 4. Sanitize username
            if(preg_match('/[a-zA-Z0-9_]+/', $username))
            {

                //Step 6. Check the length of the password
                if(strlen($password) >=6 AND strlen($password) <=60)
                {
                    //Step 5. Checks if email address is valid
                    if(filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        //check if email doesn't already exists in DB
                        if(!DB::query('SELECT email FROM users WHERE email = ?',array($email )))
                        {

                            //Insert The user into the Database
                            DB::query('INSERT INTO users(username,password,email) VALUES(?, ?, ?)', array( $username,password_hash($password,PASSWORD_BCRYPT),$email));

                            print "Account Created Successfully";
                            
                        }
                        else
                        {
                            echo "Email Already Exists";
                        }
                    } 
                    else
                    {
                        echo "Email is not a valid email address";
                    }//End of Step 5 and email validity check
                }
                else
                {
                    echo "Password Length Must be between 6 and 60 chars long";
                }//End of Step 6 and password length Check
            }
            else
            {
                echo "Username may contain any of small or Capital letters,numbers or underscore";
            }//End of step 4 and Sanitize characters check 
        }
        else
        {
            echo "Username Must be between 3 and 32 characters Long";
        }//End of Step 3 and username length check
    }
    else
    {
        echo "A User with that username already Exists";
    }//End of Step 2 and  check duplicate username else
}//End of isset if() Step 1.
?>

<h1>Register</h1>
<form action="create-account.php" method="post">
    <input type="text" name="username" value="" placeholder="Username Here"><p />
    <input type="password" name="password" value="" placeholder="Password Here"><p />
    <input type="email" name="email" value="" placeholder="Email Here"><p />
    <input type="submit" name="createaccount" value="Create Account">
    <a type="submit" href="login.php">Login</a>
</form>