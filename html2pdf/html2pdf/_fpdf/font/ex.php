<?php
require('chinese.php');

$pdf=new PDF_Chinese();
$pdf->AddBig5Font();
$pdf->AddPage();
$pdf->SetFont('GB','',20);
$pdf->Write(10,'�����ʲô��');
$pdf->Output();
?>
