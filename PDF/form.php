<?php


        $name = $_POST['customer_name'];
        $address = $_POST['address'];

        $books= $_POST['books'];





    require("fpdf/fpdf.php");
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont("Arial","I",12);
    $pdf->Cell(180,20,"Welcome {$name} To KOLPOBD",1,1,'C');
    $pdf->Cell(180,20,"Book Ordered : $books",1,1);
    $pdf->Cell(180,20,"Delivery Location : $address",1,1);

    $pdf->output();

?>