<?php
    require_once(__DIR__ . "/tfpdf/tfpdf.php");
    require_once(__DIR__ . "/phpqrcode/qrlib.php");
    require_once(__DIR__ . "/require/connect.php");
    require_once(__DIR__ . "/require/query.php");
    require_once(__DIR__ . "/require/auth.php");

    function dumpQR($as, $what) 
    {
        $path = __DIR__ . "/qrdump/" . $as;
        QRcode::png($what, $path, QR_ECLEVEL_L, 4, 1);
        return $path;
    }

    $color = "#db5d1eff";
    list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");

    $size = 100;
    $pdf = new tFPDF("P", "mm", array($size, $size));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(0, 0, 0);
    $pdf->AddPage();

    $pdf->AddFont("DIN Black", "", "dinblack.php");
    $pdf->AddFont("Arial Unicode", "", "arial_unicode.ttf", true);
    
    $pdf->SetFillColor(250, 250, 250);
    $pdf->Rect(0, 0, $size, $size, 'F');
    
    $pdf->SetDrawColor($r, $g, $b);
    $pdf->SetLineWidth(2);
    $pdf->Rect(0.125, 0.125, $size - 1, $size - 1);

    $pdf->SetFillColor($r, $g, $b);
    $pdf->Rect(0, 0, $size, 18, "F"); 
    
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont("DIN Black", "", 14);
    $pdf->SetXY(5, 5);
    $pdf->Cell(90, 8, strtoupper($movieName), 0, 0, "L");
    
    $pdf->SetFont("Arial Unicode", "", 8);
    $pdf->SetXY(5, 11);
    $pdf->Cell(90, 5, $movieRuntime . " MIN | " . strtoupper($projDate), 0, 0, "L");

    $pdf->SetTextColor($r, $g, $b);
    $pdf->SetFont("DIN Black", "", 10);
    
    $pdf->SetXY(10, 25);
    $pdf->Cell(20, 5, "SALA", 0, 0, "C");
    $pdf->SetXY(40, 25); 
    $pdf->Cell(20, 5, "RAND", 0, 0, "C");
    $pdf->SetXY(70, 25); 
    $pdf->Cell(20, 5, "LOC", 0, 0, "C");

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont("DIN Black", "", 24);
    $pdf->SetXY(10, 30); $pdf->Cell(20, 12, str_replace("Sala ", "", $roomName), 0, 0, "C");
    $pdf->SetXY(40, 30); $pdf->Cell(20, 12, $seatRow, 0, 0, "C");
    $pdf->SetXY(70, 30); $pdf->Cell(20, 12, $seatCol, 0, 0, "C");

    $qrPath = dumpQR("$saleCode" . ".png", $saleCode);
    $pdf->Image($qrPath, 35, 45, 30, 30);
    
    $pdf->SetFont("Arial Unicode", "", 6);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->SetXY(0, 75.5);
    $pdf->Cell($size, 4, "REF: " . strtoupper($saleCode), 0, 0, "C");

    $pdf->Line(10, 81, 90, 81);
    
    $pdf->SetTextColor($r, $g, $b);
    $pdf->SetFont("DIN Black", "", 10);
    $pdf->SetXY(10, 83);
    $pdf->Cell(40, 6, strtoupper($ticketType), 0, 0, "L");
    
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(50, 83);
    $pdf->Cell(40, 6, $ticketPrice . " RON", 0, 0, "R");

    $pdf->SetFont("Arial Unicode", "", 5);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->SetXY(10, 90);
    $pdf->MultiCell(80, 2.5, "Vă rugăm să aveți biletul pregătit pentru scanare. Accesul în sală este permis cu 15 minute înainte de începerea proiecției. Pentru biletele de studenți trebuie prezentat actul doveditor.", 0, "L");

?>