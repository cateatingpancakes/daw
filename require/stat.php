<?php

    require_once(__DIR__ . "/connect.php");
    require_once(__DIR__ . "/auth.php");

    final class Stat
    {
        private function __construct() 
        {

        }

        public static function log($page)
        {
            $data = Auth::getData();
            $userId = null;

            if(isset($data))
            {
                $userId = $data["id"];
            }

            $ip = $_SERVER["REMOTE_ADDR"];

            Query::recordStat($userId, $ip, $page);
        }
    }

?>
