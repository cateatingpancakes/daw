<?php

    require_once(__DIR__ . "/require/query.php");
    require_once(__DIR__ . "/require/auth.php");
    require_once(__DIR__ . "/require/stat.php");
    require_once(__DIR__ . "/tfpdf/tfpdf.php"); 

    Auth::gateBy(Auth::PERM_ADMIN);

    Stat::log($_SERVER["SCRIPT_NAME"]);

    $data = Query::exportStat();
    $table = [ ["USERNAME", "IP", "TIME", "PAGE"] ];

    foreach($data as $record)
    {
        array_push($table, [$record["USERNAME"], $record["IP"], $record["TIME"], $record["PAGE"]]);
    }

    $pdf = new tFPDF();
    $pdf->AddPage();

    $pdf->AddFont("DejaVu", "", "DejaVuSansMono.ttf", true);
    $pdf->SetFont("DejaVu", "", 10);

    $cellWidth = [30, 20, 35, 80];
    $cellHeight = 6;

    $pdf->SetFillColor(240, 240, 240); 
    $pdf->SetFont("DejaVu", "", 10); 

    foreach($table[0] as $i => $headerCol) 
    {
        $pdf->Cell($cellWidth[$i], $cellHeight, $headerCol, 1, 0, "L", true);
    }

    $pdf->Ln();
    $pdf->SetFont("DejaVu", "", 8);

    $rowCount = count($table);
    for($i = 1; $i < $rowCount; $i++) 
    {
        foreach($table[$i] as $j => $col) 
        {
            if($col == null)
            {
                $pdf->Cell($cellWidth[$j], $cellHeight, "NULL", 1, 0, "L", false);
            }
            else
            {
                $pdf->Cell($cellWidth[$j], $cellHeight, $col, 1, 0, "L", false);
            }
        }

        $pdf->Ln();
    }

    $pdf->Output();
?>