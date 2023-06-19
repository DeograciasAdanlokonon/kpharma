<?php

  //Mysql method
 /*  $server = "localhost";
  $username = "root";
  $password = "";
  $db = "kpharma";
  $mysqli = mysqli_connect($server,$username, "", $db); */

  /* $server = "db4free.net";
  $username = "kpharma";
  $password = "A#258pivoiture";
  $db = "kpharma";
  $mysqli = mysqli_connect($server,$username, $password, $db);
 */

 class Database
    {
        // //LOCALHOST DATA
        private static $dbHost = "localhost";
        private static $dbName = "kpharma";
        private static $dbUser = "root";
        private static $dbPassword ="";

        /* private static $dbHost = "db4free.net";
        private static $dbName = "kpharma";
        private static $dbUser = "kpharma";
        private static $dbPassword ="A#258pivoiture"; */

        private static $connection = null;

        public static function connect()
        {
            try 
            {
                self::$connection = new PDO("mysql:host=" .self::$dbHost.";dbname=" .self::$dbName, self::$dbUser, self::$dbPassword);
            } 
            catch (PDOException $e) 
            {
                echo 'Echec de connexion à la base de données:' .$e->getMessage();
            }
            return self::$connection;
        }

        public static function disconnect()
        {
            self::$connection = null;
        }
    }
    
   Database::connect();


?>