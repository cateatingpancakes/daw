<!DOCTYPE html>
<html lang="en">
    <?php
        require_once(__DIR__ . "/require/connect.php");
        require_once(__DIR__ . "/require/query.php");
        require_once(__DIR__ . "/require/auth.php");
        require_once(__DIR__ . "/view/draw.php");
        require_once(__DIR__ . "/require/stat.php");

        Stat::log($_SERVER["SCRIPT_NAME"]);
    ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="css/all.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>
        
        <script src="js/select.js" defer></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <title>Proiect DAW - Acasă</title>
    </head>

    <iframe name="action-frame" hidden></iframe>

    <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Proiect</a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#">Acasă</a></li>
                        <li class="nav-item"><a class="nav-link" href="movies.php">Filme</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                        <?php Draw::statsLink(); ?>
                        <?php Draw::loginLink(); ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container content text-center">
            <div class="row">
                <div class="col-md-6 text-start">
                    <h2 class="text-center">Filme disponibile</h2>

                    <?php
                        if((isset($_POST["USERNAME"]) || isset($_POST["PASSWORD"])) && Auth::getData() == null)
                        {
                            echo "<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                                    <strong>Atenție!</strong> Autentificarea a eșuat.
                                    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                                  </div>";
                        }
                    ?>

                    <form method="GET" action="index.php" class="mb-3">
                        <label for="selectDate" class="form-label">Selectează data:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="selectDate" name="MOVIE_DATE" value="<?php echo date("Y-m-d"); ?>" required>
                            <button class="btn btn-outline-secondary" type="submit">Caută</button>
                        </div>
                    </form>

                    <?php
                        $filterBy = date("Y-m-d");
                        if(isset($_GET["MOVIE_DATE"])) 
                        {
                            $filterBy = $_GET["MOVIE_DATE"];
                        }

                        $projections = Query::getProjections($filterBy);

                        if($projections == [])
                        {
                            echo "<p>Azi nu sunt filme.</p>";
                        }

                        $isAdmin = Auth::hasPerm(Auth::PERM_ADMIN);

                        foreach($projections as $record) 
                        {
                            Draw::movieCard($record["P_ID"],
                                            $record["M_NAME"],
                                            $record["M_RUNTIME"],
                                            $record["P_DATE"],
                                            $record["R_NAME"],
                                            $isAdmin);
                        }

                        if($isAdmin)
                        {
                            Draw::movieInsertCard();
                        }

                    ?>

                </div>

                <div class="col-md-6">
                    <div class="border p-4" style="background-color: #f8f9fa;">
                        <p class="text-center fw-bold">ECRAN</p>

                        <?php

                            if(!isset($_GET["PROJ_CHOICE"]))
                            {
                                echo   "<div class=\"p-5 text-muted\">
                                            Aici vor apărea locurile, odată ce alegi o proiecție.
                                        </div>";
                            }
                            else
                            {
                                $projId = $_GET["PROJ_CHOICE"];
                                $room = Query::getRoom($projId);

                                if($room == false)
                                {
                                    echo   "<div class=\"p-5 text-muted\">
                                                Această proiecție nu există sau a fost ștearsă.
                                            </div>";
                                }
                                else
                                {
                                    $noRows = $room["N_ROWS"];
                                    $noCols = $room["N_COLS"];

                                    $taken = [];
                                    $sales = Query::getSales($projId);
                                    foreach($sales as $record)
                                        array_push($taken, $record["SEAT"]);

                                    Draw::movieTable($noRows, $noCols, $taken);
                                }
                            }
                        ?>
                    </div>


                    <?php
                        if(isset($_GET["SEAT_CHOICE"]))
                        {
                            $tickets = Query::getAll("ticket_type");


                            $userData = Auth::getData();
                            $drawCaptcha = false;

                            if($userData == null)
                            {
                                $drawCaptcha = true;
                            }

                            Draw::purchaseBox($tickets, $drawCaptcha);
                        }
                    ?>
                </div>
            </div>
        </div>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                bindSelectData("purchaseTicket", "ticketPrice", "price");
            });

            window.addEventListener("message", function(message) {
                if(message.data == "refresh") {
                    window.location.reload(true);
                }
            });
        </script>
    </body>
</html>
