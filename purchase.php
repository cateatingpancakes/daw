<!DOCTYPE html>
<html lang="en">
    <?php
        require_once(__DIR__ . "/require/connect.php");
        require_once(__DIR__ . "/require/query.php");
        require_once(__DIR__ . "/require/auth.php");
        require_once(__DIR__ . "/view/draw.php"); 
        require_once(__DIR__ . "/view/utility.php");
        require_once(__DIR__ . "/mail/class.phpmailer.php");
        require_once(__DIR__ . "/mail/config.php");
        require_once(__DIR__ . "/require/stat.php");

        $status = Auth::captchaStatus();

        if($status == Auth::STAT_FAIL)
        {
            http_response_code(403);
            die();
        }

        Stat::log($_SERVER["SCRIPT_NAME"]);
    ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="css/all.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>

        <title>Proiect DAW - Achiziție bilet</title>
    </head>
    <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Proiect</a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Acasă</a></li>
                        <li class="nav-item"><a class="nav-link" href="movies.php">Filme</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                        <?php Draw::statsLink(); ?>
                        <?php Draw::loginLink(); ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container content text-center">
            <?php
                $userData = Auth::getData();
                $userId = null;

                if(isset($userData))
                {
                    $userId = $userData["id"];
                }

                $projId   = $_POST["PROJ_CHOICE"];
                $ticketId = $_POST["TICKET_CHOICE"];
                $seat     = $_POST["SEAT_CHOICE"];

                if(isset($projId) && isset($ticketId) && isset($seat))
                {
                    Query::createSale($projId, $userId, $ticketId, $seat);
                }

                $ticket = Query::getTicket($projId, $seat);

                $movieName    = $ticket["M_NAME"];
                $movieRuntime = $ticket["M_RUNTIME"];
                $roomName     = $ticket["R_NAME"];
                $ticketType   = $ticket["T_DESC"];
                $ticketPrice  = $ticket["T_PRICE"];
                $saleId       = $ticket["SALE_ID"];
                $saleCode     = $ticket["CHECK_CODE"];
                $projDate     = $ticket["P_DATE"];
                $noCols       = $ticket["N_COLS"];
                $noRows       = $ticket["N_ROWS"];
                $seatRow = rowOf($seat, $noCols);
                $seatCol = colOf($seat, $noCols);

                $projDate = strftime2("d MMMM HH:mm", $projDate);           
                
                include("ticket_exec.php");
                $pdf->Output(__DIR__ . "/pdfdump/$saleCode" . ".pdf", "F");

                $targetEmail = null;
                $nospoof     = null;

                if(isset($_POST["TICKET_EMAIL"]))
                {
                    $targetEmail = $_POST["TICKET_EMAIL"];
                }
                else
                {
                    $sale = Query::getUnique("sale", "SALE_ID", $saleId);
                    $user = Query::getUnique("user", "USER_ID", $sale["USER_ID"]);
                    $targetEmail = $user["EMAIL"];
                    $nospoof     = $user["NOSPOOF"];
                }

                if($targetEmail != null)
                {
                    $whoami = mailcfg\mailcfg::MAIL_WHOAMI;
                    $mailHead = "Bilet cinema proiect DAW";
                    $mailText = "<p>Vă atașăm biletul pe care l-ați achiziționat.</p>";

                    if($nospoof != null)
                    {
                        $mailText .= "<p>Cuvântul tău secret pentru verificarea autenticității acestui mesaj este: <strong>$nospoof</strong></p>";
                    }
                    else
                    {
                        $mailText .= "<p>Nu ai un cuvânt secret setat. Intră în contul tău și setează-ți un cuvânt prin care să poți recunoaște că un mail de la noi este autentic. Dacă nu ai un cont, îți poți crea unul.</p>";
                    }

                    $status = mail2($targetEmail, $mailHead, $mailText, "", [__DIR__ . "/pdfdump/$saleCode" . ".pdf"]);
                    // var_dump($status);
                }
            ?>

            <h2>Biletul tău a fost emis</h2>
            <p>Îl poți vedea pe email sau din cont, dacă ai cont.</p>
        </div>
        
    </body>
</html>
