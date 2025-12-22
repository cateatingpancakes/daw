<?php

    require_once(__DIR__ . "/query.php");
    require_once(__DIR__ . "/csrf.php");

    final class Auth 
    {
        private function __construct() 
        {
            // No constructor, static-method class.
        }

        const PERM_EMPTY = 0b0000000;
        const PERM_USERS = 0b0000001;
        const PERM_ADMIN = 0b0000010;

        const CAPTCHA_PUBLIC = ""; // Removed
        const CAPTCHA_SECRET = ""; // Removed

        const STAT_NONE = 0;
        const STAT_FAIL = 1;
        const STAT_PASS = 2;

        private static $_permCode;
        private static $_userData;

        public static function hasPerm($access) 
        {
            if(isset(self::$_permCode)) 
            {
                return $access & self::$_permCode;
            } 
            else 
            {
                return false;
            }
        }

        public static function getData()
        {
            return self::$_userData;
        }

        public static function captchaStatus()
        {
            if(!isset($_POST["g-recaptcha-response"]))
            {
                return self::STAT_NONE;
            }
            else
            {
                $response = $_POST["g-recaptcha-response"];
                $secretKey = self::CAPTCHA_SECRET;

                $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $response;

                $result = file_get_contents($url);
                $result = json_decode($result);
                $result = json_decode(json_encode($result), true);

                if($result["success"])
                {
                    return self::STAT_PASS;
                }
                else
                {
                    return self::STAT_FAIL;
                }
            }
        }

        private static function loginAs($username, $password) 
        {
            if($record = Query::getUnique("user", "USERNAME", $username)) 
            {
                if(password_verify($password, $record["PASSWORD"])) 
                {
                    self::$_permCode = $record["PERM_CODE"];
                    self::$_userData = [
                        "username" => $record["USERNAME"],
                        "realname" => $record["REALNAME"],
                        "email"    => $record["EMAIL"],
                        "id"       => $record["USER_ID"]
                    ];

                    Query::revokeSession($record["USER_ID"]);
                    $cookie = Query::createSession($record["USER_ID"]);
                    setcookie("AUTH_SESSION", $cookie, time() + 86400);
                }
            }
        }

        private static function recoverSession() 
        {
            if($sessionRecord = Query::getUnique("session", "COOKIE", $_COOKIE["AUTH_SESSION"])) 
            {
                $expiryTime = strtotime($sessionRecord["EXPIRY_DATE"]);
                if(time() < $expiryTime) 
                {
                    if($userRecord = Query::getUnique("user", "USER_ID", $sessionRecord["USER_ID"])) 
                    {
                        self::$_permCode = $userRecord["PERM_CODE"];
                        self::$_userData = [
                            "username" => $userRecord["USERNAME"],
                            "realname" => $userRecord["REALNAME"],
                            "email"    => $userRecord["EMAIL"],
                            "id"       => $userRecord["USER_ID"]
                        ];
                    }
                }
            }
        }

        public static function authRoutine() 
        {
            if(isset($_POST["USERNAME"]) && isset($_POST["PASSWORD"]) && self::captchaStatus() == self::STAT_PASS) 
            {
                $username = $_POST["USERNAME"];
                $password = $_POST["PASSWORD"];
                self::loginAs($username, $password);
                CSRF::emit();
            } 
            else if(isset($_COOKIE["AUTH_SESSION"])) 
            {
                self::recoverSession();
                CSRF::emit();
            }
            else
            {
                self::$_permCode = self::PERM_EMPTY;
                self::$_userData = null;
            }
        }

        public static function createUser($username, $realname, $email, $password, $permCode = self::PERM_EMPTY)
        {
            if($username != "" &&
               $realname != "" &&
               filter_var($email, FILTER_VALIDATE_EMAIL) &&
               $password != "")
            {
                return Query::createUser($username, $realname, $email, $password, $permCode);
            }
        }

        public static function validateUser($confCode, $permCode = self::PERM_USERS)
        {
            if($record = Query::getUnique("user", "CONF_CODE", $confCode))
            {
                $userId = $record["USER_ID"];
                
                Query::setWhere("user", "USER_ID", $userId, "PERM_CODE", $permCode);
                Query::setWhere("user", "USER_ID", $userId, "CONF_CODE", null);
            }
        }

        public static function gateBy($permCode)
        {
            if(!self::hasPerm($permCode))
            {
                http_response_code(403);
                die();
            }
        }
    }

    Auth::authRoutine();
?>