<!DOCTYPE html>
<html lang="en">
    <?php
        require_once(__DIR__ . "/require/connect.php");
        require_once(__DIR__ . "/require/query.php");
        require_once(__DIR__ . "/require/auth.php");
        require_once(__DIR__ . "/view/draw.php"); 
        require_once(__DIR__ . "/view/utility.php");
        require_once(__DIR__ . "/mail/config.php");
        require_once(__DIR__ . "/mail/class.phpmailer.php");
        require_once(__DIR__ . "/require/stat.php");

        Stat::log($_SERVER["SCRIPT_NAME"]);
    ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="css/all.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>

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
                $passed = false;

                if(isset($_POST["REG_USERNAME"]) &&
                   isset($_POST["REG_REALNAME"]) &&
                   isset($_POST["REG_EMAIL"]) &&
                   isset($_POST["REG_PASSWORD"]))
                {
                    if(filter_var($_POST["REG_EMAIL"], FILTER_VALIDATE_EMAIL) && Auth::captchaStatus() == Auth::STAT_PASS)
                    {
                        $confCode = Auth::createUser($_POST["REG_USERNAME"], $_POST["REG_REALNAME"], $_POST["REG_EMAIL"], $_POST["REG_PASSWORD"]);

                        $whoami = mailcfg\mailcfg::MAIL_WHOAMI;
                        $mailHead = "Confirmare cont pentru proiect DAW";
                        $mailText = "<p>Pentru a vă valida contul nou-creat, folosiți link-ul $whoami/register_valid.php?CONF_CODE=$confCode</p>";

                        mail2($_POST["REG_EMAIL"], $mailHead, $mailText, $_POST["REG_REALNAME"]);

                        $passed = true;
                        echo "<h2>Contul tău a fost creeat</h2>
                              <p>Confirmă înregistrarea folosind linkul primit pe email.</p>";
                    }
                }

                if(!$passed)
                {
                    echo "<h2>Contul tău nu a putut fi creeat</h2>
                          <p>Nu ai completat corect un câmp sau nu ai reușit CAPTCHA-ul.</p>";
                }
            ?>
        </div>
        
    </body>
</html>
