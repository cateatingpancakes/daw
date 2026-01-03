<?php
    require_once(__DIR__ . "/../require/connect.php");
    require_once(__DIR__ . "/../require/query.php");
    require_once(__DIR__ . "/../require/auth.php");
    require_once(__DIR__ . "/../require/stat.php");

    Stat::log($_SERVER["SCRIPT_NAME"]);

    $name  = null;
    $clob  = $_POST["MESSAGE"];
    $email = $_POST["EMAIL"];

    if(isset($_POST["NAME"]))
    {
        $name = $_POST["NAME"];
    }

    Query::addContact($name, $email, $clob);
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.parent.postMessage("home");
    });
</script>