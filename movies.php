<!DOCTYPE html>
<html lang="en">
    <?php
        require_once(__DIR__ . "/require/connect.php");
        require_once(__DIR__ . "/require/query.php");
        require_once(__DIR__ . "/require/auth.php");
        require_once(__DIR__ . "/view/draw.php"); 
        require_once(__DIR__ . "/view/utility.php");
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
                        <li class="nav-item"><a class="nav-link" href="#">Filme</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                        <?php Draw::statsLink(); ?>
                        <?php Draw::loginLink(); ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container content">
            <div class="row g-3">
                <?php

                    $isAdmin = Auth::hasPerm(Auth::PERM_ADMIN);

                    if(!$isAdmin)
                    {
                        echo "<h3>Topul filmelor</h3>
                              <p>Aici poți să vezi care sunt cele mai bune filme conform <a href=\"https://www.imdb.com/chart/top/\">IMDb</a>. Nu le poți adăuga la baza de date a cinematografului, însă.</p>";
                    }
                    else
                    {
                        echo "<h3>Topul filmelor</h3>
                              <p>Poți adăuga filme în baza de date din acest meniu cu cele mai bune filme conform <a href=\"https://www.imdb.com/chart/top/\">IMDb</a>.</p>";
                    }

                    $knownMovies = Query::getAll("movie");
                    $knownNames = [];

                    foreach($knownMovies as $movie)
                    {
                        array_push($knownNames, $movie["NAME"]);
                    }

                    // Preluat din cursul de DAW

                    $url = "https://www.imdb.com/chart/top/";
                    $ch = curl_init($url);

                    // Opțiuni cURL
                    curl_setopt_array($ch, [
                        CURLOPT_RETURNTRANSFER => true,       // returnează conținutul, nu îl afișează
                        CURLOPT_FOLLOWLOCATION => true,       // urmează redirecționările
                        CURLOPT_SSL_VERIFYPEER => false,      // (opțional) dezactivează verificarea SSL
                        CURLOPT_CONNECTTIMEOUT => 10,         // timeout conexiune
                        CURLOPT_TIMEOUT => 20,                // timeout total

                        // Simulăm un browser real (ex: Chrome pe Windows)
                        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '
                                        . 'AppleWebKit/537.36 (KHTML, like Gecko) '
                                        . 'Chrome/118.0.5993.90 Safari/537.36',

                        // Antete suplimentare – opțional, dar pot ajuta
                        CURLOPT_HTTPHEADER => [
                            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            'Accept-Language: en-EN,en;q=0.9,en-US;q=0.8,en;q=0.7',
                            'Connection: keep-alive',
                            'Upgrade-Insecure-Requests: 1',
                            'Cache-Control: max-age=0',
                        ],
                    ]);

                    $continut = curl_exec($ch);

                    if(curl_errno($ch)) 
                    {
                        echo 'Eroare cURL: ' . curl_error($ch);
                    } 
                    else 
                    {
                        $lista_filme = explode('<li class="ipc-metadata-list-summary-item">', $continut);

                        for($i = 1; $i < count($lista_filme); $i++)
                        {
                            $titlu = explode('<h3 class="ipc-title__text">', $lista_filme[$i]);
                            $titlu = explode("</h3>", $titlu[1]);
                            $titlu = explode(". ", $titlu[0]);
                            $titlu = $titlu[0];

                            $info = explode('cli-title-metadata-item">', $lista_filme[$i]);
                            $ani = explode("</span>", $info[1]);
                            $ani = $ani[0];

                            $durate = explode("</span>", $info[2]);
                            $durata = explode("</span>", $durate[0]);
                            $durata = $durata[0];

                            $scor = explode('<span class="ipc-rating-star--rating">', $lista_filme[$i]);
                            $scor = explode("</span>", $scor[1]);
                            $scor = $scor[0];


                            $isKnown = in_array($titlu, $knownNames);
                            Draw::movieCard2($titlu, $ani, imdbtime($durata), $scor, !$isKnown, $isAdmin);
                        }
                    }

                    // Acum se inchide automat, conf. documentatiei.
                    // (Avem deprecated)
                    // curl_close($ch); 
                ?>
            </div>
        </div>
        
        <script>
            window.addEventListener("message", function(message) {
                if(message.data == "refresh") {
                    window.location.reload(true);
                }
            });
        </script>
    </body>
</html>
