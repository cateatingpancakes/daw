<!DOCTYPE html>
<html lang="en">
    <?php
        require_once(__DIR__ . "/require/connect.php");
        require_once(__DIR__ . "/require/query.php");
        require_once(__DIR__ . "/require/auth.php");
        require_once(__DIR__ . "/view/draw.php"); 
        require_once(__DIR__ . "/view/utility.php");
        require_once(__DIR__ . "/require/stat.php");
        require_once(__DIR__ . '/jpgraph/src/jpgraph.php');
        require_once(__DIR__ . '/jpgraph/src/jpgraph_bar.php');

        Auth::gateBy(Auth::PERM_ADMIN);

        Stat::log($_SERVER["SCRIPT_NAME"]);
    ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="css/all.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>

        <title>Proiect DAW - Statistici de utilizare</title>
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
                $top = Query::countStat();

                if(!empty($top)) 
                {
                    usort($top, function($a, $b) 
                    {
                        return $b['NO_ACCESS'] <=> $a['NO_ACCESS'];
                    });
            
                    $top = array_slice($top, 0, 5);
            
                    $values = [];
                    $labels = [];
                    foreach ($top as $entry) 
                    {
                        $values[] = $entry["NO_ACCESS"];
                        $labels[] = basename($entry["TARGET"]);
                    }

                    $graph = new Graph(800, 500, "auto");
                    $graph->SetScale("textlin");
                    $graph->img->SetMargin(60, 30, 40, 80);
                    $graph->SetBox(false);
                    $graph->ygrid->SetFill(false);
                    $graph->yaxis->HideLine(false);
                    $graph->yaxis->HideTicks(false, false);
                    $graph->xaxis->SetTickLabels($labels);
                    $graph->xaxis->SetLabelAngle(45);

                    $bplot = new BarPlot($values);
                    $bplot->SetFillColor("#0d6efd"); 
                    $bplot->SetColor("white");
                    $bplot->SetWeight(0);
                    $bplot->value->Show();
                    $bplot->value->SetFormat("%d");
                    $graph->Add($bplot);

                    $tempPath = __DIR__ . "/stats_" . uniqid() . ".png";
                    
                    try 
                    {
                        $graph->Stroke($tempPath);
                        
                        if(file_exists($tempPath)) 
                        {
                            $imageData = file_get_contents($tempPath);
                            $imageEncoded = base64_encode($imageData);
                            unlink($tempPath);

                            echo "<div>
                                    <h4 class=\"mt-4\">Topul paginilor</h4>
                                  </div>
                                  
                                  <div>
                                    <img src=\"data:image/png;base64," . $imageEncoded . "\" class=\"img-fluid border rounded shadow-sm\">
                                  </div>";
                        } 
                        else 
                        {
                            throw new Exception();
                        }
                    } 
                    catch(Exception $e) 
                    {
                         if (file_exists($tempFile)) 
                            unlink($tempFile);
                    }

                } 
                else 
                {
                    echo "<div class=\"alert alert-info\">Nu există date de afișat.</div>";
                }
            ?>

            <a href="stats_dump.php">Vezi cu toate datele</a>
        </div>
        
    </body>
</html>
