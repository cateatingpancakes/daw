<!DOCTYPE html>
<html lang="en">
    <?php
        require_once(__DIR__ . "/require/connect.php");
        require_once(__DIR__ . "/require/query.php");
        require_once(__DIR__ . "/require/auth.php");
        require_once(__DIR__ . "/view/draw.php"); 
        require_once(__DIR__ . "/require/stat.php");

        Stat::log($_SERVER["SCRIPT_NAME"]);
        
        $userData = Auth::getData();
    ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="css/all.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>

        <title>Proiect DAW - Contact</title>
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
                        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                        <?php Draw::statsLink(); ?>
                        <?php Draw::loginLink(); ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container content text-center">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-6 col-md-8 text-start">
                    
                    <h3 class="mb-4">Contactează-ne</h3>
                    <p class="text-muted mb-4">Ai o întrebare sau o sugestie? Completează formularul de mai jos.</p>

                    <form action="action/action_contact.php" method="POST" target="action-frame">
                        <?php
                            CSRF::input();
                        ?>

                        <div class="mb-3">
                            <label for="contactName" class="form-label">Numele tău</label>
                            <input type="text" class="form-control" id="contactName" name="NAME" 
                                   placeholder="John Doe" 
                                   value="<?php echo $userData ? htmlspecialchars($userData["realname"]) : ""; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="contactEmail" class="form-label">Adresă email</label>
                            <input type="email" class="form-control" id="contactEmail" name="EMAIL" 
                                   placeholder="john.doe@mail.com" 
                                   value="<?php echo $userData ? htmlspecialchars($userData["email"]) : ""; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Mesaj</label>
                            <textarea class="form-control" id="contactMessage" name="MESSAGE" rows="5" placeholder="Ce vrei să ne spui?" required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Trimite</button>
                        </div>
                    </form>

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