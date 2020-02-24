<?php

class Database
{
    //initialise variable
    private static $dbHost = 'localhost';
    private static $dbName = 'burgercode_fini';
    private static $dbUser = 'root';
    private static $dbUserPassword = '';
    private static $connection = null;

    // fonction that return  connection
    public static function connect()
    {
        try {
            self::$connection = new PDO('mysql:host='.self::$dbHost.';dbname='.self::$dbName, self:: $dbUser, self:: $dbUserPassword);
            self:: $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Database connection error: '.$e->getMessage();
            exit;
        }

        return self::$connection;
    }

    // function to destroy connection
    public static function disconnect()
    {
        self::$connection = null;
    }
}

Database::connect();
