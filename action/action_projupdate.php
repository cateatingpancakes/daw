<!DOCTYPE html>
<html lang="en">
    <?php
        require_once(__DIR__ . "/../require/connect.php");
        require_once(__DIR__ . "/../require/query.php");
        require_once(__DIR__ . "/../require/auth.php");
        require_once(__DIR__ . "/../view/draw.php"); 
        require_once(__DIR__ . "/../view/utility.php");

        Auth::gateBy(Auth::PERM_ADMIN);

        if(!isset($_POST["PROJ_CHOICE"]) || $_POST["PROJ_CHOICE"] == "")
        {
            die();
        }

        if(isset($_POST["PROJ_CHOICE"]) && 
           isset($_POST["MOVIE_ID"])    && 
           isset($_POST["ROOM_ID"])     && 
           isset($_POST["DATE"]))
        {
            CSRF::check();
        }
    ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="../css/all.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>

        <title>Proiect DAW - Modificare proiecție</title>
    </head>
    <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Proiect</a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="../index.php">Acasă</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Filme</a></li>
                        <?php Draw::loginLink("../account.php", "../login.php"); ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container content text-center">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-4 col-md-6 text-start">

                    <?php

                        if(isset($_POST["PROJ_CHOICE"]) && 
                           isset($_POST["MOVIE_ID"])    && 
                           isset($_POST["ROOM_ID"])     && 
                           isset($_POST["DATE"]))
                        {
                            echo   "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
                                        <strong>Modificare efectuată!</strong> Poți reveni la pagina principală.
                                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
                                    </div>";
                        }

                    ?>

                    <h2 class="text-center mb-4">Modificare proiecție</h2>

                    <form action="action_projupdate.php" method="POST">
                    
                        <?php
                            CSRF::input();
                        ?>

                        <?php

                            $projId     = $_POST["PROJ_CHOICE"];
                            $oldMovieId = null;
                            $oldRoomId  = null;
                            $oldDate    = null;

                            if($record = Query::getUnique("projection", "PROJ_ID", $projId))
                            {
                                $oldMovieId = $record["MOVIE_ID"];
                                $oldRoomId  = $record["ROOM_ID"];
                                $oldDate    = $record["DATE"];
                            }

                            if(isset($_POST["PROJ_CHOICE"]) && 
                               isset($_POST["MOVIE_ID"])    && 
                               isset($_POST["ROOM_ID"])     && 
                               isset($_POST["DATE"]))
                            {
                                $movieId = $_POST["MOVIE_ID"];
                                $roomId  = $_POST["ROOM_ID"];
                                $date    = $_POST["DATE"];

                                if($movieId != $oldMovieId)
                                {
                                    $oldMovieId = $movieId;
                                    Query::setWhere("projection", "PROJ_ID", $projId, "MOVIE_ID", $movieId);
                                }

                                if($roomId != $oldRoomId)
                                {
                                    $oldRoomId = $roomId;
                                    Query::setWhere("projection", "PROJ_ID", $projId, "ROOM_ID", $roomId);
                                }

                                if($date != $oldDate)
                                {
                                    $oldDate = $date;
                                    $formattedDate = strftime2("yyyy-MM-dd HH:mm:ss", $date);
                                    Query::setWhere("projection", "PROJ_ID", $projId, "DATE", $formattedDate);
                                }
                            }
                        ?>

                        <input type="hidden" name="PROJ_CHOICE" value="<?php echo $projId; ?>">

                        <div class="mb-3">
                            <label for="newMovie" class="form-label">Film nou</label>
                            <select class="form-select" id="newMovie" name="MOVIE_ID">
                                <?php
                                    $movies = Query::getAll("movie");
                                    foreach($movies as $record)
                                    {
                                        $id = $record["MOVIE_ID"];
                                        $name = $record["NAME"];

                                        if($id == $oldMovieId)
                                        {
                                            echo "<option value=\"" . htmlspecialchars($id) . "\" selected>" . htmlspecialchars($name) . "</option>";
                                        }
                                        else
                                        {
                                            echo "<option value=\"" . htmlspecialchars($id) . "\">" . htmlspecialchars($name) . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="newRoom" class="form-label text-start">Sală nouă</label>
                            <select class="form-select" id="newRoom" name="ROOM_ID">
                                <?php
                                    $rooms = Query::getAll("room");
                                    foreach($rooms as $record)
                                    {
                                        $id = $record["ROOM_ID"];
                                        $name = $record["NAME"];

                                        if($id == $oldRoomId)
                                        {
                                            echo "<option value=\"" . htmlspecialchars($id) . "\" selected>" . htmlspecialchars($name) . "</option>";
                                        }
                                        else
                                        {
                                            echo "<option value=\"" . htmlspecialchars($id) . "\">" . htmlspecialchars($name) . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="newDate" class="form-label">Data și ora proiecției</label>
                            <input type="datetime-local" class="form-control" id="newDate" name="DATE" value="<?php 
                                                                                                                echo htmlspecialchars($oldDate); 
                                                                                                               ?>">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Salvează</button>
                        </div>
                        
                        <p class="text-center mt-2"><a href="../index.php">Înapoi la pagina principală</a></p>
                    </form>
                </div>
            </div>
        </div>

    </body>
</html>
