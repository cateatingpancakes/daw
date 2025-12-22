<?php
    require_once(__DIR__ . "/../require/connect.php");
    require_once(__DIR__ . "/../require/query.php");
    require_once(__DIR__ . "/../require/auth.php");

    Auth::gateBy(Auth::PERM_ADMIN);

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