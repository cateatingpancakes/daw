<!DOCTYPE html>
<html lang="en">
    <?php
        require_once(__DIR__ . "/require/connect.php");
        require_once(__DIR__ . "/require/query.php");
        require_once(__DIR__ . "/require/auth.php");
        require_once(__DIR__ . "/view/draw.php"); 
    ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="css/all.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>
        <script src="js/show.js" defer></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <title>Proiect DAW - Înregistrare</title>
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
                        <li class="nav-item"><a class="nav-link" href="#">Filme</a></li>
                        <?php Draw::loginLink(); ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container content">

            <div class="row justify-content-center mt-5">
                <div class="col-lg-4 col-md-6">
                    
                    <h2 class="text-center mb-4">Înregistrare</h2>
                    
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Atenție!</strong> Aplicația chiar va încerca să trimită mail la adresa specificată.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form method="POST" action="register_after.php">
                        <div class="mb-3">
                            <label for="registerRealname" class="form-label">Nume și prenume</label>
                            <input type="text" class="form-control" id="registerRealname" name="REG_REALNAME" placeholder="John Doe">
                        </div>

                        <div class="mb-3">
                            <label for="registerUsername" class="form-label">Nume de utilizator</label>
                            <input type="text" class="form-control" id="registerUsername" name="REG_USERNAME" placeholder="Username">
                        </div>

                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Adresă de email</label>
                            <input type="text" class="form-control" id="registerEmail" name="REG_EMAIL" placeholder="john.doe@mail.com">
                        </div>

                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Parolă</label>
                            <input type="password" class="form-control" id="registerPassword" name="REG_PASSWORD" placeholder="Password">
                        </div>

                        <div class="mb-3 g-recaptcha" data-sitekey="<?php echo Auth::CAPTCHA_PUBLIC ?>"></div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="passwordCheck">
                            <label class="form-check-label" for="passwordCheck">Arată parola</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Creează cont</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </body>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            bindShowPassword("passwordCheck", "registerPassword");
        });
    </script>
</html>
