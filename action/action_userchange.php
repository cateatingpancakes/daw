<?php
    require_once(__DIR__ . "/../require/connect.php");
    require_once(__DIR__ . "/../require/query.php");
    require_once(__DIR__ . "/../require/auth.php");

    $userData = Auth::getData();

    if($userData == null)
    {
        die();
    }

    if(isset($_POST["NEW_REALNAME"]) && $_POST["NEW_REALNAME"] != $userData["realname"])
    {
        CSRF::check();
        Query::setWhere("user", "USER_ID", $userData["id"], "REALNAME", $_POST["NEW_REALNAME"]);
    }

    if(isset($_POST["NEW_USERNAME"]) && $_POST["NEW_USERNAME"] != $userData["username"])
    {
        CSRF::check();
        Query::setWhere("user", "USER_ID", $userData["id"], "USERNAME", $_POST["NEW_USERNAME"]);
    }

    if(isset($_POST["NEW_EMAIL"]) && $_POST["NEW_EMAIL"] != $userData["email"])
    {
        CSRF::check();
        Query::setWhere("user", "USER_ID", $userData["id"], "EMAIL", $_POST["NEW_EMAIL"]);
    }

    if(isset($_POST["NEW_PASSWORD"]) && $_POST["NEW_PASSWORD"] != "")
    {
        CSRF::check();
        $cryptPassword = password_hash($_POST["NEW_PASSWORD"], PASSWORD_BCRYPT);
        Query::setWhere("user", "USER_ID", $userData["id"], "PASSWORD", $cryptPassword);
    }

    if(isset($_POST["NEW_NOSPOOF"]))
    {
        CSRF::check();
        Query::setWhere("user", "USER_ID", $userData["id"], "NOSPOOF", $_POST["NEW_NOSPOOF"]);
    }
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.parent.postMessage("refresh");
    });
</script>