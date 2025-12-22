<?php
    require_once(__DIR__ . "/view/utility.php");

    $movieName    = $_POST["MOVIE_NAME"];
    $movieRuntime = $_POST["MOVIE_RUNTIME"];
    $roomName     = $_POST["ROOM_NAME"];
    $ticketType   = $_POST["TICKET_TYPE"];
    $ticketPrice  = $_POST["TICKET_PRICE"];
    $seatRow      = $_POST["SEAT_ROW"];
    $seatCol      = $_POST["SEAT_COL"];
    $saleCode     = $_POST["SALE_CODE"];
    $projDate     = $_POST["PROJ_DATE"];

    $projDate = strftime2("d MMMM HH:mm", $projDate);

    include("ticket_exec.php");
    $pdf->Output();
?>