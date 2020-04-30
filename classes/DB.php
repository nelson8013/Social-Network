<?php

class DB
{
    private static function connect()
    {
        
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=social', 'root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo; //So we can use it else where
    }

    public static function query($query,$params = array())
    {
        //Function to query our Database
        $statement = self::connect()->prepare($query);
        $statement->execute($params);
        if(explode(' ', $query)[0] == 'SELECT')
        {
            $data = $statement->fetchAll();
            return $data;
        }
    }
}
?>