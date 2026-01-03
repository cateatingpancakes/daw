<!DOCTYPE html>
<html lang="en">
    <?php
        require_once(__DIR__ . "/require/connect.php");
        require_once(__DIR__ . "/require/query.php");
        require_once(__DIR__ . "/require/auth.php");
        require_once(__DIR__ . "/view/draw.php"); 
        require_once(__DIR__ . "/require/stat.php");

        $userData = Auth::getData();

        if($userData == null)
        {
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

        <title>Proiect DAW - Contul meu</title>
    </head>

    <iframe name="action-frame" hidden></iframe>

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
            <div class="row mt-5">
                <div class="col-md-6 text-start border-end">

                    <?php 
                        $userData = Auth::getData(); 
                    ?>

                    <h3 class="mb-4">Bună, <?php echo htmlspecialchars($userData["realname"]); ?></h3>
                
                    <form action="action/action_userchange.php" target="action-frame" method="POST">
                        <?php
                            CSRF::input();
                        ?>

                        <div class="mb-3">
                            <label for="accountRealname" class="form-label">Nume și prenume</label>
                            <input type="text" class="form-control" id="accountRealname" name="NEW_REALNAME" placeholder="John Doe" value="<?php 
                                                                                                                                              echo htmlspecialchars($userData["realname"]) 
                                                                                                                                            ?>">
                        </div>

                        <div class="mb-3">
                            <label for="accountEmail" class="form-label">Adresă email</label>
                            <input type="text" class="form-control" id="accountEmail" name="NEW_EMAIL" placeholder="john.doe@mail.com" value="<?php 
                                                                                                                                                 echo htmlspecialchars($userData["email"]) 
                                                                                                                                               ?>">
                        </div>

                        <div class="mb-3">
                            <label for="accountUsername" class="form-label">Nume utilizator</label>
                            <input type="text" class="form-control" id="accountUsername" name="NEW_USERNAME" placeholder="Username" value="<?php 
                                                                                                                                              echo htmlspecialchars($userData["username"]) 
                                                                                                                                            ?>">
                        </div>

                        <div class="mb-3">
                            <label for="accountPassword" class="form-label">Parolă</label>
                            <input type="password" class="form-control" id="accountPassword" name="NEW_PASSWORD" placeholder="Lasă gol pentru a nu modifica">
                        </div>

                        <div class="mb-3">
                            <label for="accountNospoof" class="form-label">Cuvânt secret</label>
                            <input type="text" class="form-control" id="accountNospoof" name="NEW_NOSPOOF" placeholder="Setează un cuvânt nou">
                        </div>

                        <button type="submit" class="btn btn-primary">Salvează</button>
                    </form>

                    <form class="mt-2" target="action-frame" method="POST" action="action/action_sessionend.php">
                        <?php CSRF::input(); ?>
                        <button type="submit" class="btn btn-danger">Termină sesiunea</button>
                    </form>
                </div>

                <div class="col-md-6 text-start ps-4">
                    <h3 class="mb-4">Comenzile mele</h3>
                    <?php 
                        if(Auth::hasPerm(Auth::PERM_USERS)) 
                        {
                            $userData = Auth::getData();
                            $tickets = Query::getTickets($userData["id"]);
                            Draw::accountValidForm($tickets);
                        } 
                        else 
                        {
                            Draw::accountInvalidForm();
                        }
                    ?>
                </div>
            </div>
        </div>
        
        <script>
            window.addEventListener("message", function(message) {
                if(message.data == "refresh") {
                    window.location.reload(true);
                } else if(message.data == "home") {
                    window.location = "index.php";
                }
            });
        </script>
    </body>
</html>
