<?php
    require_once(__DIR__ . "/../require/connect.php");
    require_once(__DIR__ . "/../require/query.php");
    require_once(__DIR__ . "/../require/auth.php");
    require_once(__DIR__ . "/../require/stat.php");

    Stat::log($_SERVER["SCRIPT_NAME"]);

    CSRF::check();

    $_SESSION = array();

   if(ini_get("session.use_cookies")) 
    {
        $params = session_get_cookie_params();

        setcookie(session_name(), '', time() - 3600,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
    setcookie("AUTH_SESSION", "", time() - 3600, "/");
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.parent.postMessage("home");
    });
</script>