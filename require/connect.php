<?php

    final class DBConn 
    {
        private static $_instance;

        public static function instance() 
        {
            if(!isset(self::$_instance)) 
            {
                self::$_instance = new DBConn();
            }

            return self::$_instance;
        }

        private $_dbconn;

        private function __construct() 
        {
            // On server
            $host = ""; // Removed
            $dbname = ""; // Removed
            $username = ""; // Removed
            $password = ""; // Removed

            try 
            {
                $this->_dbconn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $this->_dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } 
            catch(PDOException $e) 
            {
                die($e->getMessage());
            }
        }

        private function __clone() 
        {
            // Nothing, cannot copy object.
        }

        public function acquire() 
        {
            return $this->_dbconn;
        }
    }

?>