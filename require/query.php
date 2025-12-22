<?php

    require_once(__DIR__ . "/connect.php");

    final class Query
    {

        private function __construct() 
        {
            // No constructor, static-method class.
        }

        private static $_conn;

        private static function failRoutine($e)
        {
            die();
        }

        public static function setConn($conn) 
        {
            self::$_conn = $conn;
        }

        public static function getAll($table) 
        {
            if(isset(self::$_conn)) 
            {
                try 
                {
                    $sql = "SELECT * FROM $table";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute();

                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function getUnique($table, $field, $value) 
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $sql = "SELECT * FROM $table
                            WHERE $field = :value";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "value" => $value
                    ]);

                    return $stmt->fetch(PDO::FETCH_ASSOC);    
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }   
            }
        }

        public static function deleteUnique($table, $field, $value)
        {
            if(isset(self::$_conn))
            {
                try
                {
                    $sql = "DELETE FROM $table
                            WHERE $field = :value";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "value" => $value
                    ]);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function setWhere($table, $idField, $idValue, $field, $value)
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $sql = "UPDATE $table 
                            SET $field = :value
                            WHERE $idField = :id_value";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "value"    => $value,
                        "id_value" => $idValue
                    ]);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function revokeSession($userId) 
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $sql = "DELETE FROM session
                            WHERE USER_ID = :user_id";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "user_id" => $userId
                    ]);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function createSession($userId)
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $cookie = bin2hex(random_bytes(32));
                    $sql = "INSERT INTO 
                            session(COOKIE, USER_ID)
                            VALUES (:cookie, :user_id)";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "cookie"  => $cookie,
                        "user_id" => $userId
                    ]);

                    return $cookie;
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function createUser($username, $realname, $email, $password, $permCode)
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $cryptPassword = password_hash($password, PASSWORD_BCRYPT);
                    $confCode = bin2hex(random_bytes(16));
                    $sql = "INSERT INTO 
                            user(USERNAME, PASSWORD, EMAIL, REALNAME, PERM_CODE, CONF_CODE)
                            VALUES (:username, :password, :email, :realname, :perm_code, :conf_code)";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "username"  => $username,
                        "password"  => $cryptPassword,
                        "email"     => $email,
                        "realname"  => $realname,
                        "perm_code" => $permCode,
                        "conf_code" => $confCode
                    ]);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }

                return $confCode;
            }
        }

        public static function createProjection($movieId, $roomId, $date)
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $sql = "INSERT INTO
                            projection(MOVIE_ID, ROOM_ID, DATE)
                            VALUES (:movie_id, :room_id, :date)";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "movie_id" => $movieId,
                        "room_id"  => $roomId,
                        "date"     => $date
                    ]);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function getProjections($date)
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $sql = "SELECT 
                                P.PROJ_ID AS P_ID,
                                P.DATE    AS P_DATE,
                                M.NAME    AS M_NAME,
                                R.NAME    AS R_NAME,
                                M.RUNTIME AS M_RUNTIME
                            FROM projection P
                            INNER JOIN movie M ON P.MOVIE_ID = M.MOVIE_ID
                            INNER JOIN room R ON P.ROOM_ID = R.ROOM_ID
                            WHERE DATE(P.DATE) = :date";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "date" => $date
                    ]);

                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function getRoom($projId)
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $sql = "SELECT
                                R.N_ROWS AS N_ROWS,
                                R.N_COLS AS N_COLS
                            FROM projection P
                            INNER JOIN room R ON R.ROOM_ID = P.ROOM_ID
                            WHERE P.PROJ_ID = :proj_id";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "proj_id" => $projId
                    ]);

                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function getSales($projId)
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $sql = "SELECT SEAT
                            FROM sale
                            WHERE PROJ_ID = :proj_id";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "proj_id" => $projId
                    ]);

                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function createSale($projId, $userId, $ticketId, $seat)
        {
            if(isset(self::$_conn)) 
            {
                try
                {
                    $checkCode = bin2hex(random_bytes(16));
                    $sql = "INSERT INTO
                            sale(PROJ_ID, USER_ID, TICKET_ID, SEAT, CHECK_CODE)
                            VALUES(:proj_id, :user_id, :ticket_id, :seat, :check_code)";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "proj_id"    => $projId,
                        "user_id"    => $userId,
                        "ticket_id"  => $ticketId,
                        "seat"       => $seat,
                        "check_code" => $checkCode
                    ]);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function getTickets($userId)
        {
            if(isset(self::$_conn))
            {
                try
                {
                    $sql = "SELECT 
                                R.NAME AS R_NAME,
                                R.N_COLS AS N_COLS,
                                R.N_ROWS AS N_ROWS,
                                S.SEAT AS SEAT,
                                S.CHECK_CODE AS CHECK_CODE,
                                S.SALE_ID AS SALE_ID,
                                T.DESC AS T_DESC,
                                T.PRICE AS T_PRICE,
                                M.NAME AS M_NAME,
                                M.RUNTIME AS M_RUNTIME,
                                P.DATE AS P_DATE
                            FROM sale S
                                INNER JOIN ticket_type T ON T.TICKET_ID = S.TICKET_ID
                                INNER JOIN projection P ON P.PROJ_ID = S.PROJ_ID
                                INNER JOIN movie M ON P.MOVIE_ID = M.MOVIE_ID
                                INNER JOIN room R ON P.ROOM_ID = R.ROOM_ID
                            WHERE S.USER_ID = :user_id";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "user_id" => $userId
                    ]);

                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }

        public static function getTicket($projId, $seatCode)
        {
            if(isset(self::$_conn))
            {
                try
                {
                    $sql = "SELECT 
                                R.NAME AS R_NAME,
                                R.N_COLS AS N_COLS,
                                R.N_ROWS AS N_ROWS,
                                S.SEAT AS SEAT,
                                S.CHECK_CODE AS CHECK_CODE,
                                S.SALE_ID AS SALE_ID,
                                T.DESC AS T_DESC,
                                T.PRICE AS T_PRICE,
                                M.NAME AS M_NAME,
                                M.RUNTIME AS M_RUNTIME,
                                P.DATE AS P_DATE
                            FROM projection P
                                INNER JOIN sale S ON P.PROJ_ID = S.PROJ_ID
                                INNER JOIN movie M ON P.MOVIE_ID = M.MOVIE_ID
                                INNER JOIN room R ON P.ROOM_ID = R.ROOM_ID
                                INNER JOIN ticket_type T ON T.TICKET_ID = S.TICKET_ID
                            WHERE P.PROJ_ID = :proj_id AND
                                  S.SEAT = :seat_code";
                    $stmt = self::$_conn->prepare($sql);
                    $stmt->execute([
                        "proj_id"   => $projId,
                        "seat_code" => $seatCode
                    ]);

                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }
                catch(Exception $e)
                {
                    self::failRoutine($e);
                }
            }
        }
    }

    $conn = DBConn::instance()->acquire();
    Query::setConn($conn);

?>
