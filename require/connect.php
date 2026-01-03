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

            // Eliminat
            $host = "";
            $dbname = "";
            $username = "";
            $password = "";

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