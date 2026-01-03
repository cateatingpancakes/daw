<?php
    require_once(__DIR__ . "/../require/connect.php");
    require_once(__DIR__ . "/../require/query.php");
    require_once(__DIR__ . "/../require/auth.php");
    require_once(__DIR__ . "/../require/stat.php");

    Auth::gateBy(Auth::PERM_ADMIN);

    Stat::log($_SERVER["SCRIPT_NAME"]);

    CSRF::check();

    $movieId = $_POST["MOVIE_ID"];
    $roomId  = $_POST["ROOM_ID"];
    $date    = $_POST["DATE"];
    $time    = $_POST["TIME"];

    $fullDate = $date . " " . $time;

    Query::createProjection($movieId, $roomId, $fullDate);
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.parent.postMessage("refresh");
    });
</script>