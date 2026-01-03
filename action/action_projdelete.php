<?php
    require_once(__DIR__ . "/../require/connect.php");
    require_once(__DIR__ . "/../require/query.php");
    require_once(__DIR__ . "/../require/auth.php");
    require_once(__DIR__ . "/../require/stat.php");

    Auth::gateBy(Auth::PERM_ADMIN);

    Stat::log($_SERVER["SCRIPT_NAME"]);

    CSRF::check();

    $id = $_POST["PROJ_CHOICE"];

    if(isset($id))
    {
        Query::deleteUnique("projection", "PROJ_ID", $id);
    }
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.parent.postMessage("refresh");
    });
</script>