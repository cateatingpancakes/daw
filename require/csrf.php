<?php

    final class CSRF
    {
        private function __construct() 
        {
            // No constructor, static-method class.
        }

        public static function emit()
        {
            if(session_status() == PHP_SESSION_NONE)
            {
                session_start();
            }

            if(!isset($_SESSION["CSRF"])) 
            {
                $_SESSION["CSRF"] = bin2hex(random_bytes(32));
            }
        }

        public static function input()
        {
            if(isset($_SESSION["CSRF"]))
            {
                echo "<input type=\"hidden\" name=\"CSRF\" value=\"" . htmlspecialchars($_SESSION["CSRF"]) . "\">";
            }
        }

        public static function check()
        {
            if(!isset($_POST["CSRF"]) || !isset($_SESSION["CSRF"]) ||
               !hash_equals($_SESSION["CSRF"], $_POST["CSRF"]))
            {
                http_response_code(403);
                die();
            }
        }
    }

?>