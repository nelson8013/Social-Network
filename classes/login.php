<?php
error_reporting (E_ALL ^ E_NOTICE);
class Login
{
    //create a function that checks if a user is logged in or not
    public static function isLoggedIn()
    {
        // var_dump($_COOKIE);
        //Step 1. Check if the cookie has been set
        if(isset($_COOKIE['SNID']))
        {
            //query the DB and chack if the token is valid
            if( !empty(DB::query('SELECT user_id FROM login_tokens WHERE token = ?', array(sha1($_COOKIE['SNID'])))) )
            {
                $userid = DB::query('SELECT user_id FROM login_tokens WHERE token = ?', array(sha1($_COOKIE['SNID'])))[0]['user_id'];
                
                //Once we know the user is logged in with a valid cookie and we have the user id,
                //We'll check if the second cookie is still set.
                if(isset($_COOKIE['SNID_LONG']))
                {
                    return $userid;
                }
                else
                {
                    //if the cookie isn't set, that's the cookie must have expired
                    //Then we must give them a new token to keep it secure
                    self::refreshTokens($userid);
                    return $userid;
                }
            } else {
                return false;
            }
        } 
        else
        {
           
            return false;
        } //End of Step 1.
    }


    public static function refreshTokens(int $user_id)
    {
        //The token is put in the cookie
            $cstrong = true; //cryptographically secure
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong)); //bin2hex (generates some random bytes)

            //The id query represented by the $user_id variable is on the login.php file which may be here

        //This inserts the new token into OUR DB
        DB::query('INSERT INTO login_tokens(token,user_id) VALUES(?,?)', array(sha1($token), $user_id));

        //This deletes the old token
        DB::query('DELETE FROM login_tokens WHERE token = ?',array(sha1($_COOKIE['SSID'])));

        //This creates a new SSID cookie to replace the old one and
        setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);

        // Then we reset the SSID_ cookiewhich is the cookie that tells the first cookie to expire
        setcookie("SNID_LONG", 1, time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
    }

}


?>