<?php

class Leader
{
    public static function follow($user_id,$follower_id)
    {
        if($user_id !== $follower_id)
        {
        
            //And when the follow button is clicked, check if user B is already following user A
            if(!DB::query("SELECT follower_id FROM followers WHERE user_id = ?",array($user_id)))
            {
                //We'll simply check if a user is followed by verified
                //If they are, we'll verify them
                if($follower_id == 27)
                {
                    DB::query("UPDATE users SET verified=1 WHERE id = ?",array($user_id));
                }
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
        else
        {
            die("You Can't follow yourself");
        }
    }
    

    public static function Unfollow($user_id,$follower_id)
    {
        
        if($user_id !== $follower_id)
        {
            //when the unfollow button is clicked, check if user B is following user A
            if(DB::query("SELECT follower_id FROM followers WHERE user_id = ?",array($user_id)))
            {
                if($follower_id == 27)
                {
                    DB::query("UPDATE users SET verified=0 WHERE id = ?",array($user_id));
                }
                //and if user B is already following user A, 
                //we want to unfollow by deleting user B's user ID from the followers table with the id of user A whom he's following
                DB::query("DELETE FROM followers WHERE user_id = ? AND follower_id = ?",array($user_id,$follower_id));
            } 
           
            $isFollowing = false; 
        
        }
        
    }


}

?>