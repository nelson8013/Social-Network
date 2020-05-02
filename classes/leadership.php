<?php

class Leader
{
    public static function follow($user_id,$follower_id)
    {
        //Check if the form is being submitted
        
            //And when the follow button is clicked, check if user B is already following user A
            if(!DB::query("SELECT follower_id FROM followers WHERE user_id = ?",array($user_id)))
            {
                //and if user B is not already following user A, 
                //we want to follow by inserting user B's user ID into the followers table showing the id of user A whom he's following
                DB::query("INSERT INTO followers(user_id,follower_id) VALUES(?,?)",array($user_id,$follower_id));
            } 
            else
            {
               echo "Already Following"; 
            } 
            $isFollowing = true; 
        }
    

    public static function Unfollow($user_id,$follower_id)
    {
        
            //when the unfollow button is clicked, check if user B is following user A
            if(DB::query("SELECT follower_id FROM followers WHERE user_id = ?",array($user_id)))
            {
                //and if user B is already following user A, 
                //we want to unfollow by deleting user B's user ID from the followers table with the id of user A whom he's following
                DB::query("DELETE FROM followers WHERE user_id = ? AND follower_id = ?",array($user_id,$follower_id));
            } 
           
            $isFollowing = false; 
        
    }

}

?>