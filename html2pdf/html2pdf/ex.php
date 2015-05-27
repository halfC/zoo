<?php
require('chinese.php');

$pdf=new PDF_Chinese();
$pdf->AddGBFont();
$pdf->AddPage();
$pdf->SetFont('GB','',20);
$pdf->Write(10,'这个是什么啊');
$pdf->Output();
?>
